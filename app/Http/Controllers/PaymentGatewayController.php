<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Booking; // Import model yang dibutuhkan
use App\Models\Payment; // Import model yang dibutuhkan

class PaymentGatewayController extends Controller
{
     protected function initMidtrans()
        {
            Config::$serverKey = config('services.midtrans.server_key');
            Config::$isProduction = config('services.midtrans.production');
            Config::$isSanitized = true;
            Config::$is3ds = true;
        }

        public function getSnapToken(Request $request)
        {
            $request->validate(['booking_id' => 'required|integer']);
            $this->initMidtrans();

            $booking = DB::table('bookings')->where('id', $request->booking_id)->where('user_id', auth()->id())->first();
            if (!$booking || $booking->status !== 'pending') { return response()->json(['success' => false, 'message' => 'Invoice tidak valid.'], 400); }

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
                'item_details' => [
                    [
                        'id' => 'ROOM-' . $booking->room_id,
                        'price' => $grandTotalInt,
                        'quantity' => 1,
                        'name' => 'Oasis Luxury Suite Reservation #' . $booking->id
                    ]
                ]
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                return response()->json(['success' => true, 'token' => $snapToken]);
            } catch (\Throwable $e) {
                report($e);
                return response()->json(['success' => false, 'message' => 'Gateway pembayaran sedang tidak tersedia.'], 502);
            }
        }

        public function handleMidtransCallback(Request $request)
        {
            $serverKey = config('services.midtrans.server_key');
            abort_if(blank($serverKey), 503, 'Midtrans belum dikonfigurasi.');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            if (!hash_equals($hashed, (string) $request->signature_key)) { return response()->json(['message' => 'Invalid Signature'], 403); }

            $orderParts = explode('-', (string) $request->order_id);
            $orderType = $orderParts[0] ?? null;
            $recordId = filter_var($orderParts[1] ?? null, FILTER_VALIDATE_INT);
            $transactionStatus = $request->transaction_status;
            $statusToUpdate = ($transactionStatus == 'capture' || $transactionStatus == 'settlement') ? 'confirmed' : (in_array($transactionStatus, ['deny', 'expire', 'cancel']) ? 'cancelled' : null);

            if ($statusToUpdate && $recordId && $orderType === 'OA') {
                DB::table('bookings')->where('id', $recordId)->update(['status' => $statusToUpdate, 'updated_at' => now()]);
            }

            if ($recordId && $orderType === 'RESTO') {
                $paid = in_array($transactionStatus, ['capture', 'settlement'], true);
                DB::transaction(function () use ($recordId, $paid) {
                    DB::table('payments')->where('restaurant_order_id', $recordId)->update([
                        'payment_status' => $paid ? 'paid' : 'failed',
                        'updated_at' => now(),
                    ]);
                    DB::table('restaurant_orders')->where('id', $recordId)->update([
                        'status' => $paid ? 'paid' : 'cancelled',
                        'updated_at' => now(),
                    ]);
                });
            }
            return response()->json(['status' => 'success']);
        }

        public function payRestaurantOrder(Request $request)
        {
            $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
            if (!$guest) return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);

            $cartItems = $request->input('cart_data', []);
            if (empty($cartItems)) return response()->json(['success' => false, 'message' => 'Keranjang kosong.'], 400);

            $subtotal = 0;
            foreach ($cartItems as $item) { $subtotal += ($item['price'] * $item['quantity']); }
            
            $serviceCharge = (int) round($subtotal * 0.10);
            $tax = (int) round(($subtotal + $serviceCharge) * 0.11);
            $grandTotal = (int) ($subtotal + $serviceCharge + $tax);

            try {
                $orderId = DB::transaction(function () use ($grandTotal, $cartItems, $guest) {
                    $id = DB::table('restaurant_orders')->insertGetId(['guest_id' => $guest->id, 'total_price' => $grandTotal, 'status' => 'ordered', 'created_at' => now(), 'updated_at' => now()]);
                    foreach ($cartItems as $item) {
                        DB::table('restaurant_order_details')->insert(['restaurant_order_id' => $id, 'restaurant_menu_id' => $item['id'], 'quantity' => (int) $item['quantity'], 'price' => $item['price'], 'created_at' => now(), 'updated_at' => now()]);
                    }
                    DB::table('payments')->insert(['restaurant_order_id' => $id, 'amount' => $grandTotal, 'payment_method' => 'transfer', 'payment_status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);
                    return $id;
                });

                $this->initMidtrans();
                $params = [
                    'transaction_details' => ['order_id' => 'RESTO-' . $orderId . '-' . time(), 'gross_amount' => $grandTotal],
                    'customer_details' => ['first_name' => auth()->user()->name, 'email' => auth()->user()->email]
                ];

                $snapToken = Snap::getSnapToken($params);
                return response()->json(['success' => true, 'token' => $snapToken, 'order_id' => $orderId]);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()], 500); }
        }

        public function settleRestaurantOrder(Request $request)
        {
            $request->validate(['order_id' => 'required|integer']);
            $guestId = DB::table('guests')->where('email', auth()->user()->email)->value('id');
            $ownsOrder = DB::table('restaurant_orders')
                ->where('id', $request->integer('order_id'))
                ->where('guest_id', $guestId)
                ->exists();
            abort_unless($ownsOrder, 404);

            // The browser cannot finalize a payment. Midtrans' signed callback does that.
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sedang diverifikasi oleh gateway.',
            ], 202);
        }

        public function cancelRestaurantOrder($id)
        {
            $guestId = DB::table('guests')->where('email', auth()->user()->email)->value('id');
            $ownsPendingOrder = DB::table('restaurant_orders')
                ->where('id', $id)
                ->where('guest_id', $guestId)
                ->where('status', 'ordered')
                ->exists();
            abort_unless($ownsPendingOrder, 404);

            DB::transaction(function () use ($id) {
                DB::table('payments')->where('restaurant_order_id', $id)->where('payment_status', 'pending')->delete();
                DB::table('restaurant_order_details')->where('restaurant_order_id', $id)->delete();
                DB::table('restaurant_orders')->where('id', $id)->delete();
            });
            return redirect()->back()->with('success', 'Pesanan kuliner berhasil dibatalkan.');
        }
public function reTokenPendingOrder($id)
        {
            $guestId = DB::table('guests')->where('email', auth()->user()->email)->value('id');
            $order = DB::table('restaurant_orders')
                ->where('id', $id)
                ->where('guest_id', $guestId)
                ->where('status', 'ordered')
                ->first();
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
                ]
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

            if (!$booking) { return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan.'], 404); }
            return response()->json([
                'success' => true,
                'message' => 'Pembayaran sedang diverifikasi oleh gateway.',
            ], 202);
        }
}
