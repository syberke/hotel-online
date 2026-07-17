<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StaffRoomDetailController extends Controller
{
    public function show(int $id): JsonResponse
    {
        $room = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('rooms.id', $id)
            ->select(
                'rooms.id',
                'rooms.room_number',
                'rooms.status',
                'rooms.room_type_id',
                'room_types.name as type_name',
                'room_types.price',
                'room_types.max_capacity',
            )
            ->first();

        if (!$room) {
            return response()->json([
                'success' => false,
                'message' => 'Unit kamar tidak ditemukan.',
            ], 404);
        }

        $today = now()->toDateString();
        $bookings = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.room_id', $room->id)
            ->whereIn('bookings.status', ['pending', 'confirmed', 'checked_in'])
            ->whereDate('bookings.check_out', '>', $today)
            ->select(
                'bookings.id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.guests_count',
                'bookings.status',
                'users.name as guest_name',
                'users.email as guest_email',
            )
            ->orderBy('bookings.check_in')
            ->get();

        $activeBooking = $bookings->first(function ($booking) use ($today) {
            return $booking->status === 'checked_in'
                && $booking->check_in <= $today
                && $booking->check_out > $today;
        });

        if ($activeBooking) {
            $displayStatus = 'occupied';
        } elseif ($bookings->isNotEmpty()) {
            $activeBooking = $bookings->first();
            $displayStatus = 'reserved';
        } else {
            $displayStatus = $room->status === 'occupied' ? 'available' : $room->status;
        }

        $room->physical_status = $room->status;
        $room->status = $displayStatus;

        return response()->json([
            'success' => true,
            'room' => $room,
            'booking' => $activeBooking,
        ]);
    }
}
