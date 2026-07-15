<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomLifecycleController extends FrontOfficeCheckController
{
    public function adminStoreRoom(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $validated = $request->validate([
            'room_number' => ['required', 'string', 'unique:rooms,room_number'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance,dirty'],
        ]);

        $status = $validated['status'] === 'dirty' ? 'maintenance' : $validated['status'];

        DB::table('rooms')->insert([
            'room_number' => $validated['room_number'],
            'room_type_id' => $validated['room_type_id'],
            'status' => $status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kamar baru nomor ' . $validated['room_number'] . ' berhasil didaftarkan.');
    }

    public function adminUpdateRoomStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:available,occupied,maintenance,dirty'],
        ]);

        $status = $validated['status'] === 'dirty' ? 'maintenance' : $validated['status'];

        DB::table('rooms')->where('id', $id)->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Manifes status operasional unit kamar berhasil diperbarui.');
    }

    public function processCheckOut(Request $request)
    {
        $search = $request->input('search');
        $bookingId = $request->input('booking_id');

        $selectedBooking = null;
        $charges = [];
        $totalCharges = 0;
        $totalPayments = 0;
        $balanceDue = 0;

        $activeBookingsQuery = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereIn('bookings.status', ['checked_in', 'confirmed'])
            ->select(
                'bookings.id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.total_price',
                'bookings.status',
                'users.name as guest_name',
                'users.email as guest_email',
                'rooms.room_number',
                'room_types.name as room_type',
                'room_types.price as room_price'
            );

        if ($search) {
            $activeBookingsQuery->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('rooms.room_number', 'like', "%{$search}%")
                    ->orWhere('bookings.id', 'like', "%{$search}%");
            });
        }

        $activeBookings = $activeBookingsQuery->orderBy('bookings.created_at', 'desc')->get();

        if ($bookingId) {
            $selectedBooking = $activeBookings->firstWhere('id', $bookingId);
        } elseif ($search && $activeBookings->isNotEmpty()) {
            $selectedBooking = $activeBookings->first();
        } elseif ($activeBookings->isNotEmpty()) {
            $selectedBooking = $activeBookings->firstWhere('status', 'checked_in') ?? $activeBookings->first();
        }

        if ($selectedBooking) {
            $selectedBooking->guest_name = $selectedBooking->guest_name ?? 'Tamu';
            $selectedBooking->room_number = $selectedBooking->room_number ?? 'TBD';
            $selectedBooking->room_type = $selectedBooking->room_type ?? 'Standard';
            $selectedBooking->room_price = $selectedBooking->room_price ?? 0;

            $checkInDate = Carbon::parse($selectedBooking->check_in);
            $checkOutDate = Carbon::parse($selectedBooking->check_out);
            $nights = $checkInDate->diffInDays($checkOutDate) ?: 1;

            for ($i = 0; $i < $nights; $i++) {
                $charges[] = [
                    'date' => $checkInDate->copy()->addDays($i)->format('d M Y'),
                    'description' => "Room Charge ({$selectedBooking->room_type})",
                    'reference' => "Room {$selectedBooking->room_number}",
                    'debit' => $selectedBooking->room_price,
                    'credit' => 0,
                ];
                $totalCharges += $selectedBooking->room_price;
            }

            $extraServices = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->where('payment_status', 'paid')
                ->whereNull('restaurant_order_id')
                ->get();

            foreach ($extraServices as $service) {
                $totalPayments += $service->amount;
                $charges[] = [
                    'date' => Carbon::parse($service->created_at)->format('d M Y'),
                    'description' => 'Advance Deposit / System Payment',
                    'reference' => 'PAY-00' . $service->id,
                    'debit' => 0,
                    'credit' => $service->amount,
                ];
            }

            $balanceDue = max(0, $totalCharges - $totalPayments);
        }

        if ($request->isMethod('post') && $request->has('confirm_checkout_id')) {
            $targetId = $request->input('confirm_checkout_id');
            $bookingRecord = DB::table('bookings')->where('id', $targetId)->first();

            if ($bookingRecord && $bookingRecord->status === 'checked_in') {
                DB::transaction(function () use ($bookingRecord) {
                    DB::table('bookings')->where('id', $bookingRecord->id)->update([
                        'status' => 'checked_out',
                        'updated_at' => now(),
                    ]);

                    DB::table('rooms')->where('id', $bookingRecord->room_id)->update([
                        'status' => 'maintenance',
                        'updated_at' => now(),
                    ]);
                });

                return redirect()->route('receptionist.dashboard')->with('success', 'Proses check-out Kamar berhasil diselesaikan.');
            }

            return redirect()->route('receptionist.checkout')->with('error', 'Tamu ini tidak lagi aktif untuk check-out.');
        }

        return view('receptionist.checkout', compact(
            'selectedBooking',
            'activeBookings',
            'charges',
            'totalCharges',
            'totalPayments',
            'balanceDue',
            'search'
        ));
    }
}
