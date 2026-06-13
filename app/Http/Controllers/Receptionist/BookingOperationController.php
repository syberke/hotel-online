<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingOperationController extends Controller
{
    // 1. Approve Booking (Pending -> Confirmed)
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking #' . $booking->id . ' telah dikonfirmasi.');
    }

    // 2. Check In Guest (Confirmed -> Checked In & Kamar status -> occupied)
    public function checkIn($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'checked_in']);
        
        // Ubah status kamar fisik menjadi occupied
        Room::where('id', $booking->room_id)->update(['status' => 'occupied']);

        return back()->with('success', 'Tamu berhasil Check-In. Kamar telah terisi.');
    }

    // 3. Check Out Guest (Checked In -> Checked Out & Kamar status -> available kembali)
    public function checkOut($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'checked_out']);
        
        // Kembalikan status kamar fisik menjadi available
        Room::where('id', $booking->room_id)->update(['status' => 'available']);

        return back()->with('success', 'Tamu berhasil Check-Out. Kamar siap disewakan kembali.');
    }
}
