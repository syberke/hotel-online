<?php
namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = Room::with('roomType')->find($request->room_id);
        
        // Hitung durasi malam
        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $days = $checkIn->diffInDays($checkOut);

        // Output total harga berdasarkan harga tipe kamar
        $totalPrice = $days * $room->roomType->price;

        return response()->json([
            'room' => $room,
            'duration_nights' => $days,
            'total_price' => $totalPrice
        ]);
    }

    public function store(Request $request)
    {
        // Logika menyimpan booking baru dengan status default 'pending'
        $booking = Booking::create([
            'guest_id' => Auth::guard('guest')->id(), // Menggunakan guard guest jika terpisah dari users umum
            'room_id' => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'total_price' => $request->total_price,
            'status' => 'pending',
        ]);

        return redirect()->route('guest.bookings')->with('success', 'Booking berhasil dibuat, menunggu konfirmasi resepsionis.');
    }
}