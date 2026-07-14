<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FrontOfficeFlowController extends FrontOfficeCheckController
{
    public function processPayment(Request $request)
    {
        $bookingId = $request->input('booking_id');

        if (!$bookingId) {
            $bookingId = DB::table('bookings')
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->orderByDesc('created_at')
                ->value('id');
        }

        $selectedBooking = null;
        $totalCharges = 0.0;
        $totalPayments = 0.0;
        $balanceDue = 0.0;
        $paymentHistory = collect();

        if ($bookingId) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $bookingId)
                ->select(
                    'bookings.*',
                    'users.name as guest_name',
                    'users.email as guest_email',
                    'rooms.room_number',
                    'room_types.name as room_type',
                    'room_types.price as room_price'
                )
                ->first();
        }

        if ($selectedBooking) {
            $totalCharges = (float) $selectedBooking->total_price;
            $paymentHistory = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->orderByDesc('created_at')
                ->get();
            $totalPayments = (float) ($paymentHistory->where('payment_status', 'paid')->sum('amount') ?: 0);
            $balanceDue = max(0, $totalCharges - $totalPayments);
        }

        if ($request->isMethod('post') && $request->has('action_process_payment')) {
            $validated = $request->validate([
                'booking_id_hidden' => ['required', 'integer', 'exists:bookings,id'],
                'payment_amount' => ['required', 'numeric', 'min:1'],
                'payment_method' => ['required', Rule::in(['cash', 'transfer', 'credit_card', 'e_wallet'])],
            ]);

            $targetBookingId = (int) $validated['booking_id_hidden'];
            $chargeAmount = (float) $validated['payment_amount'];

            $booking = DB::table('bookings')->where('id', $targetBookingId)->first();
            $paidAmount = (float) (DB::table('payments')
                ->where('booking_id', $targetBookingId)
                ->where('payment_status', 'paid')
                ->sum('amount') ?: 0);
            $remainingBalance = max(0, (float) $booking->total_price - $paidAmount);

            if ($remainingBalance <= 0) {
                return redirect()
                    ->route('receptionist.payments', ['booking_id' => $targetBookingId])
                    ->with('error', 'Folio reservasi ini sudah lunas.');
            }

            if ($chargeAmount > $remainingBalance) {
                return redirect()
                    ->route('receptionist.payments', ['booking_id' => $targetBookingId])
                    ->with('error', 'Nominal pembayaran melebihi sisa tagihan Rp ' . number_format($remainingBalance, 0, ',', '.') . '.');
            }

            DB::table('payments')->insert([
                'booking_id' => $targetBookingId,
                'amount' => $chargeAmount,
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('receptionist.payments', ['booking_id' => $targetBookingId])
                ->with('success', 'Transaksi pembayaran folio berhasil dibukukan.');
        }

        $receptionistStaff = auth()->user()->name . ' (Receptionist)';

        return view('receptionist.payments', compact(
            'selectedBooking',
            'totalCharges',
            'totalPayments',
            'balanceDue',
            'paymentHistory',
            'receptionistStaff'
        ));
    }
}
