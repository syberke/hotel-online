<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentGatewayController extends Controller
{
    protected function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);
        $this->initMidtrans();

        $booking = DB::table('bookings')
            ->where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $booking || $booking->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Invoice tidak valid.'], 400);
        }

        $grandTotalInt = (int) round($booking->total_price);
        $params = [
            'transaction_details' => [
                'order_id' => 'OA-' . $booking->id . '-' . time(),
                'gross_amount' => $grandTotalInt,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => [[
                'id' => 'ROOM-' . $booking->room_id,
                'price' => $grandTotalInt,
                'quantity' => 1,
                'name' => 'Oasis Room Reservation #' . $booking->id,
            ]],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Gateway pembayaran belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function handleMidtransCallback(Request $request)
    {
        $serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if (! hash_equals($hashed, (string) $request->signature_key)) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $orderParts = explode('-', (string) $request->order_id);
        $bookingId = $orderParts[1] ?? null;
        $transactionStatus = $request->transaction_status;
        $statusToUpdate = ($transactionStatus === 'capture' || $transactionStatus === 'settlement')
            ? 'confirmed'
            : (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true) ? 'canceled' : null);

        if ($statusToUpdate && $bookingId) {
            DB::transaction(function () use ($bookingId, $statusToUpdate, $transactionStatus): void {
                DB::table('bookings')->where('id', $bookingId)->update([
                    'status' => $statusToUpdate,
                    'updated_at' => now(),
                ]);

                DB::table('payments')->where('booking_id', $bookingId)->update([
                    'payment_status' => $statusToUpdate === 'confirmed' ? 'paid' : 'failed',
                    'payment_method' => 'e_wallet',
                    'note' => 'Midtrans status: ' . $transactionStatus,
                    'updated_at' => now(),
                ]);
            });
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Return a guest-owned room receipt for confirmed, checked-in, and checked-out stays.
     */
    public function getRoomInvoiceDetails(int $id)
    {
        $booking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('payments', 'payments.booking_id', '=', 'bookings.id')
            ->where('bookings.id', $id)
            ->where('bookings.user_id', auth()->id())
            ->select(
                'bookings.id',
                'bookings.status',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.total_price',
                'bookings.updated_at as booking_updated_at',
                'rooms.room_number',
                'room_types.name as room_type_name',
                'payments.payment_status',
                'payments.updated_at as payment_updated_at'
            )
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Receipt tidak ditemukan atau bukan milik akun ini.',
            ], 404);
        }

        if (! in_array($booking->status, ['confirmed', 'checked_in', 'checked_out'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Receipt belum tersedia untuk status reservasi ini.',
            ], 422);
        }

        $checkIn = new \DateTimeImmutable($booking->check_in);
        $checkOut = new \DateTimeImmutable($booking->check_out);
        $nights = max(1, (int) $checkIn->diff($checkOut)->days);
        $total = (int) round($booking->total_price);
        $nightlyRate = (int) floor($total / $nights);
        $items = [];

        if ($nights === 1) {
            $items[] = [
                'name' => $booking->room_type_name . ' · Room ' . $booking->room_number,
                'qty' => 1,
                'price' => $total,
            ];
        } else {
            $items[] = [
                'name' => $booking->room_type_name . ' · Room ' . $booking->room_number,
                'qty' => $nights - 1,
                'price' => $nightlyRate,
            ];
            $items[] = [
                'name' => 'Final night adjustment',
                'qty' => 1,
                'price' => $total - (($nights - 1) * $nightlyRate),
            ];
        }

        $receiptDate = $booking->payment_updated_at
            ?? $booking->booking_updated_at
            ?? now();

        return response()->json([
            'success' => true,
            'details' => [
                'order_id' => $booking->id,
                'date' => date('d M Y, H:i', strtotime((string) $receiptDate)),
                'status' => $booking->status,
                'room_number' => $booking->room_number,
                'room_type' => $booking->room_type_name,
                'check_in' => date('d M Y', strtotime($booking->check_in)),
                'check_out' => date('d M Y', strtotime($booking->check_out)),
                'payment_status' => $booking->payment_status ?? 'paid',
                'total' => $total,
                'items' => $items,
            ],
        ]);
    }

    public function payRestaurantOrder(Request $request)
    {
        $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
        if (! $guest) {
            return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);
        }

        $cartItems = $request->input('cart_data', []);
        if (empty($cartItems)) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);
        }

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $serviceCharge = (int) round($subtotal * 0.10);
        $tax = (int) round(($subtotal + $serviceCharge) * 0.11);
        $grandTotal = (int) ($subtotal + $serviceCharge + $tax);

        try {
            $orderId = DB::transaction(function () use ($grandTotal, $cartItems, $guest) {
                $id = DB::table('restaurant_orders')->insertGetId([
                    'guest_id' => $guest->id,
                    'total_price' => $grandTotal,
                    'status' => 'ordered',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($cartItems as $item) {
                    DB::table('restaurant_order_details')->insert([
                        'restaurant_order_id' => $id,
                        'restaurant_menu_id' => $item['id'],
                        'quantity' => (int) $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('payments')->insert([
                    'restaurant_order_id' => $id,
                    'amount' => $grandTotal,
                    'payment_method' => 'transfer',
                    'payment_status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $id;
            });

            $this->initMidtrans();
            $params = [
                'transaction_details' => [
                    'order_id' => 'RESTO-' . $orderId . '-' . time(),
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            return response()->json(['success' => true, 'token' => $snapToken, 'order_id' => $orderId]);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Gateway pembayaran belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function settleRestaurantOrder(Request $request)
    {
        $request->validate(['order_id' => 'required|integer']);
        DB::transaction(function () use ($request) {
            DB::table('payments')->where('restaurant_order_id', $request->order_id)->update([
                'payment_status' => 'paid',
                'updated_at' => now(),
            ]);
            DB::table('restaurant_orders')->where('id', $request->order_id)->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Pembayaran terverifikasi.']);
    }

    public function cancelRestaurantOrder($id)
    {
        DB::transaction(function () use ($id) {
            DB::table('payments')->where('restaurant_order_id', $id)->where('payment_status', 'pending')->delete();
            DB::table('restaurant_order_details')->where('restaurant_order_id', $id)->delete();
            DB::table('restaurant_orders')->where('id', $id)->delete();
        });

        return redirect()->back()->with('success', 'Pesanan kuliner berhasil dibatalkan.');
    }

    public function reTokenPendingOrder($id)
    {
        $order = DB::table('restaurant_orders')->where('id', $id)->first();
        if (! $order) {
            return response()->json(['success' => false, 'message' => 'Manifes order tidak ditemukan.'], 404);
        }

        $this->initMidtrans();
        $params = [
            'transaction_details' => [
                'order_id' => 'RESTO-' . $order->id . '-' . time(),
                'gross_amount' => (int) round($order->total_price),
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Gateway pembayaran belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function localPaymentSuccess(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);

        $booking = DB::table('bookings')
            ->where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (! $booking) {
            return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan.'], 404);
        }

        DB::transaction(function () use ($booking): void {
            if ($booking->status === 'pending') {
                DB::table('bookings')->where('id', $booking->id)->update([
                    'status' => 'confirmed',
                    'updated_at' => now(),
                ]);
            }

            DB::table('payments')->where('booking_id', $booking->id)->update([
                'payment_status' => 'paid',
                'payment_method' => 'e_wallet',
                'note' => 'Payment completed through Midtrans Snap.',
                'updated_at' => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Pembayaran dan receipt berhasil diperbarui.']);
    }
}
