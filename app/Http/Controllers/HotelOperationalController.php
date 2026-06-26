<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Facades\Session;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\FacilityBooking;
use Midtrans\Config;
use Midtrans\Snap;

class HotelOperationalController extends Controller
{
    /**
     * ======================================================================
     * CORE COMPONENT: PUBLIC & GUEST PORTAL FUNCTIONS
     * ======================================================================
     */

    /**
     * Halaman Utama (Home) - Tampilkan Kamar Berdasarkan Kolom 'price'
     */
    public function index()
    {
        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                     ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $culinaryMenus = DB::table('restaurant_menus')->get();

        return view('page.home', compact('roomsLiveList', 'culinaryMenus'));
    }

    /**
     * Halaman Semua Kamar (/rooms)
     */
    public function allRoomsView()
    {
        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                     ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night', 
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $allCategories = DB::table('room_types')->select('name')->get();
        $totalInventoryReady = DB::table('rooms')->where('status', 'available')->count();

        return view('page.rooms', compact('roomsLiveList', 'allCategories', 'totalInventoryReady')); 
    }

    /**
     * Menampilkan Detail Satu Kamar Berdasarkan Kalender Reservasi
     */
    public function roomShow($id, Request $request)
    {
        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+1 day')));

        $occupiedCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id') 
            ->where('rooms.room_type_id', $id) 
            ->whereIn('bookings.status', ['confirmed', 'pending', 'checked_in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '<=', $checkIn)
                      ->where('bookings.check_out', '>', $checkIn);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '<', $checkOut)
                      ->where('bookings.check_out', '>=', $checkOut);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '>=', $checkIn)
                      ->where('bookings.check_out', '<=', $checkOut);
                });
            })
            ->count();

        $room = DB::table('room_types')->where('id', $id)->first();

        if (!$room) { 
            abort(404); 
        }

        $room->price_per_night = $room->price;

        $totalPhysicalRooms = DB::table('rooms')
            ->where('room_type_id', $id)
            ->where('status', 'available')
            ->count();

        $liveAvailableCount = max(0, $totalPhysicalRooms - $occupiedCount);
        $room->available_count = $liveAvailableCount;
        
        return view('page.rooms-detail', compact('room'));
    }

  public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'suite_type' => 'required|string',
            'guests' => 'required|string',
            'room_id' => 'nullable|integer'
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guestsCount = (int) filter_var($request->guests, FILTER_SANITIZE_NUMBER_INT) ?: 2;

        $roomTypeMaster = DB::table('room_types')->where('name', $request->suite_type)->first();
        if (!$roomTypeMaster) {
            return response()->json(['success' => false, 'message' => 'Tipe suite tidak terdaftar.'], 404);
        }

        $maxCapacityAllowed = 2;
        if (str_contains(strtolower($roomTypeMaster->name), 'deluxe')) { $maxCapacityAllowed = 4; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'executive')) { $maxCapacityAllowed = 6; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'family')) { $maxCapacityAllowed = 8; }

        if ($guestsCount > $maxCapacityAllowed) {
            return response()->json(['success' => false, 'message' => "Kamar " . $roomTypeMaster->name . " maksimal untuk " . $maxCapacityAllowed . " tamu."], 422);
        }

        // Cek kamar yang terpakai pada rentang tanggal tersebut
        $occupiedRoomIds = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'pending', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->pluck('room_id')
            ->toArray();

        $roomQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('room_types.name', $request->suite_type)
            ->where('rooms.status', 'available') 
            ->whereNotIn('rooms.id', $occupiedRoomIds) 
            ->select('rooms.id as room_id', 'room_types.price', 'rooms.room_number');

        // PRIORITAS: Jika room_id dikirim (Proses Simpan/Book), kunci nomor kamar tersebut
        if ($request->filled('room_id') && !$request->has('mode_check_only')) {
            $roomQuery->where('rooms.id', $request->room_id);
        } else {
            $roomQuery->orderBy('rooms.room_number', 'asc'); // Urutkan teratur agar konsisten
        }

        $room = $roomQuery->first();

        // KONDISI A: Hanya mengecek (AJAX Verification)
        if ($request->wantsJson() && $request->has('mode_check_only')) {
            if (!$room) {
                return response()->json([
                    'available' => false, 
                    'message' => 'Maaf, seluruh nomor kamar tipe ' . $request->suite_type . ' sudah penuh pada tanggal tersebut.'
                ]);
            }
            return response()->json([
                'available' => true, 
                'room_id' => $room->room_id, // Mengirimkan ID Kamar terpilih ke Client
                'message' => 'Kamar nomor ' . $room->room_number . ' siap dialokasikan untuk Anda!'
            ]);
        }

        // KONDISI B: Menyimpan Pemesanan Kamar Real
        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Kamar pilihan Anda baru saja terisi. Silakan lakukan cek ulang.'], 422);
        }

        $days = max(1, (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24));
        $totalPrice = $room->price * $days;

        if (Auth::check()) {
            DB::transaction(function () use ($room, $totalPrice, $checkIn, $checkOut, $guestsCount) {
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id'      => Auth::id(),
                    'guest_id'     => Auth::id(), 
                    'room_id'      => $room->room_id, 
                    'check_in'     => $checkIn,
                    'check_out'    => $checkOut,
                    'guests_count' => $guestsCount, 
                    'total_price'  => $totalPrice,
                    'status'       => 'pending',
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);

                DB::table('payments')->insert([
                    'booking_id'     => $bookingId, 
                    'amount'         => $totalPrice,
                    'payment_method' => 'transfer',
                    'payment_status' => 'pending',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
            }); 

            return response()->json(['success' => true, 'redirect' => route('guest.dashboard')]);
        }

        return response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Silakan login terlebih dahulu.']);
    }
    public function dashboard()
    {
        $bookings = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', Auth::id())
            ->select('bookings.*', 'rooms.room_number', 'room_types.name as room_type_name')
            ->orderBy('bookings.check_in', 'asc')
            ->get();

        $guestRecord = DB::table('guests')->where('email', Auth::user()->email)->first();
        
        $activeOrders = $guestRecord 
            ? DB::table('restaurant_orders')->where('guest_id', $guestRecord->id)->orderBy('created_at', 'desc')->get() 
            : collect();

        try {
            $weatherResponse = Http::timeout(2)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => -8.4095, 
                'longitude' => 115.1889,
                'current_weather' => true
            ]);
            
            $temperature = $weatherResponse->successful() 
                ? round($weatherResponse->json()['current_weather']['temperature']) . '°C' 
                : '29°C';
        } catch (\Exception $e) {
            $temperature = '29°C'; 
        }

        return view('guest.dashboard', compact('bookings', 'activeOrders', 'temperature'));
    }

    /**
     * Mengambil Riwayat Semua Pemesanan Kamar Saya
     */
    public function myBookings()
    {
        $bookings = DB::table('bookings')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', auth()->id())
            ->select('bookings.*', 'rooms.room_number', 'room_types.name as type_name')
            ->orderBy('bookings.check_in', 'desc')
            ->get();

        return view('guest.mybookings', compact('bookings'));
    }

    /**
     * Menampilkan Manifest Informasi Kamar Selama Menginap
     */
    public function myStay(Request $request)
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
            ->select(
                'bookings.id as booking_id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.guests_count',
                'room_types.name as room_name',
                'room_types.foto_url',
                'rooms.room_number',
                'bookings.total_price as room_bill'
            )
            ->first();

        $restaurantBill = DB::table('restaurant_orders')
            ->where('guest_id', $userId) 
            ->where('status', 'ordered') 
            ->sum('total_price');

        $itineraries = DB::table('facility_bookings')
            ->where('user_id', $userId)
            ->where('booking_date', date('Y-m-d'))
            ->orderBy('booking_time', 'asc')
            ->get();

        return view('guest.mystay', compact('currentBooking', 'restaurantBill', 'itineraries', 'allActiveBookings'));
    }

    /**
     * Halaman Pemesanan Fasilitas Internal
     */
    public function facilitiesIndex()
    {
        $facilities = DB::table('facilities')
            ->select('id', 'name', 'description', 'image_url', 'hours', 'requires_booking', 'category', 'access_type')
            ->orderBy('id', 'asc')
            ->get();

        $facilities = $facilities->map(function($f, $index) {
            if (empty($f->category)) {
                if ($index % 4 === 0) $f->category = 'Wellness';
                elseif ($index % 3 === 0) $f->category = 'Sports & Fitness';
                elseif ($index % 2 === 0) $f->category = 'Pools & Beach';
                else $f->category = 'Kids & Family';
            }
            if (empty($f->access_type)) { $f->access_type = 'Premium Access'; }
            return $f;
        });

        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                     ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $culinaryMenus = DB::table('restaurant_menus')->take(3)->get();

        return view('page.facilities', compact('facilities', 'roomsLiveList', 'culinaryMenus')); 
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
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghubungkan gateway: ' . $e->getMessage()], 500);
        }
    }

    public function menuShow($id)
    {
        $menu = DB::table('restaurant_menus')->where('id', $id)->first();
        if (!$menu) { abort(404); }
        return view('page.restaurants-detail', compact('menu'));
    }

    public function restaurantIndex(Request $request)
    {
        $query = DB::table('restaurant_menus');

        if ($request->has('category') && $request->category != 'All Menu') {
            $query->where(function($q) use ($request) {
                $q->where('description', 'ILIKE', '%' . $request->category . '%')
                  ->orWhere('name', 'ILIKE', '%' . $request->category . '%');
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('dietary') && is_array($request->dietary)) {
            $query->where(function($q) use ($request) {
                foreach ($request->dietary as $diet) {
                    $q->orWhere('description', 'ILIKE', '%' . $diet . '%');
                }
            });
        }

        $culinaryMenus = $query->get();
        return view('page.restaurant', compact('culinaryMenus'));
    }

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

    public function cancelBooking($id)
    {
        $userId = auth()->id();
        $booking = DB::table('bookings')->where('id', $id)->where('user_id', $userId)->first();

        if (!$booking) { return redirect()->back()->with('error', 'Data reservasi tidak valid.'); }
        if ($booking->status !== 'pending') { return redirect()->back()->with('error', 'Hanya reservasi pending yang dapat dibatalkan.'); }

        // Batalkan pesanan
        DB::transaction(function () use ($booking) {
            DB::table('bookings')->where('id', $booking->id)->update(['status' => 'cancelled', 'updated_at' => now()]);
            DB::table('payments')->where('booking_id', $booking->id)->update(['payment_status' => 'failed', 'note' => 'Dibatalkan oleh pengguna.', 'updated_at' => now()]);
        }); 

        return redirect()->back()->with('success', 'Reservasi kamar #' . str_pad($booking->id, 2, '0', STR_PAD_LEFT) . ' dibatalkan.');
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

    public function billingMatrix() { return view('guest.billingmatrix'); }

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

public function adminReservationsView(Request $request)
    {
        // 1. Ambil data statistik counter atas secara real-time
        $stats = [
            'total_resv' => \App\Models\Booking::count(),
            'confirmed'  => \App\Models\Booking::where('status', 'confirmed')->count(),
            'pending'    => \App\Models\Booking::where('status', 'pending')->count(),
            'arrivals'   => \App\Models\Booking::whereDate('check_in', date('Y-m-d'))->count(),
            'departures' => \App\Models\Booking::whereDate('check_out', date('Y-m-d'))->count(),
        ];

        // 2. Ambil data master tipe kamar untuk dropdown filter
        $roomTypes = DB::table('room_types')->select('name')->distinct()->get();

        // 3. Bangun query dengan Eager Loading (Mencegah N+1 Problem database)
        $query = \App\Models\Booking::with(['user', 'room.roomType', 'payments']);

        // Filter: Pencarian Pintar (Booking ID, Nama Guest, atau Email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $cleanId = ltrim($search, '#OA-');
                $q->where('id', 'like', "%{$cleanId}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'ILIKE', "%{$search}%")
                                ->orWhere('email', 'ILIKE', "%{$search}%");
                  });
            });
        }

        // Filter: Status Booking
        if ($request->filled('status') && $request->status != 'All Status') {
            $statusDb = strtolower(str_replace(' ', '_', $request->status));
            $query->where('status', $statusDb);
        }

        // Filter: Jenis Tipe Kamar Suite
        if ($request->filled('room_type') && $request->room_type != 'All Room Types') {
            $query->whereHas('room.roomType', function($q) use ($request) {
                $q->where('name', $request->room_type);
            });
        }

        // Filter: Rentang Tanggal Operasional
        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereDate('check_in', '>=', \Carbon\Carbon::parse($dates[0])->toDateString())
                      ->whereDate('check_out', '<=', \Carbon\Carbon::parse($dates[1])->toDateString());
            }
        }

        // 4. Eksekusi Paginasi (Menggunakan nama $bookings untuk menyembuhkan error view)
        $perPage = $request->get('per_page', 10);
        $bookings = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        // 5. Logic Detail Side Panel (Aside Desk)
        $selectedBookingId = $request->get('selected_id');
        $selectedBooking = null;

        if ($selectedBookingId) {
            $selectedBooking = \App\Models\Booking::with(['user', 'room.roomType', 'payments'])->find($selectedBookingId);
        } elseif ($bookings->count() > 0) {
            $selectedBooking = $bookings->first();
        }

        // 6. Return Data seimbang ke Blade View tanpa ada data dummy lagi
        return view('admin.reservation', compact('bookings', 'stats', 'roomTypes', 'selectedBooking'));
    }
    /**
     * Menu 2: Front Desk Operations
     */
    public function adminFrontDeskView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. HITUNG METRIK COUNTER ATAS (REAL-TIME)
        // ======================================================================
        $arrivalsCount = DB::table('bookings')->whereDate('check_in', $today)->whereIn('status', ['pending', 'confirmed'])->count();
        $departuresCount = DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_in')->count();
        $checkedInCount = DB::table('bookings')->where('status', 'checked_in')->count();
        
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $availableRooms = DB::table('rooms')->where('status', 'available')->count();
        $outOfOrderRooms = DB::table('rooms')->where('status', 'maintenance')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        
        // Persentase Tingkat Okupansi Kamar Nyata
        $occupancyRate = round(($occupiedRooms / $totalRooms) * 100, 1);

        // ======================================================================
        // 2. QUERY MASTER UNTUK TODAY'S RESERVATIONS TABLE (DENGAN FILTER)
        // ======================================================================
        $query = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'bookings.*', 
                'users.name as guest_name', 
                'users.email as guest_email', 
                'rooms.room_number', 
                'room_types.name as room_type'
            );

        // Filter: Tab Kategori (All, Arrivals, In House, Departures)
        $currentTab = $request->get('tab', 'all');
        if ($currentTab == 'arrivals') {
            $query->whereDate('bookings.check_in', $today)->whereIn('bookings.status', ['pending', 'confirmed']);
        } elseif ($currentTab == 'in_house') {
            $query->where('bookings.status', 'checked_in');
        } elseif ($currentTab == 'departures') {
            $query->whereDate('bookings.check_out', $today)->where('bookings.status', 'checked_in');
        } else {
            // Default 'all': Menampilkan semua data yang relevan dengan pergerakan hari ini
            $query->where(function($q) use ($today) {
                $q->whereDate('bookings.check_in', $today)
                  ->orWhereDate('bookings.check_out', $today)
                  ->orWhere('bookings.status', 'checked_in');
            });
        }

        // Filter: Pencarian Bilah Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $cleanSearch = ltrim($search, '#OA-');
                $q->where('bookings.id', 'like', "%{$cleanSearch}%")
                  ->orWhere('users.name', 'ILIKE', "%{$search}%")
                  ->orWhere('users.email', 'ILIKE', "%{$search}%");
            });
        }

        // Eksekusi Paginasi Tabel Atas
        $todayReservations = $query->orderBy('bookings.created_at', 'desc')->paginate(5, ['*'], 'resv_page')->withQueryString();

   $inHouseGuests = DB::table('bookings')
    ->join('users', 'bookings.user_id', '=', 'users.id')
    ->join('guests', 'users.email', '=', 'guests.email') // Hubungkan users ke guests via email
    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    ->where('bookings.status', 'checked_in')
    ->select(
        'bookings.*', 
        'users.name as guest_name', 
        'guests.phone as guest_phone', 
        'guests.foto_url as guest_avatar', // Mengambil foto_url yang benar dari tabel guests
        'rooms.room_number', 
        'room_types.name as room_type'
    )
    ->orderBy('rooms.room_number', 'asc')
    ->paginate(5, ['*'], 'guest_page')->withQueryString();
        // ======================================================================
        // 4. DATA PANEL ASIDE (ARRIVALS PREVIEW)
        // ======================================================================
      $asideArrivals = DB::table('bookings')
    ->join('users', 'bookings.user_id', '=', 'users.id')
    ->join('guests', 'users.email', '=', 'guests.email') // Join ke tabel guests untuk mengambil foto
    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
    ->whereDate('bookings.check_in', $today)
    ->whereIn('bookings.status', ['pending', 'confirmed'])
    ->select(
        'bookings.*', 
        'users.name as guest_name', 
        'guests.foto_url as guest_avatar', // Diubah dari users.foto_url menjadi guests.foto_url
        'room_types.name as room_type'
    )
    ->orderBy('bookings.check_in', 'asc')
    ->take(5)
    ->get();
        return view('admin.frontdesk', compact(
            'arrivalsCount', 'departuresCount', 'checkedInCount', 'availableRooms', 'occupancyRate',
            'totalRooms', 'outOfOrderRooms', 'occupiedRooms', 'todayReservations', 'inHouseGuests', 
            'asideArrivals', 'currentTab'
        ));
    }

    /**
     * Menu 3: Rooms & Inventory
     */
    public function adminRoomsInventoryView(Request $request)
    {
        // ======================================================================
        // 1. HITUNG COUNTER METRIK KAMAR (REAL-TIME)
        // ======================================================================
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $availableRooms = DB::table('rooms')->where('status', 'available')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $maintenanceRooms = DB::table('rooms')->where('status', 'maintenance')->count();
        $dirtyRooms = DB::table('rooms')->where('status', 'dirty')->count(); // Diasumsikan sebagai proses cleaning

        $stats = [
            'total' => $totalRooms,
            'available' => $availableRooms,
            'available_pct' => round(($availableRooms / $totalRooms) * 100, 1),
            'occupied' => $occupiedRooms,
            'occupied_pct' => round(($occupiedRooms / $totalRooms) * 100, 1),
            'maintenance' => $maintenanceRooms,
            'maintenance_pct' => round(($maintenanceRooms / $totalRooms) * 100, 1),
            'cleaning' => $dirtyRooms,
            'cleaning_pct' => round(($dirtyRooms / $totalRooms) * 100, 1),
        ];

        // ======================================================================
        // 2. QUERY UTAMA DAFTAR KAMAR + PENCARIAN & FILTER
        // ======================================================================
        $query = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'rooms.*',
                'room_types.name as type_name',
                'room_types.price',
                'room_types.max_capacity',
                'room_types.foto_url'
            );

        // Filter: Pencarian Berdasarkan Nomor Kamar atau Nama Tipe Kamar
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rooms.room_number', 'like', "%{$search}%")
                  ->orWhere('room_types.name', 'ILIKE', "%{$search}%");
            });
        }

        // Filter: Berdasarkan Status Kamar Langsung
        if ($request->filled('status_filter')) {
            $query->where('rooms.status', $request->status_filter);
        }

        // Eksekusi Paginasi Data Kamar (Default: 10 item per halaman)
        $rooms = $query->orderBy('rooms.room_number', 'asc')->paginate(10)->withQueryString();

        // Cari informasi booking aktif untuk mendapatkan info check-out tamu pada kamar terkait (jika diduduki)
        $today = now()->format('Y-m-d');
        $activeBookings = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in', '<=', $today)
            ->where('check_out', '>', $today)
            ->get()
            ->keyBy('room_id');

        foreach ($rooms->items() as $room) {
            $room->active_booking = $activeBookings->get($room->id);
        }

        // ======================================================================
        // 3. ASIDE COMPONENT: ROOM TYPE SUMMARY MATRIX
        // ======================================================================
        $roomTypesList = DB::table('room_types')->get();
        $summary = [];

        foreach ($roomTypesList as $type) {
            $typeTotal = DB::table('rooms')->where('room_type_id', $type->id)->count();
            $typeOccupied = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'occupied')->count();
            $typeAvailable = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'available')->count();

            if ($typeTotal > 0) {
                $summary[] = [
                    'name' => $type->name,
                    'total' => $typeTotal,
                    'occupied' => $typeOccupied,
                    'available' => $typeAvailable
                ];
            }
        }

        return view('admin.rooms&inventory', compact('stats', 'rooms', 'summary'));
    }
public function adminDashboardView()
{
    // ======================================================================
    // 1. UTAMA: METRIK HERO UTAMA DECK (DARI DATABASE NYATA)
    // ======================================================================
    
    // Total Reservations & Perbandingan (vs Minggu Lalu)
    $totalReservations = DB::table('bookings')->count();
    $lastWeekReservations = DB::table('bookings')
        ->where('created_at', '<', now()->subWeek())
        ->count();
    $reservationDiff = $lastWeekReservations > 0 
        ? round((($totalReservations - $lastWeekReservations) / $lastWeekReservations) * 100, 1) 
        : 0;

    // Total Tamu Unik & Perbandingan
    $totalGuests = DB::table('bookings')->sum('guests_count') ?: 0;
    $lastWeekGuests = DB::table('bookings')
        ->where('created_at', '<', now()->subWeek())
        ->sum('guests_count') ?: 0;
    $guestDiff = $lastWeekGuests > 0 
        ? round((($totalGuests - $lastWeekGuests) / $lastWeekGuests) * 100, 1) 
        : 0;

    // Tingkat Okupansi Kamar (Occupancy Rate Real-time)
    $totalPhysicalRooms = DB::table('rooms')->count() ?: 1;
    $occupiedRoomsCount = DB::table('rooms')->where('status', 'occupied')->count();
    $occupancyRate = ($occupiedRoomsCount / $totalPhysicalRooms) * 100;
    
    // Perbandingan Okupansi Minggu Lalu
    $lastWeekOccupied = DB::table('bookings')
        ->where('status', 'checked_in')
        ->where('created_at', '<', now()->subWeek())
        ->count();
    $lastWeekOccupancyRate = ($lastWeekOccupied / $totalPhysicalRooms) * 100;
    $occupancyDiff = round($occupancyRate - $lastWeekOccupancyRate, 1);

    // Rata-rata Pendapatan Harian (Average Daily Rate - ADR)
    $confirmedBookingsCount = DB::table('bookings')->where('status', 'confirmed')->count() ?: 1;
    $totalRoomRevenue = DB::table('payments')
        ->whereNotNull('booking_id')
        ->where('payment_status', 'paid')
        ->sum('amount') ?: 0;
    $adr = $totalRoomRevenue / $confirmedBookingsCount;
    $adrDiff = 0; // Metrik stabil pembanding database awal

    // Total Pendapatan Kotor Seluruh Departemen (Grand Total Revenue)
    $totalFbRevenue = DB::table('payments')
        ->whereNotNull('restaurant_order_id')
        ->where('payment_status', 'paid')
        ->sum('amount') ?: 0;
    $totalRevenue = $totalRoomRevenue + $totalFbRevenue;
    $revenueDiff = 0;

    // ======================================================================
    // 2. DIAGRAM: OKUPANSI MINGGUAN & ALOKASI STATUS RESERVASI
    // ======================================================================
    
    // Tren Okupansi Dinamis 4 Hari Terakhir
    $occupancyDates = [
        now()->subDays(3)->format('d M'),
        now()->subDays(2)->format('d M'),
        now()->subDays(1)->format('d M'),
        now()->format('d M')
    ];
    
    // Kalkulasi Koordinat Line Graph Berdasarkan Okupansi Nyata Harian
    $currentTrendPoints = [];
    for ($i = 3; $i >= 0; $i--) {
        $dayDate = now()->subDays($i)->format('Y-m-d');
        $dayOccupied = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in', '<=', $dayDate)
            ->where('check_out', '>', $dayDate)
            ->count();
        // Ubah skala persen menjadi koordinat tinggi SVG (maksimal tinggi 140px)
        $currentTrendPoints[] = round(($dayOccupied / $totalPhysicalRooms) * 100);
    }
    
    $occupancyTrend = [
        'past' => [40, 55, 45, 60], // Baseline statis pembanding grafik
        'current' => $currentTrendPoints
    ];

    // Persentase Share Status Reservasi (Pemesanan Kamar)
    $statusShares = [
        'confirmed'  => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'confirmed')->count() / $totalReservations) * 100 : 0,
        'pending'    => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'pending')->count() / $totalReservations) * 100 : 0,
        'checked_in' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'checked_in')->count() / $totalReservations) * 100 : 0,
        'cancelled'  => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'cancelled')->count() / $totalReservations) * 100 : 0,
    ];

    // ======================================================================
    // 3. MANIFEST: KEDATANGAN HARI INI & AKTIVITAS TRANSAKSI TERBARU
    // ======================================================================
    
    // Manifest Kedatangan Tamu Hari Ini (Today's Arrivals)
    $todayArrivals = DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        ->whereDate('bookings.check_in', now()->format('Y-m-d'))
        ->select(
            'users.name as guest_name', 
            'room_types.name as room_type', 
            'rooms.room_number',
            DB::raw("CASE WHEN room_types.price >= 2000000 THEN 1 ELSE 0 END as is_vip")
        )
        ->take(5)
        ->get();

    // Log Transaksi & Reservasi Sistem Terbaru (Recent Activities Feed)
    $recentActivities = DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->select(
            DB::raw("'booking' as type"),
            DB::raw("CONCAT('New reservation #OA-', bookings.id, ' confirmed') as title"),
            DB::raw("CONCAT('Room ', rooms.room_number, ' • ', bookings.check_in, ' to ', bookings.check_out) as description"),
            'bookings.created_at'
        )
        ->orderBy('bookings.id', 'desc')
        ->take(4)
        ->get();

    // Konversi Carbon Instansiasi Koleksi Waktu pada Data Aktivitas Manual Objek Objek DB
    foreach ($recentActivities as $activity) {
        $activity->created_at = \Carbon\Carbon::parse($activity->created_at);
    }

    // ======================================================================
    // 4. OPERASIONAL: UTALISASI PRODUKTIVITAS KAMAR & SANITASI HOUSEKEEPING
    // ======================================================================
    
    // Performa Yield Hasil Pendapatan Per Kelas Kamar
    $roomTypesList = DB::table('room_types')->get();
    $roomPerformances = [];

    foreach ($roomTypesList as $type) {
        $typeTotal = DB::table('rooms')->where('room_type_id', $type->id)->count();
        $typeOccupied = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'occupied')->count();
        $typeRevenue = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('rooms.room_type_id', $type->id)
            ->where('payments.payment_status', 'paid')
            ->sum('payments.amount') ?: 0;

        $roomPerformances[] = [
            'type'     => $type->name,
            'total'    => $typeTotal,
            'occupied' => $typeOccupied,
            'rate'     => $typeTotal > 0 ? ($typeOccupied / $typeTotal) * 100 : 0,
            'revenue'  => $typeRevenue
        ];
    }

    // Finansial Performa Pendapatan Cabang Departemen
    $deptRevenue = [
        'room_service' => $totalFbRevenue * 0.35, // Alokasi perkiraan pembagian pesanan via kamar
        'restaurant'   => $totalFbRevenue * 0.65, // Alokasi penjualan kuliner langsung di kasir resto
        'spa'          => DB::table('facility_bookings')->where('facility_name', 'LIKE', '%Spa%')->count() * 350000
    ];

    $totalDeptSum = array_sum($deptRevenue) ?: 1;
    $deptShares = [
        'room_service' => ($deptRevenue['room_service'] / $totalDeptSum) * 100,
        'restaurant'   => ($deptRevenue['restaurant'] / $totalDeptSum) * 100,
        'spa'          => ($deptRevenue['spa'] / $totalDeptSum) * 100,
    ];

    // Status Manajemen Kebersihan Fisik Kamar (Housekeeping Sanitasi Log)
    $hkStatus = [
        'clean'     => DB::table('rooms')->where('status', 'available')->count(),
        'dirty'     => DB::table('rooms')->where('status', 'dirty')->count(),
        'inspected' => DB::table('rooms')->where('status', 'available')->count(), // Kamar siap huni terinspeksi FO
        'oos'       => DB::table('rooms')->where('status', 'out_of_order')->count(),
    ];

    // ======================================================================
    // 5. RETURN BIND KE TEMPLATE BLADE VIEW
    // ======================================================================
    return view('admin.dashboard', compact(
        'totalReservations', 'reservationDiff', 'totalGuests', 'guestDiff',
        'occupancyRate', 'occupancyDiff', 'adr', 'adrDiff', 'totalRevenue', 'revenueDiff',
        'occupancyTrend', 'occupancyDates', 'statusShares', 'todayArrivals',
        'roomPerformances', 'deptRevenue', 'deptShares', 'recentActivities', 'hkStatus'
    ));
}

    /**
     * Menu 4: Room Service Management
     */
    public function adminRoomServiceView()
    {
        $orders = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->select('restaurant_orders.*', 'guests.name as guest_name')
            ->orderBy('restaurant_orders.created_at', 'desc')
            ->get();

        return view('admin.roomservice', compact('orders'));
    }

    /**
     * Menu 5: Restaurant Gastronomy
     */
    public function adminRestaurantView()
    {
        $orders = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->select('restaurant_orders.*', 'guests.name as guest_name')
            ->get();

        $topItems = DB::table('restaurant_order_details')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->select('restaurant_menus.name', DB::raw('sum(restaurant_order_details.quantity) as qty'), DB::raw('sum(restaurant_order_details.quantity * restaurant_order_details.price) as revenue'))
            ->groupBy('restaurant_menus.name')
            ->orderBy('qty', 'desc')->take(3)->get();

        return view('admin.restaurant', compact('orders', 'topItems'));
    }

    /**
     * Menu 6: Facilities & Wellness
     */
    public function adminFacilitiesView()
    {
        $bookings = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->select('facility_bookings.*', 'users.name as guest_name')
            ->orderBy('facility_bookings.booking_date', 'desc')
            ->get();

        return view('admin.facilities', compact('bookings'));
    }

    /**
     * Menu 7: Finance & Billing Matrix
     */
    public function adminFinanceView()
    {
        $transactions = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->select('payments.*', 'bookings.status as b_status')
            ->orderBy('payments.created_at', 'desc')->take(10)->get();

        $roomRevenue = DB::table('payments')->whereNotNull('booking_id')->where('payment_status', 'paid')->sum('amount');
        $fbRevenue = DB::table('payments')->whereNotNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount');
        $totalRevenue = $roomRevenue + $fbRevenue;

        return view('admin.finance', compact('transactions', 'roomRevenue', 'fbRevenue', 'totalRevenue'));
    }

    /**
     * Menu 8: Operational Reports Analyzer
     */
    public function adminReportsView()
    {
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $avgOccupancy = round(($occupiedRooms / $totalRooms) * 100, 1);

        $adr = DB::table('bookings')->where('status', 'confirmed')->avg('total_price') ?: 0;
        $revpar = $adr * ($avgOccupancy / 100);

        return view('admin.reports', compact('avgOccupancy', 'adr', 'revpar'));
    }

    /**
     * Menu 9: User & Role Controls
     */
    public function adminUserAndRoleView()
    {
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        $rolesCount = DB::table('users')->select('role', DB::raw('count(*) as total'))->groupBy('role')->get();

        return view('admin.userandrole', compact('users', 'rolesCount'));
    }

    /**
     * ======================================================================
     * THIRD-PARTY INTEGRATION: MIDTRANS PAYMENT GATEWAY AUTOMATION
     * ======================================================================
     */

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
        } catch (\Exception $e) { return response()->json(['success' => false, 'message' => $e->getMessage()], 500); }
    }

    public function handleMidtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) { return response()->json(['message' => 'Invalid Signature'], 403); }

        $orderParts = explode('-', $request->order_id);
        $bookingId = $orderParts[1] ?? null;
        $transactionStatus = $request->transaction_status;
        $statusToUpdate = ($transactionStatus == 'capture' || $transactionStatus == 'settlement') ? 'confirmed' : (in_array($transactionStatus, ['deny', 'expire', 'cancel']) ? 'cancelled' : null);

        if ($statusToUpdate && $bookingId) {
            DB::table('bookings')->where('id', $bookingId)->update(['status' => $statusToUpdate, 'updated_at' => now()]);
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
        DB::transaction(function () use ($request) {
            DB::table('payments')->where('restaurant_order_id', $request->order_id)->update(['payment_status' => 'paid', 'updated_at' => now()]);
            DB::table('restaurant_orders')->where('id', $request->order_id)->update(['status' => 'paid', 'updated_at' => now()]);
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

    public function localPaymentSuccess(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);
        $booking = DB::table('bookings')->where('id', $request->booking_id)->where('user_id', auth()->id())->first();

        if (!$booking) { return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan.'], 404); }
        if ($booking->status === 'pending') {
            DB::table('bookings')->where('id', $request->booking_id)->where('user_id', auth()->id())->update(['status' => 'confirmed', 'updated_at' => now()]);
        }
        return response()->json(['success' => true, 'message' => 'Database lokal diperbarui.']);
    }

    /**
     * Helper Utilities
     */
    public function changeLanguage($locale)
    {
        if (in_array($locale, ['en', 'id'])) { Session::put('locale', $locale); }
        return redirect()->back();
    }
}