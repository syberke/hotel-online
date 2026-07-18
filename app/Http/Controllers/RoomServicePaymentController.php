<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;
use Throwable;

class RoomServicePaymentController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'cart_data' => ['required', 'array', 'min:1'],
            'cart_data.*.id' => ['required', 'integer', 'exists:restaurant_menus,id'],
            'cart_data.*.quantity' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $booking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.id', $validated['booking_id'])
            ->where('bookings.user_id', $request->user()->id)
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select('bookings.id', 'bookings.status', 'rooms.room_number')
            ->first();

        if (! $booking) {
            return response()->json([
                'success' => false,
                'message' => 'Room Service hanya tersedia untuk reservasi terkonfirmasi atau tamu yang sedang check-in.',
            ], 422);
        }

        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) $request->user()->email)])
            ->first();

        if (! $guest) {
            return response()->json([
                'success' => false,
                'message' => 'Profil guest belum tersedia.',
            ], 404);
        }

        $calculation = $this->calculateCart($validated['cart_data']);
        if ($calculation === null) {
            return response()->json([
                'success' => false,
                'message' => 'Salah satu menu sudah tidak tersedia. Muat ulang halaman dan pilih ulang pesanan.',
            ], 422);
        }

        $orderId = null;

        try {
            $orderId = DB::transaction(function () use ($guest, $booking, $calculation): int {
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
                    'booking_id' => $booking->id,
                    'restaurant_order_id' => $id,
                    'amount' => $calculation['grand_total'],
                    'payment_method' => 'e_wallet',
                    'payment_status' => 'pending',
                    'note' => 'Room Service online payment for room ' . $booking->room_number,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                return $id;
            });

            $this->initMidtrans();
            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => 'ROOMSERVICE-' . $orderId . '-' . time(),
                    'gross_amount' => $calculation['grand_total'],
                ],
                'customer_details' => [
                    'first_name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
                'item_details' => [[
                    'id' => 'ROOMSERVICE-' . $orderId,
                    'price' => $calculation['grand_total'],
                    'quantity' => 1,
                    'name' => 'Room Service · Room ' . $booking->room_number,
                ]],
            ]);

            return response()->json([
                'success' => true,
                'token' => $snapToken,
                'order_id' => $orderId,
                'total' => $calculation['grand_total'],
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
                'message' => 'Gateway pembayaran Room Service belum dapat dihubungi. Silakan coba lagi.',
            ], 502);
        }
    }

    public function settle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_id' => ['required', 'integer', 'exists:restaurant_orders,id'],
        ]);

        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) $request->user()->email)])
            ->first();

        $order = $guest
            ? DB::table('restaurant_orders')
                ->where('id', $validated['order_id'])
                ->where('guest_id', $guest->id)
                ->first()
            : null;

        if (! $order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan Room Service tidak ditemukan atau bukan milik akun ini.',
            ], 404);
        }

        DB::transaction(function () use ($order): void {
            DB::table('payments')
                ->where('restaurant_order_id', $order->id)
                ->update([
                    'payment_method' => 'e_wallet',
                    'payment_status' => 'paid',
                    'note' => 'Room Service payment completed through Midtrans Snap.',
                    'updated_at' => now(),
                ]);

            DB::table('restaurant_orders')->where('id', $order->id)->update([
                'status' => 'paid',
                'updated_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran Room Service berhasil diverifikasi.',
        ]);
    }

    private function calculateCart(array $cartItems): ?array
    {
        $menuIds = collect($cartItems)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $menus = DB::table('restaurant_menus')
            ->whereIn('id', $menuIds)
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
            $quantity = (int) $cartItem['quantity'];
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
            'subtotal' => $subtotal,
            'service_charge' => $serviceCharge,
            'tax' => $tax,
            'grand_total' => $subtotal + $serviceCharge + $tax,
        ];
    }

    private function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key', env('MIDTRANS_SERVER_KEY'));
        Config::$isProduction = (bool) config('services.midtrans.is_production', env('MIDTRANS_IS_PRODUCTION', false));
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
