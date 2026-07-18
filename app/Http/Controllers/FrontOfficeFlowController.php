<?php

namespace App\Http\Controllers;

use App\Services\BookingFolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class FrontOfficeFlowController extends FrontOfficeCheckController
{
    public function __construct(private readonly BookingFolioService $folioService)
    {
    }

    public function processPayment(Request $request)
    {
        if ($request->isMethod('post') && $request->has('action_process_payment')) {
            $validated = $request->validate([
                'booking_id_hidden' => ['required', 'integer', 'exists:bookings,id'],
                'payment_amount' => ['required', 'numeric', 'min:1'],
                'payment_method' => ['required', Rule::in(['cash', 'transfer', 'credit_card', 'e_wallet'])],
            ]);

            $targetBookingId = (int) $validated['booking_id_hidden'];
            $this->folioService->settle(
                $targetBookingId,
                (float) $validated['payment_amount'],
                $validated['payment_method'],
            );

            return redirect()
                ->route('receptionist.payments', ['booking_id' => $targetBookingId])
                ->with('success', 'Pembayaran folio berhasil dibukukan. Tagihan Room Service yang tercakup sudah ditandai lunas.');
        }

        $bookingId = $request->integer('booking_id');

        if (! $bookingId) {
            $bookingId = (int) (DB::table('bookings')
                ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                ->orderByDesc('created_at')
                ->value('id') ?? 0);
        }

        $selectedBooking = null;
        $totalCharges = 0.0;
        $totalPayments = 0.0;
        $balanceDue = 0.0;
        $paymentHistory = collect();

        if ($bookingId) {
            $ledger = $this->folioService->build($bookingId);

            if ($ledger) {
                $selectedBooking = $ledger['booking'];
                $totalCharges = $ledger['total_charges'];
                $totalPayments = $ledger['total_payments'];
                $balanceDue = $ledger['balance_due'];
                $paymentHistory = $ledger['payment_history'];
            }
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
