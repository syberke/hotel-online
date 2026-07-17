<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalkInController extends Controller
{
    public function create(): View
    {
        $rooms = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('rooms.status', 'available')
            ->select(
                'rooms.id',
                'rooms.room_number',
                'room_types.name as room_type',
                'room_types.price',
            )
            ->orderBy('rooms.room_number')
            ->get();

        $today = now()->toDateString();
        $stats = [
            'available_rooms' => $rooms->count(),
            'checkins_today' => DB::table('bookings')->whereDate('check_in', $today)->where('status', 'checked_in')->count(),
            'checkouts_today' => DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_out')->count(),
            'in_house_guests' => (int) DB::table('bookings')->where('status', 'checked_in')->sum('guests_count'),
        ];

        return view('receptionist.walkin', compact('rooms', 'stats'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:15'],
            'identity_number' => ['required', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:10'],
            'payment_method' => ['required', 'in:cash,transfer,credit_card,e_wallet'],
        ]);

        $temporaryPassword = Str::password(14);
        $createdNewAccount = false;

        $booking = DB::transaction(function () use ($validated, $temporaryPassword, &$createdNewAccount) {
            $room = DB::table('rooms')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('rooms.id', $validated['room_id'])
                ->where('rooms.status', 'available')
                ->lockForUpdate()
                ->select('rooms.id', 'rooms.room_number', 'room_types.price')
                ->first();

            abort_unless($room, 422, 'Kamar yang dipilih tidak lagi tersedia. Muat ulang halaman dan pilih kamar lain.');

            $user = User::query()->whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
            if (! $user) {
                $user = User::query()->create([
                    'name' => $validated['name'],
                    'email' => strtolower($validated['email']),
                    'email_verified_at' => now(),
                    'password' => $temporaryPassword,
                    'role' => 'guest',
                    'account_status' => 'active',
                ]);
                $createdNewAccount = true;
            } else {
                abort_unless($user->role === 'guest', 422, 'Email tersebut sudah dipakai oleh akun staf dan tidak dapat digunakan untuk walk-in guest.');
                $user->forceFill(['name' => $validated['name']])->save();
            }

            $guest = DB::table('guests')->whereRaw('LOWER(email) = ?', [strtolower($validated['email'])])->first();
            if ($guest) {
                DB::table('guests')->where('id', $guest->id)->update([
                    'name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'identity_number' => $validated['identity_number'],
                    'address' => $validated['address'] ?? null,
                    'updated_at' => now(),
                ]);
                $guestId = $guest->id;
            } else {
                $guestId = DB::table('guests')->insertGetId([
                    'name' => $validated['name'],
                    'email' => strtolower($validated['email']),
                    'email_verified_at' => now(),
                    'password' => $user->getAuthPassword(),
                    'phone' => $validated['phone'],
                    'identity_number' => $validated['identity_number'],
                    'address' => $validated['address'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $nights = max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out'])));
            $total = (float) $room->price * $nights;

            $bookingId = DB::table('bookings')->insertGetId([
                'guest_id' => $guestId,
                'user_id' => $user->id,
                'room_id' => $room->id,
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'guests_count' => $validated['guests_count'],
                'total_price' => $total,
                'status' => 'checked_in',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('rooms')->where('id', $room->id)->update([
                'status' => 'occupied',
                'updated_at' => now(),
            ]);

            DB::table('payments')->insert([
                'booking_id' => $bookingId,
                'restaurant_order_id' => null,
                'amount' => $total,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'note' => 'Walk-in reservation payment',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return (object) ['id' => $bookingId, 'room_number' => $room->room_number, 'total' => $total];
        });

        $message = 'Walk-in berhasil dibuat. Booking #OA-' . str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT)
            . ', kamar ' . $booking->room_number . ', total Rp ' . number_format($booking->total, 0, ',', '.') . '.';

        if ($createdNewAccount) {
            $message .= ' Akun guest baru dibuat. Minta tamu menggunakan Forgot Password pada email ' . $validated['email'] . ' untuk membuat password pribadi.';
        }

        return redirect()->route('receptionist.folio', ['booking_id' => $booking->id])->with('success', $message);
    }
}
