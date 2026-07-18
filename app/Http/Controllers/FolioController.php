<?php

namespace App\Http\Controllers;

use App\Services\BookingFolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FolioController extends Controller
{
    public function __construct(private readonly BookingFolioService $folioService)
    {
    }

    public function show(Request $request): View
    {
        abort_unless(in_array($request->user()?->role, ['admin', 'manager', 'receptionist'], true), 403);

        $bookingId = $request->integer('booking_id');
        if (! $bookingId) {
            $bookingId = (int) (DB::table('bookings')
                ->whereIn('status', ['checked_in', 'checked_out', 'confirmed'])
                ->latest('updated_at')
                ->value('id') ?? 0);
        }

        $selectedBooking = null;
        $charges = collect();
        $totalCharges = 0.0;
        $totalPayments = 0.0;
        $balanceDue = 0.0;
        $departmentTotals = ['Room' => 0.0, 'Restaurant' => 0.0];
        $facilityReservations = collect();

        if ($bookingId) {
            $ledger = $this->folioService->build($bookingId);

            if ($ledger) {
                $selectedBooking = $ledger['booking'];
                $charges = $ledger['charges'];
                $totalCharges = $ledger['total_charges'];
                $totalPayments = $ledger['total_payments'];
                $balanceDue = $ledger['balance_due'];
                $departmentTotals = $ledger['department_totals'];
                $facilityReservations = $ledger['facility_reservations'];
            }
        }

        $departmentShares = $this->folioService->departmentShares($departmentTotals);
        $folioLayout = in_array($request->user()?->role, ['admin', 'manager'], true)
            ? 'admin-dashboard-layout'
            : 'receptionist-dashboard-layout';

        return view('receptionist.folio', compact(
            'selectedBooking',
            'charges',
            'totalCharges',
            'totalPayments',
            'balanceDue',
            'departmentTotals',
            'departmentShares',
            'facilityReservations',
            'folioLayout',
        ));
    }
}
