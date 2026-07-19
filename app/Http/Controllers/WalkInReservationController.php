<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;

class WalkInReservationController extends Controller
{
    public function create(): View
    {
        $roomTypes = DB::table('room_types')
            ->leftJoin('rooms', function ($join): void {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                    ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.price',
                'room_types.max_capacity',
                DB::raw('COUNT(rooms.id) as available_rooms'),
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.price', 'room_types.max_capacity')
            ->orderBy('room_types.price')
            ->get();

        return view('receptionist.walk-in', compact('roomTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:50'],
            'phone' => ['required', 'string', 'max:15'],
            'identity_number' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:12'],
            'payment_method' => ['required', 'in:cash,transfer,credit_card,e_wallet'],
            'payment_status' => ['required', 'in:pending,paid'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            $bookingId = DB::transaction(function () use ($validated, $request): int {
                $roomType = DB::table('room_types')
                    ->where('id', $validated['room_type_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $roomType) {
                    throw new RuntimeException('Tipe kamar tidak ditemukan.');
                }

                $maxCapacity = max(1, (int) ($roomType->max_capacity ?? 2));
                if ((int) $validated['guests_count'] > $maxCapacity) {
                    throw new RuntimeException(
                        'Jumlah tamu melebihi kapasitas maksimum '.$roomType->name.' yaitu '.$maxCapacity.' orang.'
                    );
                }

                $occupiedRoomIds = DB::table('bookings')
                    ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->where('check_in', '<', $validated['check_out'])
                    ->where('check_out', '>', $validated['check_in'])
                    ->pluck('room_id');

                $room = DB::table('rooms')
                    ->where('room_type_id', $roomType->id)
                    ->where('status', 'available')
                    ->when($occupiedRoomIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $occupiedRoomIds))
                    ->orderBy('room_number')
                    ->lockForUpdate()
                    ->first();

                if (! $room) {
                    throw new RuntimeException(
                        'Tidak ada kamar '.$roomType->name.' yang tersedia pada tanggal tersebut.'
                    );
                }

                $contactEmail = strtolower(trim((string) ($validated['email'] ?? '')));
                $guest = $contactEmail !== ''
                    ? DB::table('guests')->whereRaw('LOWER(email) = ?', [$contactEmail])->first()
                    : null;

                if ($guest) {
                    DB::table('guests')->where('id', $guest->id)->update([
                        'name' => $validated['name'],
                        'phone' => $validated['phone'],
                        'identity_number' => $validated['identity_number'],
                        'address' => $validated['address'],
                        'updated_at' => now(),
                    ]);
                    $guestId = (int) $guest->id;
                } else {
                    $storedEmail = $contactEmail !== ''
                        ? $contactEmail
                        : 'walkin.'.Str::uuid().'@oasis.local';

                    $guestId = (int) DB::table('guests')->insertGetId([
                        'name' => $validated['name'],
                        'email' => $storedEmail,
                        'password' => Hash::make(Str::random(48)),
                        'phone' => $validated['phone'],
                        'identity_number' => $validated['identity_number'],
                        'address' => $validated['address'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $nights = max(1, now()->parse($validated['check_in'])->diffInDays(now()->parse($validated['check_out'])));
                $totalPrice = round((float) $roomType->price * $nights, 2);
                $bookingStatus = $validated['payment_status'] === 'paid' ? 'confirmed' : 'pending';

                $bookingId = (int) DB::table('bookings')->insertGetId([
                    'user_id' => null,
                    'created_by_user_id' => $request->user()->id,
                    'guest_id' => $guestId,
                    'room_id' => $room->id,
                    'check_in' => $validated['check_in'],
                    'check_out' => $validated['check_out'],
                    'guests_count' => $validated['guests_count'],
                    'total_price' => $totalPrice,
                    'status' => $bookingStatus,
                    'booking_source' => 'walk_in',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::table('payments')->insert([
                    'booking_id' => $bookingId,
                    'restaurant_order_id' => null,
                    'amount' => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $validated['payment_status'],
                    'note' => trim('Walk-in reservation registered by Front Desk. '.($validated['notes'] ?? '')),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $bookingId;
            });
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()
            ->route('receptionist.reservations', ['search' => $bookingId])
            ->with('success', 'Reservasi walk-in #OA-'.str_pad((string) $bookingId, 5, '0', STR_PAD_LEFT).' berhasil dibuat.');
    }
}
