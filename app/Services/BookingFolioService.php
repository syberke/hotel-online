<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingFolioService
{
    public function build(int $bookingId): ?array
    {
        $booking = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.id', $bookingId)
            ->select(
                'bookings.*',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                DB::raw("CASE WHEN bookings.booking_source = 'walk_in' THEN NULL ELSE COALESCE(users.email, guests.email) END as guest_email"),
                'guests.phone as guest_phone',
                'rooms.room_number',
                'room_types.name as room_type',
                'room_types.price as room_price',
            )
            ->first();

        if (! $booking) {
            return null;
        }

        $charges = collect();
        $roomCharge = (float) $booking->total_price;
        $departmentTotals = ['Room' => $roomCharge, 'Restaurant' => 0.0];

        $charges->push((object) [
            'date' => Carbon::parse($booking->check_in)->format('d M Y'),
            'description' => 'Room accommodation · ' . ($booking->room_type ?? 'Room'),
            'reference' => 'BOOKING-' . $booking->id,
            'department' => 'Room',
            'debit' => $roomCharge,
            'credit' => 0.0,
        ]);

        $restaurantOrders = DB::table('payments')
            ->join('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->where('payments.booking_id', $booking->id)
            ->whereNotNull('payments.restaurant_order_id')
            ->whereNotIn('restaurant_orders.status', ['cancelled', 'canceled'])
            ->select(
                'restaurant_orders.id',
                'restaurant_orders.total_price',
                'restaurant_orders.created_at',
            )
            ->distinct()
            ->orderBy('restaurant_orders.created_at')
            ->get();

        foreach ($restaurantOrders as $order) {
            $amount = (float) $order->total_price;
            $departmentTotals['Restaurant'] += $amount;

            $itemSummary = DB::table('restaurant_order_details')
                ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                ->where('restaurant_order_details.restaurant_order_id', $order->id)
                ->orderBy('restaurant_order_details.id')
                ->get([
                    'restaurant_menus.name',
                    'restaurant_order_details.quantity',
                ])
                ->map(fn ($item) => $item->quantity . '× ' . $item->name)
                ->take(3)
                ->implode(', ');

            $charges->push((object) [
                'date' => Carbon::parse($order->created_at)->format('d M Y'),
                'description' => 'Room Service #' . str_pad((string) $order->id, 4, '0', STR_PAD_LEFT)
                    . ($itemSummary !== '' ? ' · ' . $itemSummary : ''),
                'reference' => 'RS-' . $order->id,
                'department' => 'Restaurant',
                'debit' => $amount,
                'credit' => 0.0,
            ]);
        }

        $paidPayments = DB::table('payments')
            ->where('booking_id', $booking->id)
            ->where('payment_status', 'paid')
            ->orderBy('created_at')
            ->get();

        foreach ($paidPayments as $payment) {
            $isRestaurant = $payment->restaurant_order_id !== null;
            $charges->push((object) [
                'date' => Carbon::parse($payment->created_at)->format('d M Y'),
                'description' => $isRestaurant
                    ? 'Room Service settlement · ' . str_replace('_', ' ', $payment->payment_method)
                    : 'Booking payment · ' . str_replace('_', ' ', $payment->payment_method),
                'reference' => 'PAY-' . $payment->id,
                'department' => 'Payment',
                'debit' => 0.0,
                'credit' => (float) $payment->amount,
            ]);
        }

        $totalCharges = $roomCharge + $departmentTotals['Restaurant'];
        $totalPayments = (float) ($paidPayments->sum('amount') ?: 0);
        $balanceDue = max(0, $totalCharges - $totalPayments);

        $roomPaid = (float) (DB::table('payments')
            ->where('booking_id', $booking->id)
            ->whereNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0);

        $pendingAncillary = DB::table('payments')
            ->join('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->where('payments.booking_id', $booking->id)
            ->whereNotNull('payments.restaurant_order_id')
            ->where('payments.payment_status', 'pending')
            ->whereNotIn('restaurant_orders.status', ['cancelled', 'canceled'])
            ->select(
                'payments.id',
                'payments.restaurant_order_id',
                'payments.amount',
                'payments.note',
                'payments.created_at',
            )
            ->orderBy('payments.created_at')
            ->get();

        $facilityReservations = $booking->user_id
            ? DB::table('facility_bookings')
                ->where('user_id', $booking->user_id)
                ->whereNotIn('status', ['cancelled', 'canceled'])
                ->whereBetween('booking_date', [$booking->check_in, $booking->check_out])
                ->orderBy('booking_date')
                ->orderBy('booking_time')
                ->get()
            : collect();

        return [
            'booking' => $booking,
            'charges' => $charges,
            'total_charges' => $totalCharges,
            'total_payments' => $totalPayments,
            'balance_due' => $balanceDue,
            'room_charge' => $roomCharge,
            'room_paid' => $roomPaid,
            'room_balance' => max(0, $roomCharge - $roomPaid),
            'pending_ancillary' => $pendingAncillary,
            'payment_history' => DB::table('payments')
                ->where('booking_id', $booking->id)
                ->orderByDesc('created_at')
                ->get(),
            'department_totals' => $departmentTotals,
            'facility_reservations' => $facilityReservations,
        ];
    }

    public function settle(int $bookingId, float $amount, string $method): void
    {
        $ledger = $this->build($bookingId);

        if (! $ledger) {
            throw ValidationException::withMessages([
                'booking_id_hidden' => 'Reservasi tidak ditemukan.',
            ]);
        }

        $amount = round($amount, 2);
        $balanceDue = round((float) $ledger['balance_due'], 2);

        if ($balanceDue <= 0) {
            throw ValidationException::withMessages([
                'payment_amount' => 'Folio reservasi ini sudah lunas.',
            ]);
        }

        if ($amount > $balanceDue) {
            throw ValidationException::withMessages([
                'payment_amount' => 'Nominal pembayaran melebihi sisa tagihan Rp '
                    . number_format($balanceDue, 0, ',', '.') . '.',
            ]);
        }

        $roomAllocation = min($amount, round((float) $ledger['room_balance'], 2));
        $remaining = round($amount - $roomAllocation, 2);
        $ancillaryToSettle = collect();

        foreach ($ledger['pending_ancillary'] as $charge) {
            if ($remaining <= 0) {
                break;
            }

            $chargeAmount = round((float) $charge->amount, 2);
            if ($remaining < $chargeAmount) {
                throw ValidationException::withMessages([
                    'payment_amount' => 'Pembayaran parsial tidak dapat memotong satu tagihan Room Service. '
                        . 'Gunakan minimal Rp ' . number_format($roomAllocation + $chargeAmount, 0, ',', '.')
                        . ' atau lunasi seluruh saldo.',
                ]);
            }

            $ancillaryToSettle->push($charge);
            $remaining = round($remaining - $chargeAmount, 2);
        }

        if ($remaining > 0) {
            throw ValidationException::withMessages([
                'payment_amount' => 'Sebagian pembayaran tidak dapat dialokasikan ke folio.',
            ]);
        }

        DB::transaction(function () use ($bookingId, $roomAllocation, $ancillaryToSettle, $method): void {
            if ($roomAllocation > 0) {
                DB::table('payments')->insert([
                    'booking_id' => $bookingId,
                    'amount' => $roomAllocation,
                    'payment_method' => $method,
                    'payment_status' => 'paid',
                    'note' => 'Room accommodation payment posted by Front Desk.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($ancillaryToSettle as $charge) {
                DB::table('payments')->where('id', $charge->id)->update([
                    'payment_method' => $method,
                    'payment_status' => 'paid',
                    'note' => trim(($charge->note ? $charge->note . ' · ' : '') . 'Settled by Front Desk.'),
                    'updated_at' => now(),
                ]);

                DB::table('restaurant_orders')
                    ->where('id', $charge->restaurant_order_id)
                    ->whereNotIn('status', ['cancelled', 'canceled'])
                    ->update([
                        'status' => 'paid',
                        'updated_at' => now(),
                    ]);
            }

            $roomBalance = (float) DB::table('bookings')->where('id', $bookingId)->value('total_price')
                - (float) DB::table('payments')
                    ->where('booking_id', $bookingId)
                    ->whereNull('restaurant_order_id')
                    ->where('payment_status', 'paid')
                    ->sum('amount');

            if ($roomBalance <= 0) {
                DB::table('bookings')
                    ->where('id', $bookingId)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'confirmed',
                        'updated_at' => now(),
                    ]);
            }
        });
    }

    public function departmentShares(array $departmentTotals): array
    {
        $total = array_sum($departmentTotals);

        if ($total <= 0) {
            return array_fill_keys(array_keys($departmentTotals), 0.0);
        }

        return collect($departmentTotals)
            ->map(fn ($amount) => round(((float) $amount / $total) * 100, 1))
            ->all();
    }
}
