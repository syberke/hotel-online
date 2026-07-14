<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DynamicFrontOfficeCheckController extends FrontOfficeCheckController
{
    public function receptionistFolioView(Request $request)
    {
        $bookingId = $request->input('booking_id');

        if (!$bookingId) {
            $latestActive = DB::table('bookings')
                ->where('status', 'checked_in')
                ->orderBy('created_at', 'desc')
                ->first();
            $bookingId = $latestActive?->id;
        }

        $selectedBooking = null;
        $charges = [];
        $totalCharges = 0.0;
        $totalPayments = 0.0;
        $deptAmounts = ['Room' => 0.0, 'F&B' => 0.0, 'Spa' => 0.0, 'Laundry' => 0.0];
        $deptShares = ['Room' => 0.0, 'F&B' => 0.0, 'Spa' => 0.0, 'Laundry' => 0.0];
        $trendPoints = [0.0, 0.0, 0.0, 0.0];
        $trendDates = [];

        if ($bookingId) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
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
            $checkInDate = Carbon::parse($selectedBooking->check_in)->startOfDay();
            $checkOutDate = Carbon::parse($selectedBooking->check_out)->endOfDay();
            $nights = max(1, $checkInDate->diffInDays($checkOutDate->copy()->startOfDay()));
            $runningBalance = 0.0;

            for ($i = 0; $i < $nights; $i++) {
                $dayDate = $checkInDate->copy()->addDays($i);
                if ($i < 4) {
                    $trendDates[$i] = $dayDate->format('d M');
                }

                $amount = (float) $selectedBooking->room_price;
                $runningBalance += $amount;
                $totalCharges += $amount;
                $deptAmounts['Room'] += $amount;
                if ($i < 4) {
                    $trendPoints[$i] += $amount;
                }

                $charges[] = [
                    'sort_date' => $dayDate->timestamp,
                    'post_date' => $dayDate->format('d M Y'),
                    'date' => $dayDate->format('d M Y'),
                    'description' => "Room Charge ({$selectedBooking->room_type})",
                    'reference' => "Room {$selectedBooking->room_number}",
                    'department' => 'Room',
                    'debit' => $amount,
                    'credit' => 0,
                    'balance' => $runningBalance,
                ];
            }

            $facilityBookings = DB::table('facility_bookings')
                ->where('user_id', $selectedBooking->user_id)
                ->whereBetween('booking_date', [$checkInDate->toDateString(), $checkOutDate->toDateString()])
                ->whereIn('status', ['confirmed', 'completed'])
                ->where('total_price', '>', 0)
                ->orderBy('booking_date')
                ->get();

            foreach ($facilityBookings as $facilityBooking) {
                $amount = (float) $facilityBooking->total_price;
                $date = Carbon::parse($facilityBooking->booking_date);
                $runningBalance += $amount;
                $totalCharges += $amount;
                $deptAmounts['Spa'] += $amount;
                $this->addTrendAmount($trendPoints, $checkInDate, $date, $amount);

                $charges[] = [
                    'sort_date' => $date->timestamp,
                    'post_date' => $date->format('d M Y'),
                    'date' => $date->format('d M Y'),
                    'description' => $facilityBooking->facility_name,
                    'reference' => 'FAC-' . str_pad((string) $facilityBooking->id, 4, '0', STR_PAD_LEFT),
                    'department' => 'Spa',
                    'debit' => $amount,
                    'credit' => 0,
                    'balance' => $runningBalance,
                ];
            }

            $guestId = DB::table('guests')
                ->whereRaw('LOWER(email) = ?', [strtolower($selectedBooking->guest_email)])
                ->value('id');

            $restaurantOrders = collect();
            if ($guestId) {
                $restaurantOrders = DB::table('restaurant_orders')
                    ->where('guest_id', $guestId)
                    ->whereBetween('created_at', [$checkInDate, $checkOutDate])
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('created_at')
                    ->get();
            }

            foreach ($restaurantOrders as $order) {
                $amount = (float) $order->total_price;
                $date = Carbon::parse($order->created_at);
                $runningBalance += $amount;
                $totalCharges += $amount;
                $deptAmounts['F&B'] += $amount;
                $this->addTrendAmount($trendPoints, $checkInDate, $date, $amount);

                $charges[] = [
                    'sort_date' => $date->timestamp,
                    'post_date' => $date->format('d M Y'),
                    'date' => $date->format('d M Y'),
                    'description' => 'Restaurant / Room Service Order',
                    'reference' => 'RS-' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT),
                    'department' => 'F&B',
                    'debit' => $amount,
                    'credit' => 0,
                    'balance' => $runningBalance,
                ];
            }

            $bookingPayments = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->where('payment_status', 'paid')
                ->get();

            $restaurantOrderIds = $restaurantOrders->pluck('id');
            $restaurantPayments = $restaurantOrderIds->isEmpty()
                ? collect()
                : DB::table('payments')
                    ->whereIn('restaurant_order_id', $restaurantOrderIds)
                    ->where('payment_status', 'paid')
                    ->get();

            foreach ($bookingPayments->concat($restaurantPayments)->sortBy('created_at') as $payment) {
                $amount = (float) $payment->amount;
                $date = Carbon::parse($payment->created_at);
                $totalPayments += $amount;
                $runningBalance = max(0, $runningBalance - $amount);

                $charges[] = [
                    'sort_date' => $date->timestamp,
                    'post_date' => $date->format('d M Y'),
                    'date' => $date->format('d M Y'),
                    'description' => 'Payment Settlement (' . strtoupper(str_replace('_', ' ', $payment->payment_method)) . ')',
                    'reference' => 'PAY-' . str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT),
                    'department' => 'Cashier',
                    'debit' => 0,
                    'credit' => $amount,
                    'balance' => $runningBalance,
                ];
            }

            usort($charges, static fn (array $a, array $b) => $a['sort_date'] <=> $b['sort_date']);
            $runningBalance = 0.0;
            foreach ($charges as &$charge) {
                $runningBalance += (float) $charge['debit'];
                $runningBalance -= (float) $charge['credit'];
                $charge['balance'] = max(0, $runningBalance);
                unset($charge['sort_date']);
            }
            unset($charge);

            $totalDept = array_sum($deptAmounts);
            if ($totalDept > 0) {
                foreach ($deptAmounts as $department => $amount) {
                    $deptShares[$department] = round(($amount / $totalDept) * 100, 1);
                }
            }
        }

        $balanceDue = max(0, $totalCharges - $totalPayments);

        return view('receptionist.folio', compact(
            'selectedBooking',
            'charges',
            'totalCharges',
            'totalPayments',
            'balanceDue',
            'deptAmounts',
            'deptShares',
            'trendPoints',
            'trendDates'
        ));
    }

    private function addTrendAmount(array &$trendPoints, Carbon $checkInDate, Carbon $date, float $amount): void
    {
        $index = $checkInDate->copy()->startOfDay()->diffInDays($date->copy()->startOfDay(), false);
        if ($index >= 0 && $index < 4) {
            $trendPoints[$index] += $amount;
        }
    }
}
