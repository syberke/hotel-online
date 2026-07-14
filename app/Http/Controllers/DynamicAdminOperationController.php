<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicAdminOperationController extends AdminOperationController
{
    public function adminStoreFacility(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name'],
            'category' => ['required', 'string', 'max:100'],
            'hours' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'url'],
            'requires_booking' => ['nullable', 'boolean'],
            'hourly_capacity' => ['nullable', 'integer', 'min:0'],
            'price_per_person' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::table('facilities')->insert([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'hours' => $validated['hours'],
            'image_url' => $validated['image_url'] ?? null,
            'requires_booking' => (bool) ($validated['requires_booking'] ?? true),
            'hourly_capacity' => (int) ($validated['hourly_capacity'] ?? 0),
            'price_per_person' => (float) ($validated['price_per_person'] ?? 0),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Fasilitas area baru berhasil didaftarkan.');
    }

    public function adminUpdateFacility(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:facilities,name,' . $id],
            'category' => ['required', 'string', 'max:100'],
            'hours' => ['required', 'string', 'max:100'],
            'image_url' => ['nullable', 'url'],
            'requires_booking' => ['nullable', 'boolean'],
            'hourly_capacity' => ['nullable', 'integer', 'min:0'],
            'price_per_person' => ['nullable', 'numeric', 'min:0'],
        ]);

        $facility = DB::table('facilities')->where('id', $id)->first();
        if (!$facility) {
            return redirect()->back()->with('error', 'Fasilitas tidak ditemukan.');
        }

        DB::transaction(function () use ($validated, $facility, $id) {
            $oldName = $facility->name;
            $newName = $validated['name'];

            DB::table('facilities')->where('id', $id)->update([
                'name' => $newName,
                'category' => $validated['category'],
                'hours' => $validated['hours'],
                'image_url' => $validated['image_url'] ?: $facility->image_url,
                'requires_booking' => (bool) ($validated['requires_booking'] ?? $facility->requires_booking),
                'hourly_capacity' => (int) ($validated['hourly_capacity'] ?? $facility->hourly_capacity),
                'price_per_person' => (float) ($validated['price_per_person'] ?? $facility->price_per_person),
                'updated_at' => now(),
            ]);

            if ($oldName !== $newName) {
                DB::table('facility_bookings')
                    ->where('facility_name', $oldName)
                    ->update(['facility_name' => $newName, 'updated_at' => now()]);
            }
        });

        return redirect()->back()->with('success', 'Konfigurasi area fasilitas berhasil diperbarui. Harga baru hanya berlaku untuk reservasi berikutnya.');
    }

    public function adminUpdateReservation(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,checked_in,checked_out,cancelled'],
        ]);

        $booking = Booking::findOrFail($id);
        $booking->forceFill([
            'status' => $validated['status'],
            'updated_at' => now(),
        ])->save();

        return redirect()
            ->route('admin.reservation', ['selected_id' => $booking->id])
            ->with('success', 'Status reservasi berhasil diperbarui. Status pembayaran tetap mengikuti ledger transaksi.');
    }

    public function adminDeleteReservation($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi.');
        }

        $booking = DB::table('bookings')->where('id', $id)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Reservasi tidak ditemukan.');
        }

        if (DB::table('payments')->where('booking_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Reservasi tidak dapat dihapus karena sudah memiliki jejak pembayaran. Batalkan reservasi untuk mempertahankan audit ledger.');
        }

        DB::table('bookings')->where('id', $id)->delete();

        return redirect()->route('admin.reservation')->with('success', 'Reservasi tanpa transaksi pembayaran berhasil dihapus.');
    }

    public function adminDetailReservation($id)
    {
        $booking = Booking::with(['user.guestProfile', 'room.roomType', 'payments'])->find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }

        $latestPayment = $booking->payments->sortByDesc('created_at')->first();
        $guestProfile = $booking->user?->guestProfile;

        return response()->json([
            'success' => true,
            'id' => $booking->id,
            'guest_id' => $guestProfile?->id,
            'identity_number' => $guestProfile?->identity_number,
            'guest_name' => $booking->user?->name ?? 'N/A',
            'guest_email' => $booking->user?->email ?? 'N/A',
            'guest_phone' => $guestProfile?->phone ?: ($booking->user?->phone ?? '-'),
            'guest_address' => $guestProfile?->address ?: ($booking->user?->address ?? 'Tidak ada alamat'),
            'room_type' => $booking->room?->roomType?->name ?? 'Unassigned',
            'room_number' => $booking->room?->room_number ?? 'TBD',
            'check_in' => \Carbon\Carbon::parse($booking->check_in)->format('d M Y'),
            'check_out' => \Carbon\Carbon::parse($booking->check_out)->format('d M Y'),
            'check_in_time' => date('h:i A', strtotime(config('hotel.checkin_time'))),
            'check_out_time' => date('h:i A', strtotime(config('hotel.checkout_time'))),
            'duration' => \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) . ' Malam',
            'guests_count' => $booking->guests_count . ' Orang',
            'status' => $booking->status,
            'payment_method' => $latestPayment?->payment_method
                ? strtoupper(str_replace('_', ' ', $latestPayment->payment_method))
                : null,
            'payment_status' => $latestPayment?->payment_status ?? 'pending',
            'total_price' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
        ]);
    }
}
