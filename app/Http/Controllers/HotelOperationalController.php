<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http; 
use App\Models\FacilityBooking;
use Midtrans\Config;
use Midtrans\Snap;

class HotelOperationalController extends Controller
{
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
                'room_types.price as price_per_night', // Alias aman untuk visual blade
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

        // FIX: Menggunakan kolom 'check_in' dan 'check_out' sesuai skema asli database
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
                }) ;
            })
            ->count();

        $room = DB::table('room_types')->where('id', $id)->first();

        if (!$room) { 
            abort(404); 
        }

        // Menyediakan variabel penolong agar Blade tidak mencari properti price_per_night
        $room->price_per_night = $room->price;

        $totalPhysicalRooms = DB::table('rooms')
            ->where('room_type_id', $id)
            ->where('status', 'available')
            ->count();

        $liveAvailableCount = max(0, $totalPhysicalRooms - $occupiedCount);
        $room->available_count = $liveAvailableCount;
        
        return view('page.rooms-detail', compact('room'));
    }

    /**
     * Sinkronisasi Cek Ketersediaan dan Validasi Kuota Tamu Dinamis
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'suite_type' => 'required|string',
            'guests' => 'required|string'
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        // Ekstrak jumlah tamu nyata dari input string HTML frontend Anda
        $guestsCount = 2; 
        if (str_contains($request->guests, '1')) { $guestsCount = 1; }
        elseif (str_contains($request->guests, '3')) { $guestsCount = 3; }
        elseif (str_contains($request->guests, '4')) { $guestsCount = 4; }
        elseif (str_contains($request->guests, '6')) { $guestsCount = 6; }
        elseif (str_contains($request->guests, '8')) { $guestsCount = 8; }

        // Ambil manifest kamar target
        $roomTypeMaster = DB::table('room_types')->where('name', $request->suite_type)->first();
        if (!$roomTypeMaster) {
            return response()->json(['success' => false, 'message' => 'Tipe suite tidak terdaftar.'], 404);
        }

        // PENGAMAN KAPASITAS NYATA: Menyesuaikan aturan kamar yang Anda miliki
        $maxCapacityAllowed = 2; // Default Standard
        if (str_contains(strtolower($roomTypeMaster->name), 'deluxe')) { $maxCapacityAllowed = 4; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'executive')) { $maxCapacityAllowed = 6; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'family')) { $maxCapacityAllowed = 8; }

        if ($guestsCount > $maxCapacityAllowed) {
            $msgMax = "Kamar " . $roomTypeMaster->name . " hanya diizinkan maksimal untuk " . $maxCapacityAllowed . " orang tamu.";
            return $request->wantsJson() 
                ? response()->json(['success' => false, 'message' => $msgMax], 422)
                : redirect()->back()->withInput()->with('error', $msgMax);
        }

        // Cek kamar terisi menggunakan kolom tanggal asli
        $occupiedRoomIds = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'pending', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->pluck('room_id')
            ->toArray();

        $room = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('room_types.name', $request->suite_type)
            ->where('rooms.status', 'available') 
            ->whereNotIn('rooms.id', $occupiedRoomIds) 
            ->select('rooms.id as room_id', 'room_types.price', 'rooms.room_number')
            ->inRandomOrder()
            ->first();

        if ($request->wantsJson() && $request->has('mode_check_only')) {
            if (!$room) {
                return response()->json([
                    'available' => false, 
                    'message' => 'Maaf, seluruh nomor kamar tipe ' . $request->suite_type . ' sudah penuh pada tanggal tersebut.'
                ]);
            }
            return response()->json([
                'available' => true, 
                'message' => 'Kamar nomor ' . $room->room_number . ' siap dialokasikan untuk Anda!'
            ]);
        }

        if (!$room) {
            $errMessage = 'Maaf, seluruh unit kamar tipe ' . $request->suite_type . ' sudah penuh dipesan.';
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $errMessage], 422)
                : redirect()->back()->withInput()->with('error', $errMessage);
        }

        $days = max(1, (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24));
        $totalPrice = $room->price * $days;

        if (Auth::check()) {
            DB::transaction(function () use ($room, $totalPrice, $checkIn, $checkOut, $guestsCount) {
                // Gunakan Query Builder langsung untuk performa transaksi Supabase yang bersih
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id'      => Auth::id(),
                    'guest_id'     => Auth::id(), 
                    'room_id'      => $room->room_id, 
                    'check_in'     => $checkIn,
                    'check_out'    => $checkOut,
                    'guests_count' => $guestsCount, // Kuota hitung tamu tersimpan dinamis
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

            return $request->wantsJson() 
                ? response()->json(['success' => true, 'redirect' => route('guest.dashboard')])
                : redirect()->route('guest.dashboard')->with('success', 'Reservasi berhasil!');
        }

        return $request->wantsJson()
            ? response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Silakan login terlebih dahulu.'])
            : redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
    }

    /**
     * Halaman Dasbor Tamu (Menampilkan Data Cuaca & Booking Aktif)
     */
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
            $weatherResponse = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
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
    public function changeLanguage($locale)
{
    // Batasi hanya bahasa yang kita sediakan (misal: en = Inggris, id = Indonesia)
    if (in_array($locale, ['en', 'id'])) {
        Session::put('locale', $locale);
    }
    
    // Kembalikan user ke halaman sebelumnya dengan bahasa yang baru
    return redirect()->back();
}
}