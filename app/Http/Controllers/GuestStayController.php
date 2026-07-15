<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GuestStayController extends Controller
{
    public function dashboard()
    {
        $bookings = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', Auth::id())
            ->select('bookings.*', 'rooms.room_number', 'room_types.name as room_type_name')
            ->orderBy('bookings.check_in', 'asc')
            ->get();

        $guestRecord = DB::table('guests')->where('email', Auth::user()->email)->first();

        $activeOrders = $guestRecord
            ? DB::table('restaurant_orders')->where('guest_id', $guestRecord->id)->orderBy('created_at', 'desc')->get()
            : collect();

        try {
            $weatherResponse = Http::timeout(2)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => -8.4095,
                'longitude' => 115.1889,
                'current_weather' => true
            ]);

            $temperature = $weatherResponse->successful()
                ? round($weatherResponse->json()['current_weather']['temperature']) . '°C'
                : '29°C';
        } catch (\Exception $e) {
            $temperature = '29°C';
        }

        return view('guest.dashboard', compact('bookings', 'activeOrders', 'temperature'));
    }

    public function myBookings()
    {
        $bookings = DB::table('bookings')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'rooms.room_number', 'room_types.name as type_name')
            ->orderBy('bookings.check_in', 'desc')
            ->get();

        return view('guest.mybookings', compact('bookings'));
    }

    public function myStay(Request $request)
    {
        $userId = auth()->id();

        $allActiveBookings = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.booking_id', '=', 'bookings.id')
                    ->where('payments.payment_status', 'paid');
            })
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->where(function ($query) {
                $query->whereNotNull('payments.id')
                    ->orWhere('bookings.status', 'checked_in');
            })
            ->select('bookings.id', 'rooms.room_number')
            ->get();

        $targetBookingId = $request->input('booking_id', optional($allActiveBookings->first())->id);

        $currentBooking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.booking_id', '=', 'bookings.id')
                    ->where('payments.payment_status', 'paid');
            })
            ->where('bookings.id', $targetBookingId)
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.id as booking_id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.guests_count',
                'bookings.status',
                'room_types.name as room_name',
                'room_types.foto_url',
                'rooms.room_number',
                'bookings.total_price as room_bill',
                'payments.id as payment_id'
            )
            ->first();

        $hasPaidBooking = $currentBooking && $currentBooking->status === 'checked_in' && !empty($currentBooking->payment_id);

        $restaurantBill = DB::table('restaurant_orders')
            ->where('guest_id', $userId)
            ->where('status', 'ordered')
            ->sum('total_price');

        $itineraries = DB::table('facility_bookings')
            ->where('user_id', $userId)
            ->where('booking_date', date('Y-m-d'))
            ->orderBy('booking_time', 'asc')
            ->get();

        return view('guest.mystay', compact('currentBooking', 'restaurantBill', 'itineraries', 'allActiveBookings', 'hasPaidBooking'));
    }

    public function cancelBooking($id)
    {
        $userId = auth()->id();
        $booking = DB::table('bookings')->where('id', $id)->where('user_id', $userId)->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Data reservasi tidak valid.');
        }
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya reservasi pending yang dapat dibatalkan.');
        }

        DB::transaction(function () use ($booking) {
            DB::table('bookings')->where('id', $booking->id)->update([
                'status' => 'canceled',
                'updated_at' => now(),
            ]);
            DB::table('payments')->where('booking_id', $booking->id)->update([
                'payment_status' => 'failed',
                'note' => 'Dibatalkan oleh pengguna.',
                'updated_at' => now(),
            ]);
        });

        return redirect()->back()->with('success', 'Reservasi kamar #' . str_pad($booking->id, 2, '0', STR_PAD_LEFT) . ' dibatalkan.');
    }
}
