<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\FacilityBooking; // Import model yang dibutuhkan

class GuestServiceController extends Controller
{
   public function roomService(Request $request)
        {
            $userId = auth()->id();

            $allActiveBookings = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('bookings.user_id', $userId)
                ->whereIn('bookings.status', ['confirmed', 'pending', 'checked_in'])
                ->select('bookings.id', 'rooms.room_number')
                ->get();

            $targetBookingId = $request->input('booking_id', optional($allActiveBookings->first())->id);

            $currentBooking = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $targetBookingId)
                ->where('bookings.user_id', $userId)
                ->select('bookings.id as booking_id', 'rooms.room_number', 'room_types.name as room_name')
                ->first();

            $menus = DB::table('restaurant_menus')->select('id', 'name', 'description', 'price', 'foto_url')->get();
            $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
            $orderHistory = $guest 
                ? DB::table('restaurant_orders')->where('guest_id', $guest->id)->orderBy('created_at', 'desc')->take(5)->get()
                : collect();

            return view('guest.roomservice', compact('menus', 'orderHistory', 'currentBooking', 'allActiveBookings'));
        }

    
        public function storeRoomServiceOrder(Request $request)
        {
            $request->validate(['cart_data' => 'required', 'booking_id' => 'required']);
            $cartItems = json_decode($request->input('cart_data'));
            if (empty($cartItems)) { return redirect()->back()->with('error', 'Keranjang belanja Anda kosong.'); }

            $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
            if (!$guest) { return redirect()->back()->with('error', 'Profil manifest tamu Anda tidak terdaftar.'); }

            $subtotal = 0;
            foreach ($cartItems as $item) { $subtotal += ($item->price * $item->quantity); }

            $serviceCharge = (int) round($subtotal * 0.10);
            $tax = (int) round(($subtotal + $serviceCharge) * 0.11);
            $grandTotal = $subtotal + $serviceCharge + $tax;

            DB::transaction(function () use ($grandTotal, $cartItems, $guest) {
                $orderId = DB::table('restaurant_orders')->insertGetId([
                    'guest_id'    => $guest->id, 
                    'total_price' => $grandTotal,
                    'status'      => 'ordered', 
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);

                foreach ($cartItems as $item) {
                    DB::table('restaurant_order_details')->insert([
                        'restaurant_order_id' => $orderId, 
                        'restaurant_menu_id'  => $item->id, 
                        'quantity'            => (int) $item->quantity,
                        'price'               => $item->price,
                        'created_at'          => now(),
                        'updated_at'          => now()
                    ]);
                }
            }); 

            return redirect()->back()->with('success', 'Pesanan Room Service berhasil dikirim!');
        }

        public function restaurantOrders(Request $request)
        {
            $userId = auth()->id();
            $allActiveBookings = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('bookings.user_id', $userId)
                ->whereIn('bookings.status', ['confirmed', 'pending', 'checked_in'])
                ->select('bookings.id', 'rooms.room_number')
                ->get();

            $booking_id = $request->input('booking_id', optional($allActiveBookings->first())->id);
            $currentRoomBooking = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $booking_id)
                ->select('bookings.id as booking_id', 'rooms.room_number', 'room_types.name as room_name')
                ->first();

            $room_number = $currentRoomBooking ? $currentRoomBooking->room_number : null;
            $restaurant_menus = DB::table('restaurant_menus')->select('id', 'name as title', 'description', 'price', 'foto_url as image_url')->get();

            $restaurant_menus = $restaurant_menus->map(function($menu, $index) {
                $menu->is_signature = ($index === 0 || $menu->id % 4 === 0);
                $menu->venue_name = ($menu->id % 3 === 0) ? 'The Beach Club' : (($menu->id % 2 === 0) ? 'The Garden Atrium' : 'Oasis Fine Dining');
                return $menu;
            });

            $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
            $orderHistory = $guest 
                ? DB::table('restaurant_orders')
                    ->leftJoin('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
                    ->where('restaurant_orders.guest_id', $guest->id)
                    ->select('restaurant_orders.id', 'restaurant_orders.total_price', 'restaurant_orders.created_at', DB::raw("COALESCE(payments.payment_status, 'pending') as payment_status"))
                    ->orderBy('restaurant_orders.id', 'desc')
                    ->get()
                : collect();

            return view('guest.restaurantorders', compact('restaurant_menus', 'room_number', 'booking_id', 'allActiveBookings', 'orderHistory'));
        }

        public function facilitiesBooking()
        {
            $myReservations = DB::table('facility_bookings')->where('user_id', auth()->id())->orderBy('booking_date', 'asc')->orderBy('booking_time', 'asc')->get();
            $facilities = DB::table('facilities')->orderBy('id', 'asc')->get();

            $facilities = $facilities->map(function($f, $index) {
                if (empty($f->category)) { $f->category = ($index % 4 === 0) ? 'Wellness' : (($index % 3 === 0) ? 'Sports & Fitness' : (($index % 2 === 0) ? 'Pools & Beach' : 'Kids & Family')); }
                if (empty($f->access_type)) { $f->access_type = 'Complimentary'; }
                return $f;
            });

            return view('guest.facilitiesbooking', compact('myReservations', 'facilities'));
        }
  public function placeGastronomyOrder(Request $request)
        {
            $request->validate(['total_price' => 'required|numeric|min:0']);
            if (!Auth::check()) { return response()->json(['success' => false, 'message' => 'Silakan login.'], 401); }

            $guest = DB::table('guests')->where('email', Auth::user()->email)->first();
            if (!$guest) { return response()->json(['success' => false, 'message' => 'Profil tamu tidak ditemukan.'], 404); }

            try {
                DB::table('restaurant_orders')->insert([
                    'guest_id' => $guest->id,
                    'total_price' => (int) round($request->total_price),
                    'status' => 'ordered',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return response()->json(['success' => true, 'message' => 'Pesanan kuliner dikonfirmasi!']);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => 'Gagal memproses.'], 500); }
        }

        public function bookFacility(Request $request)
        {
            $request->validate([
                'facility_name' => 'required|string',
                'booking_date'  => 'required|date|after_or_equal:today',
                'booking_time'  => 'required',
                'guests_count'  => 'required|integer|min:1|max:10'
            ]);

            if (!Auth::check()) { return response()->json(['success' => false, 'message' => 'Silakan login.'], 401); }

            try {
                FacilityBooking::create([
                    'user_id'            => Auth::id(),
                    'facility_name'      => $request->facility_name,
                    'booking_date'       => $request->booking_date,
                    'booking_time'       => $request->booking_time,
                    'guests_count'       => (int) $request->guests_count,
                    'seating_preference' => $request->seating_preference ?? 'No Preference',
                    'notes'              => $request->notes ?? null,
                    'status'             => 'confirmed'
                ]);
                return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dipesan!']);
            } catch (\Exception $e) { return response()->json(['success' => false, 'message' => 'Gagal mengamankan slot.'], 500); }
        }
}