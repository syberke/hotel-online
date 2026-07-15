<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentGatewayController extends Controller
{
    protected function initMidtrans()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);
        $this->initMidtrans();

        $booking = DB::table('bookings')->where('id', $request->booking_id)->where('user_id', auth()->id())->first();
        if (!$booking || $booking->status !== 'pending') {
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
                'name' => 'Oasis Luxury Suite Reservation #' . $booking->id,
            ]],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function handleMidtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        $orderParts = explode('-', $request->order_id);
        $bookingId = $orderParts[1] ?? null;
        $transactionStatus = $request->transaction_status;
        $statusToUpdate = ($transactionStatus === 'capture' || $transactionStatus === 'settlement')
            ? 'confirmed'
            : (in_array($transactionStatus, ['deny', 'expire', 'cancel'], true) ? 'canceled' : null);

        if ($statusToUpdate && $bookingId) {
            DB::table('bookings')->where('id', $bookingId)->update([
                'status' => $statusToUpdate,
                'updated_at' => now(),
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    public function payRestaurantOrder(Request $request)
    {
        $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
        if (!$guest) {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500);
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
        if (!$order) {
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
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghubungkan gateway: ' . $e->getMessage()], 500);
        }
    }

    public function localPaymentSuccess(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);
        $booking = DB::table('bookings')->where('id', $request->booking_id)->where('user_id', auth()->id())->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan.'], 404);
        }

        if ($booking->status === 'pending') {
            DB::table('bookings')
                ->where('id', $request->booking_id)
                ->where('user_id', auth()->id())
                ->update(['status' => 'confirmed', 'updated_at' => now()]);
        }

        return response()->json(['success' => true, 'message' => 'Database lokal diperbarui.']);
    }
}
