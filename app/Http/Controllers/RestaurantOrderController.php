<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
}