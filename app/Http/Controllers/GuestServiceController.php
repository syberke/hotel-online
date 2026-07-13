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
            $validated = $request->validate([
                'cart_data' => ['required', 'json'],
                'booking_id' => ['required', 'integer'],
            ]);
            $cartItems = collect(json_decode($validated['cart_data'], true));
            if ($cartItems->isEmpty() || $cartItems->count() > 25) {
                return redirect()->back()->with('error', 'Keranjang belanja tidak valid.');
            }

            $ownsBooking = DB::table('bookings')
                ->where('id', $validated['booking_id'])
                ->where('user_id', auth()->id())
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->exists();
            abort_unless($ownsBooking, 404);

            $guest = DB::table('guests')->where('email', auth()->user()->email)->first();
            if (!$guest) { return redirect()->back()->with('error', 'Profil manifest tamu Anda tidak terdaftar.'); }

            $requestedItems = $cartItems->mapWithKeys(function ($item) {
                $id = filter_var($item['id'] ?? null, FILTER_VALIDATE_INT);
                $quantity = filter_var($item['quantity'] ?? null, FILTER_VALIDATE_INT);
                abort_unless($id && $quantity && $quantity >= 1 && $quantity <= 20, 422);

                return [$id => $quantity];
            });

            $menus = DB::table('restaurant_menus')
                ->whereIn('id', $requestedItems->keys())
                ->get(['id', 'price'])
                ->keyBy('id');
            abort_unless($menus->count() === $requestedItems->count(), 422);

            $trustedItems = $requestedItems->map(function ($quantity, $id) use ($menus) {
                return [
                    'id' => (int) $id,
                    'quantity' => (int) $quantity,
                    'price' => (int) $menus->get($id)->price,
                ];
            })->values();

            $subtotal = $trustedItems->sum(fn ($item) => $item['price'] * $item['quantity']);

            $serviceCharge = (int) round($subtotal * 0.10);
            $tax = (int) round(($subtotal + $serviceCharge) * 0.11);
            $grandTotal = $subtotal + $serviceCharge + $tax;

            DB::transaction(function () use ($grandTotal, $trustedItems, $guest) {
                $orderId = DB::table('restaurant_orders')->insertGetId([
                    'guest_id'    => $guest->id, 
                    'total_price' => $grandTotal,
                    'status'      => 'ordered', 
                    'created_at'  => now(),
                    'updated_at'  => now()
                ]);

                foreach ($trustedItems as $item) {
                    DB::table('restaurant_order_details')->insert([
                        'restaurant_order_id' => $orderId, 
                        'restaurant_menu_id'  => $item['id'],
                        'quantity'            => $item['quantity'],
                        'price'               => $item['price'],
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
                ->where('bookings.user_id', $userId)
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
            $validated = $request->validate([
                'menu_id' => ['required', 'integer', 'exists:restaurant_menus,id'],
                'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            ]);
            if (!Auth::check()) { return response()->json(['success' => false, 'message' => 'Silakan login.'], 401); }

            $guest = DB::table('guests')->where('email', Auth::user()->email)->first();
            if (!$guest) { return response()->json(['success' => false, 'message' => 'Profil tamu tidak ditemukan.'], 404); }

            try {
                $menu = DB::table('restaurant_menus')->where('id', $validated['menu_id'])->first();
                $quantity = (int) $validated['quantity'];
                $unitPrice = (int) $menu->price;

                DB::transaction(function () use ($guest, $menu, $quantity, $unitPrice) {
                    $orderId = DB::table('restaurant_orders')->insertGetId([
                        'guest_id' => $guest->id,
                        'total_price' => $unitPrice * $quantity,
                        'status' => 'ordered',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('restaurant_order_details')->insert([
                        'restaurant_order_id' => $orderId,
                        'restaurant_menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $unitPrice,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                });
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
