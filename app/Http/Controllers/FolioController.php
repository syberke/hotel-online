<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FolioController extends Controller
{
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
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $bookingId)
                ->select(
                    'bookings.*',
                    'users.name as guest_name',
                    'users.email as guest_email',
                    'guests.phone as guest_phone',
                    'rooms.room_number',
                    'room_types.name as room_type',
                )
                ->first();
        }

        if ($selectedBooking) {
            $roomCharge = (float) $selectedBooking->total_price;
            $departmentTotals['Room'] = $roomCharge;
            $totalCharges += $roomCharge;
            $charges->push((object) [
                'date' => Carbon::parse($selectedBooking->check_in)->format('d M Y'),
                'description' => 'Room accommodation · ' . ($selectedBooking->room_type ?? 'Room'),
                'reference' => 'BOOKING-' . $selectedBooking->id,
                'department' => 'Room',
                'debit' => $roomCharge,
                'credit' => 0.0,
            ]);

            $restaurantOrders = DB::table('restaurant_orders')
                ->where('guest_id', $selectedBooking->guest_id)
                ->whereNotIn('status', ['cancelled', 'canceled'])
                ->whereBetween('created_at', [
                    Carbon::parse($selectedBooking->check_in)->startOfDay(),
                    Carbon::parse($selectedBooking->check_out)->endOfDay(),
                ])
                ->orderBy('created_at')
                ->get();

            foreach ($restaurantOrders as $order) {
                $amount = (float) $order->total_price;
                $departmentTotals['Restaurant'] += $amount;
                $totalCharges += $amount;
                $charges->push((object) [
                    'date' => Carbon::parse($order->created_at)->format('d M Y'),
                    'description' => 'Restaurant order #' . $order->id,
                    'reference' => 'REST-' . $order->id,
                    'department' => 'Restaurant',
                    'debit' => $amount,
                    'credit' => 0.0,
                ]);

                $restaurantPayments = DB::table('payments')
                    ->where('restaurant_order_id', $order->id)
                    ->where('payment_status', 'paid')
                    ->orderBy('created_at')
                    ->get();

                foreach ($restaurantPayments as $payment) {
                    $paid = (float) $payment->amount;
                    $totalPayments += $paid;
                    $charges->push((object) [
                        'date' => Carbon::parse($payment->created_at)->format('d M Y'),
                        'description' => 'Restaurant payment · ' . str_replace('_', ' ', $payment->payment_method),
                        'reference' => 'PAY-' . $payment->id,
                        'department' => 'Payment',
                        'debit' => 0.0,
                        'credit' => $paid,
                    ]);
                }
            }

            $bookingPayments = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->where('payment_status', 'paid')
                ->orderBy('created_at')
                ->get();

            foreach ($bookingPayments as $payment) {
                $paid = (float) $payment->amount;
                $totalPayments += $paid;
                $charges->push((object) [
                    'date' => Carbon::parse($payment->created_at)->format('d M Y'),
                    'description' => 'Booking payment · ' . str_replace('_', ' ', $payment->payment_method),
                    'reference' => 'PAY-' . $payment->id,
                    'department' => 'Payment',
                    'debit' => 0.0,
                    'credit' => $paid,
                ]);
            }

            $facilityReservations = DB::table('facility_bookings')
                ->where('user_id', $selectedBooking->user_id)
                ->whereNotIn('status', ['cancelled', 'canceled'])
                ->whereBetween('booking_date', [$selectedBooking->check_in, $selectedBooking->check_out])
                ->orderBy('booking_date')
                ->orderBy('booking_time')
                ->get();

            $balanceDue = max(0, $totalCharges - $totalPayments);
        }

        $departmentShares = $this->departmentShares($departmentTotals);
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

    private function departmentShares(array $departmentTotals): array
    {
        $total = array_sum($departmentTotals);

        return collect($departmentTotals)
            ->map(fn (float $amount) => $total > 0 ? round(($amount / $total) * 100, 1) : 0.0)
            ->all();
    }
}
