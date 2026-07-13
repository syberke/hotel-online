<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class RestaurantOrderController extends Controller
{
    /**
     * Eksekusi tombol Add To Order dari Halaman Restaurant
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $menu = DB::table('restaurant_menus')->where('id', $request->menu_id)->first();
        if (!$menu) {
            return back()->with('error', 'Menu kuliner tidak ditemukan.');
        }

        $totalPrice = $menu->price * $request->quantity;

        // Cari atau buat keselarasan data Guest ID berdasarkan User yang sedang aktif
        $guest = DB::table('guests')->where('email', Auth::user()->email)->first();
        
        if (!$guest) {
            // Sinkronisasi otomatis jika data guest belum terpetakan di tabel PostgreSQL
            $guestId = DB::table('guests')->insertGetId([
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'password' => Auth::user()->password,
                'phone' => Auth::user()->phone ?? '-',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $guestId = $guest->id;
        }

        // Jalankan Transaction Database untuk mengamankan data pemesanan
        DB::transaction(function () use ($guestId, $menu, $request, $totalPrice) {
            $orderId = DB::table('restaurant_orders')->insertGetId([
                'guest_id' => $guestId,
                'total_price' => $totalPrice,
                'status' => 'ordered',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::table('restaurant_order_details')->insert([
                'restaurant_order_id' => $orderId,
                'restaurant_menu_id' => $menu->id,
                'quantity' => $request->quantity,
                'price' => $menu->price,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Catat ke lembar tagihan hotel pending (Payments)
            DB::table('payments')->insert([
                'restaurant_order_id' => $orderId,
                'amount' => $totalPrice,
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });

        return redirect()->route('guest.dashboard')->with('success', 'Pesanan F&B berhasil diteruskan ke dapur.');
    }

        /**
         * Mengambil Informasi Tagihan Detail Kamar Melalui Format Kueri AJAX JSON Modal
         */
        public function getRoomInvoiceDetails($id)
        {
            $booking = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $id)
                ->where('bookings.user_id', auth()->id())
                ->select('bookings.*', 'rooms.room_number', 'room_types.name as room_name')
                ->first();

            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Manifes tidak ditemukan.'], 404);
            }

            $days = max(1, (strtotime($booking->check_out) - strtotime($booking->check_in)) / (60 * 60 * 24));
            $pricePerNight = $booking->total_price / $days;

            $items = [
                [
                    'name'  => 'Suite Room ' . $booking->room_number . ' (' . $booking->room_name . ')',
                    'qty'   => (int) $days,
                    'price' => (int) round($pricePerNight)
                ]
            ];

            return response()->json([
                'success' => true,
                'details' => [
                    'order_id' => $booking->id,
                    'date'     => date('d M Y, H:i', strtotime($booking->updated_at)),
                    'status'   => $booking->status, 
                    'total'    => (int) $booking->total_price,
                    'items'    => $items
                ]
            ]);
        }

        /**
         * Mengambil JSON Detail Invoice Restoran
         */
        public function getRestaurantOrderDetails($id)
        {
            $order = DB::table('restaurant_orders')
                ->leftJoin('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
                ->where('restaurant_orders.id', $id)
                ->select('restaurant_orders.*', DB::raw("COALESCE(payments.payment_status, 'pending') as real_payment_status"))
                ->first();

            if (!$order) return response()->json(['success' => false], 404);

            $items = DB::table('restaurant_order_details')
                ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                ->where('restaurant_order_details.restaurant_order_id', $id)
                ->select('restaurant_menus.name', 'restaurant_order_details.quantity as qty', 'restaurant_order_details.price')
                ->get();

            return response()->json([
                'success' => true,
                'details' => [
                    'order_id' => $order->id,
                    'date'     => date('d M Y, H:i', strtotime($order->created_at)),
                    'status'   => $order->real_payment_status,
                    'total'    => $order->total_price,
                    'items'    => $items
                ]
            ]);
        }

        

       
        public function billingMatrix() { return view('guest.billingmatrix'); }

      
    /**
     * MENANGKAP ROUTE DETAILS UNTUK INVOICE / STRUK NOTA MANIFEST
     * Route: /restaurant-order/{id}/details
     */
    public function details($id): JsonResponse
    {
        try {
            // 1. Ambil data induk pesanan restoran
            $order = DB::table('restaurant_orders')->where('id', $id)->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Manifes pesanan tidak ditemukan.'
                ], 404);
            }

            // 2. Cek status pembayaran riil dari tabel payments pendamping
            $payment = DB::table('payments')->where('restaurant_order_id', $id)->first();
            $paymentStatus = $payment ? $payment->payment_status : 'pending';

            // 3. Ambil rincian menu makanan (di-join ke tabel restaurant_menus)
            $items = DB::table('restaurant_order_details')
                ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                ->where('restaurant_order_details.restaurant_order_id', $id)
                ->select([
                    'restaurant_menus.name as name',            // Sesuai 'line.name' di Alpine.js
                    'restaurant_order_details.quantity as qty',  // Sesuai 'line.qty' di Alpine.js
                    'restaurant_order_details.price as price'   // Sesuai 'line.price' di Alpine.js
                ])
                ->get();

            // 4. Return format JSON murni agar terbaca oleh JavaScript modal popup
            return response()->json([
                'success' => true,
                'details' => [
                    'order_id' => $order->id,
                    'date'     => date('d M Y, H:i', strtotime($order->created_at)),
                    'status'   => $paymentStatus, 
                    'total'    => (float) $order->total_price,
                    'items'    => $items
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail pesanan: ' . $e->getMessage()
            ], 500);
        }
    }
}