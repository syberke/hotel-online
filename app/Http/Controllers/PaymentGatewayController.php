<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable;

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
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
        ]);

        $booking = DB::table('bookings')
            ->where('id', $validated['booking_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (! $booking || $booking->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Invoice tidak valid.'], 400);
        }

        $this->initMidtrans();
        $grandTotal = (int) round($booking->total_price);

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => 'OA-' . $booking->id . '-' . time(),
                    'gross_amount' => $grandTotal,
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'item_details' => [[
                    'id' => 'ROOM-' . $booking->room_id,
                    'price' => $grandTotal,
                    'quantity' => 1,
                    'name' => 'Oasis Room Reservation #' . $booking->id,
                ]],
            ]);

            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (Throwable $exception) {
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

        $parts = explode('-', (string) $request->order_id);
        $prefix = strtoupper((string) ($parts[0] ?? ''));
        $entityId = isset($parts[1]) && ctype_digit((string) $parts[1]) ? (int) $parts[1] : 0;
        $transactionStatus = strtolower((string) $request->transaction_status);
        $isPaid = in_array($transactionStatus, ['capture', 'settlement'], true);
        $isFailed = in_array($transactionStatus, ['deny', 'expire', 'cancel'], true);

        if (! $entityId || (! $isPaid && ! $isFailed)) {
            return response()->json(['status' => 'ignored']);
        }

        DB::transaction(function () use ($prefix, $entityId, $transactionStatus, $isPaid): void {
            if ($prefix === 'OA') {
                DB::table('bookings')->where('id', $entityId)->update([
                    'status' => $isPaid ? 'confirmed' : 'canceled',
                    'updated_at' => now(),
                ]);

                DB::table('payments')->where('booking_id', $entityId)->update([
                    'payment_status' => $isPaid ? 'paid' : 'failed',
                    'payment_method' => 'e_wallet',
                    'note' => 'Midtrans booking status: ' . $transactionStatus,
                    'updated_at' => now(),
                ]);

                return;
            }

            if (in_array($prefix, ['RESTO', 'ROOMSERVICE'], true)) {
                DB::table('restaurant_orders')->where('id', $entityId)->update([
                    'status' => $isPaid ? 'paid' : 'cancelled',
                    'updated_at' => now(),
                ]);

                DB::table('payments')->where('restaurant_order_id', $entityId)->update([
                    'payment_status' => $isPaid ? 'paid' : 'failed',
                    'payment_method' => 'e_wallet',
                    'note' => 'Midtrans restaurant status: ' . $transactionStatus,
                    'updated_at' => now(),
                ]);
            }
        });

        return response()->json(['status' => 'success']);
    }

    public function getRoomInvoiceDetails(int $id)
    {
        $booking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.booking_id', '=', 'bookings.id')
                    ->whereNull('payments.restaurant_order_id');
            })
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
                'payments.updated_at as payment_updated_at',
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
        $validated = $request->validate([
            'cart_data' => ['required', 'array', 'min:1'],
            'cart_data.*.id' => ['required', 'integer', 'exists:restaurant_menus,id'],
            'cart_data.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
            'booking_id' => ['nullable', 'integer', 'exists:bookings,id'],
            'delivery_note' => ['nullable', 'string', 'max:255'],
        ]);

        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) auth()->user()->email)])
            ->first();

        if (! $guest) {
            return response()->json(['success' => false, 'message' => 'Profil tidak ditemukan.'], 404);
        }

        $bookingId = $validated['booking_id'] ?? null;
        if ($bookingId) {
            $bookingExists = DB::table('bookings')
                ->where('id', $bookingId)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();

            if (! $bookingExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Reservasi tujuan tidak valid.',
                ], 422);
            }
        }

        $calculation = $this->calculateRestaurantCart($validated['cart_data']);
        if ($calculation === null) {
            return response()->json([
                'success' => false,
                'message' => 'Salah satu menu sudah tidak tersedia. Muat ulang halaman.',
            ], 422);
        }

        $orderId = null;

        try {
            $orderId = DB::transaction(function () use ($guest, $bookingId, $validated, $calculation): int {
                $id = DB::table('restaurant_orders')->insertGetId([
                    'guest_id' => $guest->id,
                    'total_price' => $calculation['grand_total'],
                    'status' => 'ordered',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                foreach ($calculation['items'] as $item) {
                    DB::table('restaurant_order_details')->insert([
                        'restaurant_order_id' => $id,
                        'restaurant_menu_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('payments')->insert([
                    'booking_id' => $bookingId,
                    'restaurant_order_id' => $id,
                    'amount' => $calculation['grand_total'],
                    'payment_method' => 'e_wallet',
                    'payment_status' => 'pending',
                    'note' => $validated['delivery_note'] ?? 'Restaurant online payment',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $id;
            });

            $this->initMidtrans();
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => 'RESTO-' . $orderId . '-' . time(),
                    'gross_amount' => $calculation['grand_total'],
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'item_details' => [[
                    'id' => 'RESTAURANT-' . $orderId,
                    'price' => $calculation['grand_total'],
                    'quantity' => 1,
                    'name' => 'Restaurant Order #' . $orderId,
                ]],
            ]);

            return response()->json([
                'success' => true,
                'token' => $snapToken,
                'order_id' => $orderId,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            if ($orderId) {
                DB::transaction(function () use ($orderId): void {
                    DB::table('payments')
                        ->where('restaurant_order_id', $orderId)
                        ->where('payment_status', 'pending')
                        ->delete();
                    DB::table('restaurant_order_details')->where('restaurant_order_id', $orderId)->delete();
                    DB::table('restaurant_orders')->where('id', $orderId)->delete();
                });
            }

            return response()->json([
                'success' => false,
                'message' => 'Gateway pembayaran belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function settleRestaurantOrder(Request $request)
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:restaurant_orders,id'],
        ]);

        $order = $this->guestOwnedRestaurantOrder($validated['order_id']);
        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau bukan milik akun ini.',
            ], 404);
        }

        DB::transaction(function () use ($order): void {
            DB::table('payments')->where('restaurant_order_id', $order->id)->update([
                'payment_method' => 'e_wallet',
                'payment_status' => 'paid',
                'note' => 'Payment completed through Midtrans Snap.',
                'updated_at' => now(),
            ]);
            DB::table('restaurant_orders')->where('id', $order->id)->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Pembayaran terverifikasi.']);
    }

    public function cancelRestaurantOrder(int $id)
    {
        $order = $this->guestOwnedRestaurantOrder($id);
        if (! $order) {
            return back()->with('error', 'Pesanan tidak ditemukan atau bukan milik akun ini.');
        }

        $hasPaidPayment = DB::table('payments')
            ->where('restaurant_order_id', $order->id)
            ->where('payment_status', 'paid')
            ->exists();

        if ($hasPaidPayment) {
            return back()->with('error', 'Pesanan yang sudah dibayar tidak dapat dibatalkan dari portal guest.');
        }

        DB::transaction(function () use ($order): void {
            DB::table('payments')->where('restaurant_order_id', $order->id)->delete();
            DB::table('restaurant_order_details')->where('restaurant_order_id', $order->id)->delete();
            DB::table('restaurant_orders')->where('id', $order->id)->delete();
        });

        return back()->with('success', 'Pesanan kuliner berhasil dibatalkan.');
    }

    public function reTokenPendingOrder(int $id)
    {
        $order = $this->guestOwnedRestaurantOrder($id);
        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan atau bukan milik akun ini.',
            ], 404);
        }

        $pendingPayment = DB::table('payments')
            ->where('restaurant_order_id', $order->id)
            ->where('payment_status', 'pending')
            ->first();

        if (! $pendingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak mempunyai pembayaran pending.',
            ], 422);
        }

        $this->initMidtrans();

        try {
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => 'RESTO-' . $order->id . '-' . time(),
                    'gross_amount' => (int) round($order->total_price),
                ],
                'customer_details' => [
                    'first_name' => auth()->user()->name,
                    'email' => auth()->user()->email,
                ],
                'item_details' => [[
                    'id' => 'RESTAURANT-' . $order->id,
                    'price' => (int) round($order->total_price),
                    'quantity' => 1,
                    'name' => 'Restaurant Order #' . $order->id,
                ]],
            ]);

            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'success' => false,
                'message' => 'Gateway pembayaran belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function localPaymentSuccess(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
        ]);

        $booking = DB::table('bookings')
            ->where('id', $validated['booking_id'])
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

            DB::table('payments')
                ->where('booking_id', $booking->id)
                ->whereNull('restaurant_order_id')
                ->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'e_wallet',
                    'note' => 'Payment completed through Midtrans Snap.',
                    'updated_at' => now(),
                ]);
        });

        return response()->json(['success' => true, 'message' => 'Pembayaran dan receipt berhasil diperbarui.']);
    }

    private function guestOwnedRestaurantOrder(int $orderId): ?object
    {
        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) auth()->user()->email)])
            ->first();

        if (! $guest) {
            return null;
        }

        return DB::table('restaurant_orders')
            ->where('id', $orderId)
            ->where('guest_id', $guest->id)
            ->first();
    }

    private function calculateRestaurantCart(array $cartItems): ?array
    {
        $menuIds = collect($cartItems)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $menus = DB::table('restaurant_menus')
            ->whereIn('id', $menuIds)
            ->where('is_available', true)
            ->select('id', 'name', 'price')
            ->get()
            ->keyBy('id');

        if ($menus->count() !== $menuIds->count()) {
            return null;
        }

        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $cartItem) {
            $menu = $menus->get((int) $cartItem['id']);
            $quantity = max(1, min(20, (int) $cartItem['quantity']));
            $price = (int) round((float) $menu->price);

            $items[] = [
                'id' => (int) $menu->id,
                'name' => $menu->name,
                'quantity' => $quantity,
                'price' => $price,
            ];
            $subtotal += $price * $quantity;
        }

        $serviceCharge = (int) round($subtotal * 0.10);
        $tax = (int) round(($subtotal + $serviceCharge) * 0.11);

        return [
            'items' => $items,
            'grand_total' => $subtotal + $serviceCharge + $tax,
        ];
    }
}
