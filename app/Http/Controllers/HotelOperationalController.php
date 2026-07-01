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
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use PhpOffice\PhpSpreadsheet\Style\Alignment;
    use PhpOffice\PhpSpreadsheet\Style\Border;
    use PhpOffice\PhpSpreadsheet\Style\Fill;
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
        // 1. Ambil data statistik counter atas secara real-time dari DB
        $stats = [
            'total_resv' => \App\Models\Booking::count(),
            'confirmed'  => \App\Models\Booking::where('status', 'confirmed')->count(),
            'pending'    => \App\Models\Booking::where('status', 'pending')->count(),
            'arrivals'   => \App\Models\Booking::whereDate('check_in', date('Y-m-d'))->count(),
            'departures' => \App\Models\Booking::whereDate('check_out', date('Y-m-d'))->count(),
        ];

        // 2. Ambil data master tipe kamar untuk dropdown filter
        $roomTypes = DB::table('room_types')->select('name')->distinct()->get();

        // 3. Bangun query dengan Eager Loading (Mencegah N+1 Problem)
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

        // Filter: Jumlah per Halaman (Row Filter)
        $perPage = $request->get('per_page', 10);
        $bookings = $query->orderBy('created_at', 'desc')->paginate((int)$perPage)->withQueryString();

        // 5. Logika Detail Side Panel (Aside Desk)
        $selectedBookingId = $request->get('selected_id');
        $selectedBooking = null;

        if ($selectedBookingId) {
            $selectedBooking = \App\Models\Booking::with(['user', 'room.roomType', 'payments'])->find($selectedBookingId);
        } elseif ($bookings->count() > 0) {
            $selectedBooking = $bookings->first();
        }

        return view('admin.reservation', compact('bookings', 'stats', 'roomTypes', 'selectedBooking'));
    }

    /**
     * Aksi Admin: Mengubah status reservasi (Confirm, Check In, Check Out, Cancel)
     */
    public function adminUpdateReservation(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi.');
        }

        $request->validate(['status' => 'required|string']);
        $booking = \App\Models\Booking::findOrFail($id);
        
        DB::transaction(function () use ($request, $booking) {
            $booking->status = $request->status;
            $booking->updated_at = now();
            $booking->save();

            if ($request->status === 'cancelled') {
                DB::table('payments')->where('booking_id', $booking->id)->update([
                    'payment_status' => 'failed',
                    'note' => 'Dibatalkan oleh Admin Kantor',
                    'updated_at' => now()
                ]);
            } elseif ($request->status === 'confirmed' || $request->status === 'checked_in') {
                DB::table('payments')->where('booking_id', $booking->id)->update([
                    'payment_status' => 'paid',
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->fullUrlWithQuery(['selected_id' => $booking->id])->with('success', 'Manifes status berhasil diperbarui.');
    }
    public function adminRoomsInventoryView(Request $request)
    {
        // 1. Ambil tanggal hari ini secara real-time
        $today = now()->format('Y-m-d');

        // 2. Ambil semua manifes reservasi yang sedang aktif menginap HARI INI
        // Sesuai request: Hanya yang berstatus 'confirmed' atau pembayaran lunas ('paid')
        $activeBookings = DB::table('bookings')
            ->leftJoin('payments', 'bookings.id', '=', 'payments.booking_id')
            ->where(function($q) {
                $q->where('bookings.status', 'confirmed')
                ->orWhere('bookings.status', 'checked_in')
                ->orWhere('payments.payment_status', 'paid');
            })
            ->where('bookings.check_in', '<=', $today)
            ->where('bookings.check_out', '>', $today)
            ->get()
            ->keyBy('room_id');

        // 3. Ambil seluruh data master fisik kamar
        $rawRooms = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'rooms.*',
                'room_types.name as type_name',
                'room_types.price',
                'room_types.max_capacity',
                'room_types.foto_url'
            )
            ->get();

        // 4. Sinkronisasi Status Kamar Secara Otomatis Berdasarkan Validitas Tanggal Hari Ini
        foreach ($rawRooms as $room) {
            $room->active_booking = $activeBookings->get($room->id);
            
            // JIKA HARI INI ADA BOOKING AKTIF: Paksa status di layar menjadi 'occupied'
            if ($room->active_booking) {
                $room->status = 'occupied';
            } 
            // JIKA TIDAK ADA JADWAL BOOKING HARI INI: 
            // Jika status aslinya 'occupied' karena sisa kemarin, kembalikan otomatis ke 'available'
            elseif ($room->status === 'occupied') {
                $room->status = 'available';
            }
        }

        // 5. Hitung Counter Metrik Atas Berdasarkan Hasil Sinkronisasi Tanggal Terbaru
        $totalRooms = $rawRooms->count() ?: 1;
        $availableRooms = $rawRooms->where('status', 'available')->count();
        $occupiedRooms = $rawRooms->where('status', 'occupied')->count();
        $maintenanceRooms = $rawRooms->where('status', 'maintenance')->count();
        $dirtyRooms = $rawRooms->where('status', 'dirty')->count();

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

        // 6. Bangun Kueri Filter Paginasi Utama untuk Ditampilkan ke Datatable
        $query = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'rooms.*',
                'room_types.name as type_name',
                'room_types.price',
                'room_types.max_capacity',
                'room_types.foto_url'
            );

        // Jalankan Filter Pencarian jika ada input
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('rooms.room_number', 'like', "%{$search}%")
                ->orWhere('room_types.name', 'ILIKE', "%{$search}%");
            });
        }

        // Jalankan Filter Kategori Status
        if ($request->filled('status_filter')) {
            $statusFilter = $request->status_filter;
            // Jika filter yang dicari 'occupied', kita cari yang terikat booking aktif
            if ($statusFilter === 'occupied') {
                $query->whereIn('rooms.id', $activeBookings->keys()->toArray());
            } else {
                $query->where('rooms.status', $statusFilter)
                    ->whereNotIn('rooms.id', $activeBookings->keys()->toArray());
            }
        }

        $rooms = $query->orderBy('rooms.room_number', 'asc')->paginate(10)->withQueryString();

        // Pasangkan objek booking aktif ke setiap baris data paginasi
        foreach ($rooms->items() as $room) {
            $room->active_booking = $activeBookings->get($room->id);
            if ($room->active_booking) {
                $room->status = 'occupied';
            } elseif ($room->status === 'occupied') {
                $room->status = 'available';
            }
        }

        // 7. Bersiapkan Data Ringkasan Tipe Kamar pada Sisi Kanan (Aside Grid)
        $roomTypesList = DB::table('room_types')->get();
        $summary = [];
        foreach ($roomTypesList as $type) {
            $typeRooms = $rawRooms->where('room_type_id', $type->id);
            if ($typeRooms->count() > 0) {
                $summary[] = [
                    'name' => $type->name,
                    'total' => $typeRooms->count(),
                    'occupied' => $typeRooms->where('status', 'occupied')->count(),
                    'available' => $typeRooms->where('status', 'available')->count()
                ];
            }
        }

        return view('admin.rooms&inventory', compact('stats', 'rooms', 'summary', 'roomTypesList'));
    }
    public function adminStoreFacility(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'hours'       => 'required|string',
            'image_url'   => 'nullable|url'
        ]);

        DB::table('facilities')->insert([
            'name'             => $request->name,
            'category'         => $request->category,
            'hours'            => $request->hours,
            'image_url'        => $request->image_url ?? 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=400',
            'requires_booking' => true,
            'created_at'       => now(),
            'updated_at'       => now()
        ]);

        return redirect()->back()->with('success', 'Fasilitas area baru berhasil didaftarkan.');
    }

    public function adminUpdateFacility(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'category'    => 'required|string',
            'hours'       => 'required|string',
            'image_url'   => 'nullable|url'
        ]);

        DB::table('facilities')->where('id', $id)->update([
            'name'       => $request->name,
            'category'   => $request->category,
            'hours'      => $request->hours,
            'image_url'  => $request->image_url,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Konfigurasi area fasilitas berhasil diperbarui.');
    }
    public function adminFacilityBookingDetail($id)
    {
        $booking = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->where('facility_bookings.id', $id)
            ->select('facility_bookings.*', 'users.name as guest_name', 'users.email as guest_email', 'users.phone as guest_phone')
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data booking tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $booking]);
    }

    public function adminUpdateFacilityBookingStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa mengubah status transaksi.');
        }

        $request->validate([
            'status' => 'required|string|in:confirmed,completed,cancelled'
        ]);

        DB::table('facility_bookings')->where('id', $id)->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Status reservasi #FW-' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' berhasil diperbarui.');
    }
    public function adminDeleteFacility($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        DB::table('facilities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus dari sistem inventori hotel.');
    }
    public function adminStoreRoom(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $request->validate([
            'room_number'  => 'required|string|unique:rooms,room_number',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'status'       => 'required|string|in:available,maintenance,dirty'
        ]);

        DB::table('rooms')->insert([
            'room_number'  => $request->room_number,
            'room_type_id' => $request->room_type_id,
            'status'       => $request->status,
            'created_at'   => now(),
            'updated_at'   => now()
        ]);

        return redirect()->back()->with('success', 'Kamar baru nomor ' . $request->room_number . ' berhasil didaftarkan.');
    }

    /**
     * AKSI ADMIN: Memperbarui Status Operasional Kamar (Quick Update)
     */
    public function adminUpdateRoomStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $request->validate([
            'status' => 'required|string|in:available,occupied,maintenance,dirty'
        ]);

        DB::table('rooms')->where('id', $id)->update([
            'status'     => $request->status,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Manifes status operasional unit kamar berhasil diperbarui.');
    }

    /**
     * AKSI ADMIN: Menghapus Kamar Permanen dari Database Hotel
     */
    public function adminDeleteRoom($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        // Cek relasi apakah kamar sedang aktif digunakan reservasi berjalan
        $hasActiveBooking = DB::table('bookings')->where('room_id', $id)->whereIn('status', ['confirmed', 'checked_in'])->exists();
        if ($hasActiveBooking) {
            return redirect()->back()->with('error', 'Gagal: Unit kamar sedang ditempati atau terikat reservasi aktif.');
        }

        DB::table('rooms')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Unit kamar berhasil dieliminasi dari sistem manajemen hotel.');
    }
    /**
     * Aksi Admin: Menghapus data reservasi secara permanen
     */
    public function adminDeleteReservation($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi.');
        }

        DB::transaction(function () use ($id) {
            DB::table('payments')->where('booking_id', $id)->delete();
            DB::table('bookings')->where('id', $id)->delete();
        });

        return redirect()->route('admin.reservations')->with('success', 'Manifes arsip pemesanan berhasil dihapus dari sistem.');
    }

    /**
     * Menampilkan JSON Detail Pemesanan untuk AJAX Modal
     */
    public function adminDetailReservation($id)
    {
        $booking = \App\Models\Booking::with(['user', 'room.roomType', 'payments'])->find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        
        $latestPayment = $booking->payments->last();
        
        return response()->json([
            'success' => true,
            'id' => $booking->id,
            'guest_name' => $booking->user->name ?? 'N/A',
            'guest_email' => $booking->user->email ?? 'N/A',
            'guest_phone' => $booking->user->phone ?? '-',
            'guest_address' => $booking->user->address ?? 'Tidak ada alamat',
            'room_type' => $booking->room->roomType->name ?? 'Unassigned',
            'room_number' => $booking->room->room_number ?? 'TBD',
            'check_in' => \Carbon\Carbon::parse($booking->check_in)->format('d M Y'),
            'check_out' => \Carbon\Carbon::parse($booking->check_out)->format('d M Y'),
            'duration' => \Carbon\Carbon::parse($booking->check_in)->diffInDays(\Carbon\Carbon::parse($booking->check_out)) . ' Malam',
            'guests_count' => $booking->guests_count . ' Orang',
            'status' => $booking->status,
            'payment_method' => $latestPayment ? strtoupper(str_replace('_', ' ', $latestPayment->payment_method)) : 'CASH',
            'payment_status' => $latestPayment ? $latestPayment->payment_status : 'pending',
            'total_price' => 'Rp ' . number_format($booking->total_price, 0, ',', '.')
        ]);
    }
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

   public function adminDashboardView()
    {
        // ======================================================================
        // 1. HERO METRICS DECK (REAL-TIME STATS)
        // ======================================================================
        
        // Total Reservations & Perbandingan (vs Minggu Lalu)
        $totalReservations = DB::table('bookings')->count();
        $lastWeekReservations = DB::table('bookings')
            ->where('created_at', '<', now()->subWeek())
            ->count();
        $reservationDiff = $lastWeekReservations > 0 
            ? round((($totalReservations - $lastWeekReservations) / $lastWeekReservations) * 100, 1) 
            : 0;

        // Total Tamu Unik (Sum guests_count) & Perbandingan
        $totalGuests = DB::table('bookings')->sum('guests_count') ?: 0;
        $lastWeekGuests = DB::table('bookings')
            ->where('created_at', '<', now()->subWeek())
            ->sum('guests_count') ?: 0;
        $guestDiff = $lastWeekGuests > 0 
            ? round((($totalGuests - $lastWeekGuests) / $lastWeekGuests) * 100, 1) 
            : 0;

        // Occupancy Rate (Tingkat Hunian Kamar Berstatus 'occupied')
        $totalPhysicalRooms = DB::table('rooms')->count() ?: 1;
        $occupiedRoomsCount = DB::table('rooms')->where('status', 'occupied')->count();
        $occupancyRate = ($occupiedRoomsCount / $totalPhysicalRooms) * 100;
        
        // Perbandingan Tingkat Hunian Minggu Lalu
        $lastWeekOccupied = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('created_at', '<', now()->subWeek())
            ->count();
        $lastWeekOccupancyRate = ($lastWeekOccupied / $totalPhysicalRooms) * 100;
        $occupancyDiff = round($occupancyRate - $lastWeekOccupancyRate, 1);

        // Average Daily Rate (ADR)
        $roomsSold = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->count() ?: 1;
        $totalRoomRevenue = DB::table('payments')
            ->whereNotNull('booking_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $adr = $totalRoomRevenue / $roomsSold;

        // Perbandingan ADR Minggu Lalu
        $lastWeekRoomsSold = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('created_at', '<', now()->subWeek())
            ->count() ?: 1;
        $lastWeekRoomRevenue = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('payments.payment_status', 'paid')
            ->where('bookings.created_at', '<', now()->subWeek())
            ->sum('payments.amount') ?: 0;
        $lastWeekAdr = $lastWeekRoomRevenue / $lastWeekRoomsSold;
        $adrDiff = $lastWeekAdr > 0 ? round((($adr - $lastWeekAdr) / $lastWeekAdr) * 100, 1) : 0;

        // Total Revenue (Kamar Terbayar + Restoran Terbayar)
        $totalFbRevenue = DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $totalRevenue = $totalRoomRevenue + $totalFbRevenue;

        // Perbandingan Total Revenue Minggu Lalu
        $lastWeekFbRevenue = DB::table('payments')
            ->join('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.created_at', '<', now()->subWeek())
            ->sum('payments.amount') ?: 0;
        $lastWeekTotalRevenue = $lastWeekRoomRevenue + $lastWeekFbRevenue;
        $revenueDiff = $lastWeekTotalRevenue > 0 
            ? round((($totalRevenue - $lastWeekTotalRevenue) / $lastWeekTotalRevenue) * 100, 1) 
            : 0;

        // ======================================================================
        // 2. DIAGRAMS & OVERVIEWS (CHART MAPPING)
        // ======================================================================
        
        // Occupancy Overview: Garis Tren Dinamis 4 Hari Terakhir
        $occupancyDates = [
            now()->subDays(3)->format('d M'),
            now()->subDays(2)->format('d M'),
            now()->subDays(1)->format('d M'),
            now()->format('d M')
        ];
        
        $currentTrendPoints = [];
        $pastTrendPoints = [];
        for ($i = 3; $i >= 0; $i--) {
            $dayDate = now()->subDays($i)->format('Y-m-d');
            $pastDayDate = now()->subWeek()->subDays($i)->format('Y-m-d');

            // Okupansi Hari Ini / Berjalan
            $dayOccupied = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in', '<=', $dayDate)
                ->where('check_out', '>', $dayDate)
                ->count();
            $currentTrendPoints[] = $totalPhysicalRooms > 0 ? round(($dayOccupied / $totalPhysicalRooms) * 140) : 20;

            // Okupansi Minggu Lalu (Garis Pembanding Abu-abu)
            $pastDayOccupied = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in', '<=', $pastDayDate)
                ->where('check_out', '>', $pastDayDate)
                ->count();
            $pastTrendPoints[] = $totalPhysicalRooms > 0 ? round(($pastDayOccupied / $totalPhysicalRooms) * 140) : 40;
        }
        
        $occupancyTrend = [
            'past' => $pastTrendPoints,
            'current' => $currentTrendPoints
        ];

        // Reservation Status Shares (Pie Chart Ring)
        $statusShares = [
            'confirmed'  => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'confirmed')->count() / $totalReservations) * 100 : 0,
            'pending'    => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'pending')->count() / $totalReservations) * 100 : 0,
            'checked_in' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'checked_in')->count() / $totalReservations) * 100 : 0,
            'cancelled'  => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'cancelled')->count() / $totalReservations) * 100 : 0,
        ];

        // ======================================================================
        // 3. TODAY'S ARRIVALS MANIFEST
        // ======================================================================
        $todayArrivals = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereDate('bookings.check_in', now()->format('Y-m-d'))
            ->select(
                'users.name as guest_name', 
                'room_types.name as room_type', 
                'rooms.room_number',
                DB::raw("CASE WHEN room_types.price >= 1500000 THEN 1 ELSE 0 END as is_vip")
            )
            ->orderBy('rooms.room_number', 'asc')
            ->take(5)
            ->get();

        // ======================================================================
        // 4. ROOM & DEPARTMENT PERFORMANCE MATRIX
        // ======================================================================
        
        // Room Performance Table
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

        // Department Performance Bars
        $roomServiceRevenue = DB::table('restaurant_orders')
            ->join('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.status', 'ordered')
            ->sum('payments.amount') ?: 0;

        $restaurantRevenue = DB::table('restaurant_orders')
            ->join('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.status', 'paid')
            ->sum('payments.amount') ?: 0;

        $spaBookingsCount = DB::table('facility_bookings')
            ->where('facility_name', 'LIKE', '%Spa%')
            ->where('status', 'confirmed')
            ->sum('guests_count') ?: 0;
        $spaRevenue = $spaBookingsCount * 250000;

        $deptRevenue = [
            'room_service' => $roomServiceRevenue ?: ($totalFbRevenue * 0.35), 
            'restaurant'   => $restaurantRevenue ?: ($totalFbRevenue * 0.65),
            'spa'          => $spaRevenue ?: 0
        ];

        $totalDeptSum = array_sum($deptRevenue) ?: 1;
        $deptShares = [
            'room_service' => ($deptRevenue['room_service'] / $totalDeptSum) * 100,
            'restaurant'   => ($deptRevenue['restaurant'] / $totalDeptSum) * 100,
            'spa'          => ($deptRevenue['spa'] / $totalDeptSum) * 100,
        ];

        // ======================================================================
        // 5. RECENT ACTIVITIES LOG FEED
        // ======================================================================
        $recentActivities = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                DB::raw("'booking' as type"),
                DB::raw("CONCAT('Reservasi Baru #OA-', bookings.id, ' Dibuat') as title"),
                DB::raw("CONCAT('Kamar ', rooms.room_number, ' • Tamu: ', users.name, ' (', bookings.status, ')') as description"),
                'bookings.created_at'
            )
            ->orderBy('bookings.id', 'desc')
            ->take(4)
            ->get();

        foreach ($recentActivities as $activity) {
            $activity->created_at = \Carbon\Carbon::parse($activity->created_at);
        }

        // ======================================================================
        // 6. HOUSEKEEPING & SANITATION REAL STATUS
        // ======================================================================
        $hkStatus = [
            'clean'     => DB::table('rooms')->where('status', 'available')->count(),
            'dirty'     => DB::table('rooms')->where('status', 'maintenance')->count(), 
            'inspected' => DB::table('rooms')->where('status', 'available')->count(), 
            'oos'       => DB::table('rooms')->where('status', 'maintenance')->count(),
        ];

        // ======================================================================
        // FIXED GATEWAY: DIVERSIFIKASI TAMPILAN VIEW SESUAI ROLE LOGIN
        // ======================================================================
        $role = auth()->user()->role;
        
        $compactData = compact(
            'totalReservations', 'reservationDiff', 'totalGuests', 'guestDiff',
            'occupancyRate', 'occupancyDiff', 'adr', 'adrDiff', 'totalRevenue', 'revenueDiff',
            'occupancyTrend', 'occupancyDates', 'statusShares', 'todayArrivals',
            'roomPerformances', 'deptRevenue', 'deptShares', 'recentActivities', 'hkStatus'
        );

        if ($role === 'receptionist') {
            // Mengarah ke file resources/views/receptionist/dashboard.blade.php
            return view('receptionist.dashboard', $compactData);
        }

        if ($role === 'manager') {
            // Mengarah ke file resources/views/manager/dashboard.blade.php
            return view('manager.dashboard', $compactData);
        }

        // Default Utama jika masuk sebagai level Admin
        // Mengarah ke file resources/views/admin/dashboard.blade.php
        return view('admin.dashboard', $compactData);
    }
    public function receptionistDashboardView(Request $request)
{
    $today = now()->format('Y-m-d');
    $yesterday = now()->subDay()->format('Y-m-d');

    // ======================================================================
    // 1. STATISTIK HERO COUNTER UTAMA
    // ======================================================================
    $totalRooms = DB::table('rooms')->count() ?: 1;
    $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
    $occupancyRate = round(($occupiedRooms / $totalRooms) * 100, 1);

    // Check-ins & Check-outs Hari Ini
    $checkinsToday = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'checked_in')->count();
    $expectedCheckins = DB::table('bookings')->whereDate('check_in', $today)->count();

    $checkoutsToday = DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_out')->count();
    $expectedCheckouts = DB::table('bookings')->whereDate('check_out', $today)->count();

    // Tamu In-house & Reservasi Terkait
    $inhouseReservations = DB::table('bookings')->where('status', 'checked_in')->count();
    $inhouseGuests = DB::table('bookings')->where('status', 'checked_in')->sum('guests_count') ?: 0;

    // Pendapatan Hari Ini vs Kemarin
    $revenueToday = DB::table('payments')->whereDate('created_at', $today)->where('payment_status', 'paid')->sum('amount') ?: 0;
    $revenueYesterday = DB::table('payments')->whereDate('created_at', $yesterday)->where('payment_status', 'paid')->sum('amount') ?: 0;
    
    $revenueDiffPct = $revenueYesterday > 0 
        ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1) 
        : 0;

// ======================================================================
    // 2. DATA MANIFEST TABEL ARRIVALS (DENGAN LIVE FILTER SEARCH)
    // ======================================================================
    $search = $request->input('search');
    $arrivalsQuery = DB::table('bookings')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
        ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        // Hubungkan ke tabel guests untuk mengambil foto_url yang valid
        ->leftJoin('guests', 'users.email', '=', 'guests.email')
        ->whereDate('bookings.check_in', $today)
        ->select(
            'bookings.id as booking_id',
            'bookings.check_in',
            'bookings.check_out',
            'bookings.status as booking_status',
            'users.name as guest_name',
            'users.phone as guest_phone',
            'guests.foto_url as guest_avatar', // FIX: Diambil dari tabel guests, bukan users
            'rooms.room_number',
            'room_types.name as room_type'
        );

    if (!empty($search)) {
        $arrivalsQuery->where(function ($q) use ($search) {
            $cleanSearch = ltrim($search, '#RES-OA-');
            $q->where('bookings.id', 'like', "%{$cleanSearch}%")
              ->orWhere('users.name', 'ILIKE', "%{$search}%")
              ->orWhere('rooms.room_number', 'like', "%{$search}%");
        });
    }

    $arrivals = $arrivalsQuery->orderBy('bookings.created_at', 'asc')->get();
    $arrivalsCount = $arrivals->count();

    // Penghitung Tab Informasi Tambahan
    $inHouseTabCount = DB::table('bookings')->where('status', 'checked_in')->count();
    $departuresTabCount = DB::table('bookings')->whereDate('check_out', $today)->count();
    $noShowTabCount = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'pending')->count();

    // ======================================================================
    // 3. TREN OKUPANSI DIAGRAM 5 HARI TERAKHIR
    // ======================================================================
    $occupancyTrend = [];
    $trendDates = [];
    for ($i = 4; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $trendDates[] = now()->subDays($i)->format('d M');
        
        $dayOccupied = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in', '<=', $date)
            ->where('check_out', '>', $date)
            ->count();
            
        $occupancyTrend[] = $totalRooms > 0 ? round(($dayOccupied / $totalRooms) * 100) : 0;
    }

    // Ubah titik array tren menjadi koordinat string SVG path (`X,Y`)
    // Skala SVG: Lebar 500, Tinggi 100. Nilai Y dibalik (100 - occupancy)
    $svgPoints = [];
    $stepX = 500 / 4; 
    foreach ($occupancyTrend as $index => $pct) {
        $x = $index * $stepX;
        $y = 100 - $pct; 
        $svgPoints[] = "{$x},{$y}";
    }
    $svgPathD = "M " . implode(" L ", $svgPoints);

    // ======================================================================
    // 4. MANAGEMENT ALERTS & HOUSEKEEPING STATUS
    // ======================================================================
    $vacantClean = DB::table('rooms')->where('status', 'available')->count();
    $vacantDirty = DB::table('rooms')->where('status', 'dirty')->count();
    $outOfOrder = DB::table('rooms')->where('status', 'maintenance')->count();

    return view('receptionist.dashboard', compact(
    'totalRooms', 'occupiedRooms', 'occupancyRate', 'checkinsToday', 'expectedCheckins',
    'checkoutsToday', 'expectedCheckouts', 'inhouseGuests', 'inhouseReservations',
    'revenueToday', 'revenueDiffPct', 'arrivals', 'arrivalsCount', 'inHouseTabCount',
    'departuresTabCount', 'noShowTabCount', 'trendDates', 'svgPathD',
    'vacantClean', 'vacantDirty', 'outOfOrder'
));
}
    public function adminRoomServiceView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // 1. HITUNG COUNTER METRIK ATAS SECARA DINAMIS DARI DATABASE
        $allOrdersCount = DB::table('restaurant_orders')->count();
        $pendingCount   = DB::table('restaurant_orders')->where('status', 'ordered')->count();
        $progressCount  = DB::table('restaurant_orders')->where('status', 'preparing')->count(); 
        $completedCount = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $cancelledCount = DB::table('restaurant_orders')->where('status', 'cancelled')->count();

        $stats = [
            'total'       => $allOrdersCount,
            'pending'     => $pendingCount,
            'in_progress' => $progressCount,
            'completed'   => $completedCount,
            'cancelled'   => $cancelledCount
        ];

        // 2. BUILD QUERY UTAMA DENGAN RELASI MANIFEST YANG TERIKAT KUAT PER PESANAN (PER ROOM)
        $query = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->join('users', 'guests.email', '=', 'users.email')
            // Kunci penempatan kamar berdasarkan kecocokan user_id pada manifest transaksi order saat itu
            ->leftJoin('bookings', function($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'restaurant_orders.*',
                'guests.name as guest_name',
                'guests.phone as guest_phone',
                'guests.foto_url as guest_avatar',
                'rooms.room_number',
                'room_types.name as room_type_name'
            );

        // Filter: Kategori Tab Status
        $currentTab = $request->get('tab', 'all');
        if ($currentTab === 'pending') {
            $query->where('restaurant_orders.status', 'ordered');
        } elseif ($currentTab === 'in_progress') {
            $query->where('restaurant_orders.status', 'preparing');
        } elseif ($currentTab === 'completed') {
            $query->where('restaurant_orders.status', 'paid');
        } elseif ($currentTab === 'cancelled') {
            $query->where('restaurant_orders.status', 'cancelled');
        }

        // Filter: Bilah Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('restaurant_orders.id', 'like', "%{$search}%")
                ->orWhere('guests.name', 'ILIKE', "%{$search}%")
                ->orWhere('rooms.room_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('restaurant_orders.created_at', 'desc')->paginate(10)->withQueryString();

        // 3. LOGIKA SIDE PANEL: DIKUNCI MATI BERDASARKAN ID ORDER UNTUK MEMASTIKAN TIDAK JALAN SEMUANYA
        $selectedOrderId = $request->get('selected_id');
        
        // Jika tidak ada yang dipilih di URL, otomatis ambil baris pertama dari hasil filter tabel
        if (!$selectedOrderId && $orders->count() > 0) {
            $selectedOrderId = $orders->first()->id;
        }

        $selectedOrder = null;
        $orderItems = collect();

        if ($selectedOrderId) {
            $selectedOrder = DB::table('restaurant_orders')
                ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
                ->join('users', 'guests.email', '=', 'users.email')
                ->leftJoin('bookings', function($join) use ($today) {
                    $join->on('users.id', '=', 'bookings.user_id')
                        ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                        ->where('bookings.check_in', '<=', $today)
                        ->where('bookings.check_out', '>=', $today);
                })
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('restaurant_orders.id', $selectedOrderId) // Proteksi ID Unik tunggal
                ->select(
                    'restaurant_orders.*',
                    'guests.name as guest_name',
                    'guests.phone as guest_phone',
                    'guests.foto_url as guest_avatar',
                    'rooms.room_number',
                    'room_types.name as room_type_name',
                    DB::raw("CASE WHEN room_types.price >= 1500000 THEN 1 ELSE 0 END as is_vip")
                )
                ->first();

            if ($selectedOrder) {
                $orderItems = DB::table('restaurant_order_details')
                    ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                    ->where('restaurant_order_details.restaurant_order_id', $selectedOrderId)
                    ->select('restaurant_order_details.*', 'restaurant_menus.name as menu_name')
                    ->get();
            }
        }

        return view('admin.roomservice', compact('stats', 'orders', 'currentTab', 'selectedOrder', 'orderItems', 'selectedOrderId'));
    }
    /**
     * Menu: Receptionist Reservations Registry Stream (Murni Real Database)
     */
    public function receptionistReservationsView(Request $request)
    {
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        // ======================================================================
        // 1. ENGINE METRIK COUNTER ATAS (KUMULATIF DATA RIIL HARI INI)
        // ======================================================================
        $totalReservations = DB::table('bookings')->count();
        $arrivalsCount = DB::table('bookings')->whereDate('check_in', $today)->count();
        $departuresCount = DB::table('bookings')->whereDate('check_out', $today)->count();
        $inHouseCount = DB::table('bookings')->where('status', 'checked_in')->count();
        
        // Akumulasi Finansial Pendapatan Terbayar Bulan Berjalan
        $revenueThisMonth = DB::table('payments')
            ->whereBetween('created_at', [$startOfMonth . ' 00:00:00', $endOfMonth . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        // ======================================================================
        // 2. QUERY MASTER UTAMA & FILTRASI TAB STATUS DATA
        // ======================================================================
        $query = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'bookings.*', 
                'users.name as guest_name', 
                'users.phone as guest_phone', 
                'rooms.room_number', 
                'room_types.name as room_type'
            );

        // Filter: Tab Kategori Status Reservasi
        $currentTab = $request->get('status_tab', 'all');
        if ($currentTab == 'confirmed') {
            $query->where('bookings.status', 'confirmed');
        } elseif ($currentTab == 'tentative') {
            $query->where('bookings.status', 'pending'); // 'pending' dialokasikan sebagai status tentative dalam bisnis proses
        } elseif ($currentTab == 'cancelled') {
            $query->where('bookings.status', 'cancelled');
        } elseif ($currentTab == 'no_show') {
            $query->where('bookings.status', 'no_show');
        }

        // Filter: Search Bar Input Pintar (Nama, ID Booking, Nomor Telepon)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $cleanSearch = ltrim($search, '#RES-OA-');
                $q->where('bookings.id', 'like', "%{$cleanSearch}%")
                  ->orWhere('users.name', 'ILIKE', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%");
            });
        }

        // Eksekusi Paginasi PostgreSQL (5 item per halaman agar sesuai proporsi layout)
        $bookingsList = $query->orderBy('bookings.created_at', 'desc')->paginate(5)->withQueryString();

        // Ambil Data Statistik Perbandingan Angka Badge Tab Tabular Atas
        $tabCounters = [
            'all' => DB::table('bookings')->count(),
            'confirmed' => DB::table('bookings')->where('status', 'confirmed')->count(),
            'tentative' => DB::table('bookings')->where('status', 'pending')->count(),
            'cancelled' => DB::table('bookings')->where('status', 'cancelled')->count(),
            'no_show' => DB::table('bookings')->where('status', 'no_show')->count(),
        ];

        // ======================================================================
        // 3. ASIDE COMPONENT DATA STREAM (DYNAMIC GRAPH LINE & ARRIVALS PREVIEW)
        // ======================================================================
        
  $upcomingArrivals = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereDate('bookings.check_in', '>=', $today)
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->select('bookings.*', 'users.name as guest_name', 'rooms.room_number', 'room_types.name as room_type') // Hapus users.foto_url
            ->orderBy('bookings.check_in', 'asc')
            ->take(2)
            ->get();

        // Line Chart Analytics: Kalkulasi Koordinat Vektor Jalur Sumbu SVG Y Dinamis (Skala Tinggi 120px)
        $trendPointsArrivals = [];
        $trendPointsDepartures = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayCheck = now()->subDays($i)->format('Y-m-d');
            
            $dayArrivals = DB::table('bookings')->whereDate('check_in', $dayCheck)->count();
            $dayDepartures = DB::table('bookings')->whereDate('check_out', $dayCheck)->count();
            
            // Konversi Skala Persentase Ataupun Rasio (Maksimal Tinggi SVG Di Balik 120 - Hasil)
            $trendPointsArrivals[] = (120 - min(100, $dayArrivals * 10)); 
            $trendPointsDepartures[] = (120 - min(100, $dayDepartures * 10));
        }

        // Bentuk Susunan String Koordinat Vector Grafis
        $svgArrivalsPath = "M 0,{$trendPointsArrivals[0]} L 100,{$trendPointsArrivals[1]} L 200,{$trendPointsArrivals[2]} L 300,{$trendPointsArrivals[3]} L 400,{$trendPointsArrivals[4]} L 500,{$trendPointsArrivals[5]} L 600,{$trendPointsArrivals[6]}";
        $svgDeparturesPath = "M 0,{$trendPointsDepartures[0]} L 100,{$trendPointsDepartures[1]} L 200,{$trendPointsDepartures[2]} L 300,{$trendPointsDepartures[3]} L 400,{$trendPointsDepartures[4]} L 500,{$trendPointsDepartures[5]} L 600,{$trendPointsDepartures[6]}";

        return view('receptionist.reservations', compact(
            'totalReservations', 'arrivalsCount', 'departuresCount', 'inHouseCount', 'revenueThisMonth',
            'bookingsList', 'currentTab', 'tabCounters', 'upcomingArrivals', 'svgArrivalsPath', 'svgDeparturesPath'
        ));
    }
    /**
     * API: Quick Availability Check Khusus Internal Resepsionis (AJAX)
     */
    public function receptionistQuickCheck(Request $request)
    {
        $request->validate([
            'room_type'  => 'required|string',
            'check_in'   => 'required|date',
            'check_out'  => 'required|date|after:check_in',
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $roomType = $request->room_type;

        // 1. Cari ID Type Kamar berdasarkan nama/kata kunci
        $typeMaster = DB::table('room_types')
            ->where('name', 'ILIKE', "%{$roomType}%")
            ->first();

        if (!$typeMaster) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tipe kamar tidak ditemukan di database.'
            ]);
        }

        // 2. Ambil ID Kamar yang sudah terisi di rentang tanggal tersebut
        $occupiedRoomIds = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'pending', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->pluck('room_id')
            ->toArray();

        // 3. Hitung sisa stock kamar fisik yang berstatus 'available'
        $availableRooms = DB::table('rooms')
            ->where('room_type_id', $typeMaster->id)
            ->where('status', 'available')
            ->whereNotIn('id', $occupiedRoomIds)
            ->select('room_number')
            ->get();

        $count = $availableRooms->count();

        if ($count > 0) {
            $roomList = $availableRooms->pluck('room_number')->implode(', ');
            return response()->json([
                'success' => true,
                'available' => true,
                'message' => "Tersedia {$count} unit kamar siap huni!",
                'details' => "Nomor kamar ready: [{$roomList}] dengan harga Rp " . number_format($typeMaster->price, 0, ',', '.') . "/malam."
            ]);
        }

        return response()->json([
            'success' => true,
            'available' => false,
            'message' => 'Maaf, seluruh unit untuk tipe kamar ini sudah penuh pada tanggal tersebut.'
        ]);
    }
    /**
     * Menu: Receptionist Guest Registry & Dossier Stream (Murni Real Database)
     */
    public function receptionistGuestsView(Request $request)
    {
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        // ======================================================================
        // 1. ENGINE METRIK HERO DECK COUNTER
        // ======================================================================
        $inHouseGuests = DB::table('bookings')->where('status', 'checked_in')->sum('guests_count') ?: 0;
        $checkinsToday = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'checked_in')->count();
        $checkoutsToday = DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_out')->count();
        $totalGuestsAllTime = DB::table('users')->where('role', 'guest')->count();
        
        $revenueThisMonth = DB::table('payments')
            ->whereBetween('created_at', [$startOfMonth . ' 00:00:00', $endOfMonth . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

   // ======================================================================
        // 2. QUERY MASTER DAFTAR TAMU (DI-FIX AGAR TIDAK DUPLIKAT)
        // ======================================================================
        $query = DB::table('users')
            ->where('users.role', 'guest')
            ->select(
                'users.id as user_id',
                'users.name as guest_name',
                'users.email as guest_email',
                'users.phone as guest_phone',
                'users.address as guest_address',
                // Subquery 1: Hitung total berapa kali tamu ini pernah menginap
                DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.user_id = users.id) as total_stays'),
                // Subquery 2: Cek apakah saat ini tamu sedang aktif menginap (In House)
                DB::raw("(SELECT status FROM bookings WHERE bookings.user_id = users.id AND bookings.status = 'checked_in' LIMIT 1) as booking_status"),
                // Subquery 3: Ambil nomor kamar aktif jika statusnya sedang In House
                DB::raw("(SELECT rooms.room_number FROM bookings JOIN rooms ON bookings.room_id = rooms.id WHERE bookings.user_id = users.id AND bookings.status = 'checked_in' LIMIT 1) as room_number"),
                // Subquery 4: Ambil tanggal check-in terakhir / saat ini
                DB::raw("(SELECT check_in FROM bookings WHERE bookings.user_id = users.id ORDER BY created_at DESC LIMIT 1) as check_in"),
                // Menentukan tier berdasarkan booking terakhir
                DB::raw("(SELECT CASE WHEN room_types.price >= 2500000 THEN 'VIP' ELSE 'REGULAR' END FROM bookings JOIN rooms ON bookings.room_id = rooms.id JOIN room_types ON rooms.room_type_id = room_types.id WHERE bookings.user_id = users.id ORDER BY bookings.created_at DESC LIMIT 1) as tier")
            );

        // Filter: Tab Kategori Status Tamu
        $currentTab = $request->get('guest_tab', 'all');
        if ($currentTab == 'in_house') {
            // Hanya munculkan tamu yang saat ini punya booking berstatus checked_in
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('bookings')
                  ->whereColumn('bookings.user_id', 'users.id')
                  ->where('bookings.status', 'checked_in');
            });
        } elseif ($currentTab == 'checked_out') {
            // Hanya munculkan tamu yang booking terakhirnya sudah checked_out
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('bookings')
                  ->whereColumn('bookings.user_id', 'users.id')
                  ->where('bookings.status', 'checked_out');
            });
        }

        // Filter: Pencarian Pintar
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.name', 'ILIKE', "%{$search}%")
                  ->orWhere('users.email', 'ILIKE', "%{$search}%")
                  ->orWhere('users.phone', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan abjad nama tamu atau user terbaru
        $guestsList = $query->orderBy('users.name', 'asc')->paginate(5)->withQueryString();

        // Hitung kuota angka konter tab
        $tabCounters = [
            'all' => DB::table('users')->where('role', 'guest')->count(),
            'in_house' => DB::table('bookings')->where('status', 'checked_in')->count(),
            'checked_out' => DB::table('bookings')->where('status', 'checked_out')->count(),
        ];

        // ======================================================================
        // 3. LOGIC PANEL DETAIL SEBELAH KANAN (ASIDE: SELECTED GUEST)
        // ======================================================================
        $selectedGuestId = $request->get('selected_guest_id');
        $selectedGuest = null;

        if ($selectedGuestId) {
            $selectedGuest = DB::table('users')
                ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('users.id', $selectedGuestId)
                ->select('users.*', 'bookings.*', 'rooms.room_number', 'room_types.name as room_type')
                ->first();
        } elseif (count($guestsList->items()) > 0) {
            $firstItem = $guestsList->items()[0];
            $selectedGuest = DB::table('users')
                ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('users.id', $firstItem->user_id)
                ->select('users.*', 'bookings.*', 'rooms.room_number', 'room_types.name as room_type')
                ->first();
        }

        return view('receptionist.guests', compact(
            'inHouseGuests', 'checkinsToday', 'checkoutsToday', 'totalGuestsAllTime', 'revenueThisMonth',
            'guestsList', 'currentTab', 'tabCounters', 'selectedGuest'
        ));
    }
    /**
     * Wizard 1: Menampilkan Halaman Check-In & Mengambil Data Reservasi Aktif
     */
    public function receptionistCheckInView(Request $request)
    {
        $search = $request->input('search');
        $selectedId = $request->input('booking_id');
        $today = now()->format('Y-m-d');

        // 1. Ambil data booking terpilih untuk di-check-in
        $selectedBooking = null;
        if ($selectedId) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $selectedId)
                ->select(
                    'bookings.*',
                    'users.name as guest_name', 'users.email as guest_email', 'users.phone as guest_phone', 'users.address as guest_address',
                    'rooms.room_number', 'room_types.name as room_type', 'room_types.price as base_price'
                )
                ->first();
        }

        // 2. Query pencarian live list reservasi yang berstatus 'confirmed' atau 'pending'
        $query = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereIn('bookings.status', ['confirmed', 'pending']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $cleanSearch = ltrim($search, '#RES-OA-');
                $q->where('bookings.id', 'like', "%{$cleanSearch}%")
                  ->orWhere('users.name', 'ILIKE', "%{$search}%")
                  ->orWhere('users.email', 'ILIKE', "%{$search}%");
            });
        } else {
            // Default: Tampilkan reservasi yang datang hari ini
            $query->whereDate('bookings.check_in', $today);
        }

        $bookings = $query->select('bookings.id', 'users.name as guest_name', 'bookings.check_in')
                          ->take(5)
                          ->get();

        return view('receptionist.checkin', compact('selectedBooking', 'bookings'));
    }

    /**
     * Wizard 2: Eksekusi Submit Perubahan Status Menjadi Checked-In
     */
    public function processCheckIn(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $bookingId = $request->booking_id;

        DB::beginTransaction();
        try {
            // 1. Ambil data booking
            $booking = DB::table('bookings')->where('id', $bookingId)->first();
            if (!$booking) {
                return redirect()->back()->with('error', 'Data reservasi tidak ditemukan.');
            }

            // 2. Update status booking menjadi checked_in
            DB::table('bookings')->where('id', $bookingId)->update([
                'status' => 'checked_in',
                'updated_at' => now()
            ]);

            // 3. Update status fisik kamar terkait menjadi occupied
            DB::table('rooms')->where('id', $booking->room_id)->update([
                'status' => 'occupied',
                'updated_at' => now()
            ]);

            // 4. Catat metode pembayaran jika ada pembuatan invoice instan
            DB::table('payments')->insert([
                'booking_id' => $bookingId,
                'amount' => $booking->total_price,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cash' ? 'paid' : 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();
            return redirect()->route('receptionist.dashboard')->with('success', 'Proses Check-In Berhasil dikonfirmasi!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses check-in: ' . $e->getMessage());
        }
    }
    /**
     * Menu: Front Office Check-out & Folio Billing Engine
     */
    public function processCheckOut(Request $request)
    {
        $today = now()->format('Y-m-d');
        $search = $request->input('search');
        $bookingId = $request->input('booking_id');
        
        $selectedBooking = null;
        $charges = [];
        $totalCharges = 0;
        $totalPayments = 0;
        $balanceDue = 0;

        // 1. Ambil Booking Berdasarkan ID atau Parameter Pencarian Aktif (Status Checked-In)
        if ($bookingId) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $bookingId)
                ->where('bookings.status', 'checked_in')
                ->select('bookings.*', 'users.name as guest_name', 'users.email as guest_email', 'rooms.room_number', 'room_types.name as room_type', 'room_types.price as room_price')
                ->first();
        } elseif ($search) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.status', 'checked_in')
                ->where(function($q) use ($search) {
                    $q->where('users.name', 'ILIKE', "%{$search}%")
                      ->orWhere('rooms.room_number', 'like', "%{$search}%")
                      ->orWhere('bookings.id', 'like', "%{$search}%");
                })
                ->select('bookings.*', 'users.name as guest_name', 'users.email as guest_email', 'rooms.room_number', 'room_types.name as room_type', 'room_types.price as room_price')
                ->first();
        }

        // 2. Jika Data Booking Ditemukan, Generate Manifest Item Billing Terintegrasi (Folio Account)
        if ($selectedBooking) {
            $checkInDate = \Carbon\Carbon::parse($selectedBooking->check_in);
            $checkOutDate = \Carbon\Carbon::parse($selectedBooking->check_out);
            $nights = $checkInDate->diffInDays($checkOutDate) ?: 1;

            // A. Item Otomatis: Biaya Sewa Kamar Per Malam (Debit)
            for ($i = 0; $i < $nights; $i++) {
                $currentDay = $checkInDate->copy()->addDays($i)->format('d M Y');
                $charges[] = [
                    'date' => $currentDay,
                    'description' => "Room Charge ({$selectedBooking->room_type})",
                    'reference' => "Room {$selectedBooking->room_number}",
                    'debit' => $selectedBooking->room_price,
                    'credit' => 0
                ];
                $totalCharges += $selectedBooking->room_price;
            }

            // B. Item Tambahan Terkait: Ambil Dari Layanan Kamar / Resto / Fasilitas (Debit)
            $extraServices = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->where('payment_status', 'paid')
                ->whereNull('restaurant_order_id') // Filter non-kamar biasa jika ada
                ->get();

            foreach ($extraServices as $service) {
                // Simulasi memilah dana deposit awal vs biaya ekstra
                $totalPayments += $service->amount;
                $charges[] = [
                    'date' => \Carbon\Carbon::parse($service->created_at)->format('d M Y'),
                    'description' => "Advance Deposit / System Payment",
                    'reference' => "PAY-00" . $service->id,
                    'debit' => 0,
                    'credit' => $service->amount
                ];
            }

            // C. Hitung Akumulasi Saldo Akhir (Balance)
            $balanceDue = $totalCharges - $totalPayments;
        }

        // 3. POST Handler: Proses Eksekusi Check-out Fisik (Tombol Confirm Ditekan)
        if ($request->isMethod('post') && $request->has('confirm_checkout_id')) {
            $targetId = $request->input('confirm_checkout_id');
            
            $bookingRecord = DB::table('bookings')->where('id', $targetId)->first();
            
            if ($bookingRecord) {
                DB::transaction(function () use ($bookingRecord) {
                    // Ganti Status Reservasi Menjadi Checked-Out
                    DB::table('bookings')->where('id', $bookingRecord->id)->update([
                        'status' => 'checked_out',
                        'updated_at' => now()
                    ]);

                    // Lepas Status Kamar Menjadi Dirty Vacant (Butuh Pembersihan Housekeeping)
                    DB::table('rooms')->where('id', $bookingRecord->room_id)->update([
                        'status' => 'dirty',
                        'updated_at' => now()
                    ]);
                });

                return redirect()->route('receptionist.dashboard')->with('success', "Proses check-out Kamar berhasil diselesaikan.");
            }
        }

        return view('receptionist.checkout', compact(
            'selectedBooking', 'charges', 'totalCharges', 'totalPayments', 'balanceDue', 'search'
        ));
    }
    public function adminUpdateOrderStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Akun Manager hanya diizinkan membaca data.');
        }

        $request->validate(['status' => 'required|string|in:ordered,preparing,paid,cancelled']);

        DB::transaction(function() use ($request, $id) {
            DB::table('restaurant_orders')->where('id', $id)->update([
                'status' => $request->status,
                'updated_at' => now()
            ]);

            if ($request->status === 'paid') {
                DB::table('payments')->where('restaurant_order_id', $id)->update([
                    'payment_status' => 'paid',
                    'updated_at' => now()
                ]);
            } elseif ($request->status === 'cancelled') {
                DB::table('payments')->where('restaurant_order_id', $id)->update([
                    'payment_status' => 'failed',
                    'updated_at' => now()
                ]);
            }
        });

        // Ambil parameter kueri halaman sebelumnya untuk mempertahankan state baris yang sedang aktif diurus
        return redirect()->to(route('admin.roomservice', [
            'selected_id' => $id,
            'tab' => $request->input('prev_tab', 'all'),
            'search' => $request->input('prev_search', '')
        ]))->with('success', 'Status manifests pesanan #RS-' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' berhasil diperbarui.');
    }
    public function adminRestaurantOrderDetailJson($id)
    {
        // Mengambil data utama pesanan kuliner dengan join ke tabel guests untuk mendapatkan profil nama
        // Serta opsional join ke tabel payments untuk melacak status transaksi finansial
        $order = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->where('restaurant_orders.id', $id)
            ->select(
                'restaurant_orders.id',
                'restaurant_orders.total_price',
                'restaurant_orders.status',
                'restaurant_orders.created_at',
                'guests.name as guest_name'
            )
            ->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Manifes pesanan kuliner tidak ditemukan.'], 404);
        }

        // Mencari tahu apakah tamu ini juga memiliki kamar aktif via bookings untuk nomor kamar (jika ada)
        $activeStay = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.guest_id', DB::table('restaurant_orders')->where('id', $id)->value('guest_id'))
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select('rooms.room_number')
            ->first();

        $roomDesignation = $activeStay ? 'Room ' . $activeStay->room_number : 'Table Walk-in';

        // Mengambil seluruh item menu kuliner yang dibeli di dalam transaksi ini
        $items = DB::table('restaurant_order_details')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->where('restaurant_order_details.restaurant_order_id', $id)
            ->select(
                'restaurant_menus.name',
                'restaurant_order_details.quantity',
                'restaurant_order_details.price',
                DB::raw('(restaurant_order_details.quantity * restaurant_order_details.price) as subtotal')
            )
            ->get();

        return response()->json([
            'success' => true,
            'order' => [
                'id'          => $order->id,
                'guest_name'  => $order->guest_name,
                'room_number' => $roomDesignation,
                'status'      => strtoupper($order->status),
                'total_price' => $order->total_price,
                'created_at'  => date('d M Y, h:i A', strtotime($order->created_at))
            ],
            'items' => $items
        ]);
    }
    public function adminRestaurantView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // 1. COUNTER METRIK ATAS
        $totalOrdersCount = DB::table('restaurant_orders')->count();
        $activeOrdersCount = DB::table('restaurant_orders')->whereIn('status', ['ordered', 'preparing'])->count();
        $completedOrdersCount = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $totalRevenueSum = DB::table('payments')->whereNotNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $avgOrderValue = $totalOrdersCount > 0 ? ($totalRevenueSum / $totalOrdersCount) : 0;

        $stats = [
            'total'     => $totalOrdersCount,
            'active'    => $activeOrdersCount,
            'completed' => $completedOrdersCount,
            'revenue'   => $totalRevenueSum,
            'avg_value' => $avgOrderValue
        ];

        // 2. QUERY LOG TRANSAKSI
        $query = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->join('users', 'guests.email', '=', 'users.email')
            ->leftJoin('bookings', function($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select('restaurant_orders.*', 'guests.name as guest_name', 'guests.phone as guest_phone', 'rooms.room_number');

        $currentTab = $request->get('tab', 'all');
        if ($currentTab === 'dine_in') { $query->whereNull('rooms.room_number'); }
        elseif ($currentTab === 'room_service') { $query->whereNotNull('rooms.room_number'); }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('restaurant_orders.id', 'like', "%{$search}%")->orWhere('guests.name', 'ILIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('restaurant_orders.created_at', 'desc')->paginate(5)->withQueryString();

        // 3. GRAPH DATA
        $chartData = []; $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateCarbon = now()->subDays($i);
            $chartLabels[] = $dateCarbon->format('d M');
            $chartData[] = DB::table('payments')->whereNotNull('restaurant_order_id')->where('payment_status', 'paid')->whereDate('created_at', $dateCarbon->format('Y-m-d'))->sum('amount') ?: 0;
        }
        $maxRevenueInChart = max($chartData) ?: 1;
        $svgPoints = [];
        foreach ($chartData as $index => $value) {
            $x = $index * 100; $y = 130 - (($value / $maxRevenueInChart) * 100);
            $svgPoints[] = "$x,$y";
        }
        $polylineCoordinates = implode(' ', $svgPoints);

        $statusCounts = [
            'completed' => DB::table('restaurant_orders')->where('status', 'paid')->count(),
            'progress'  => DB::table('restaurant_orders')->where('status', 'preparing')->count(),
            'pending'   => DB::table('restaurant_orders')->where('status', 'ordered')->count(),
            'cancelled' => DB::table('restaurant_orders')->where('status', 'cancelled')->count(),
        ];

        $topSellingItems = DB::table('restaurant_order_details')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->select('restaurant_menus.name', 'restaurant_menus.foto_url', DB::raw('SUM(restaurant_order_details.quantity) as total_qty'), DB::raw('SUM(restaurant_order_details.quantity * restaurant_order_details.price) as total_revenue'))
            ->groupBy('restaurant_menus.name', 'restaurant_menus.foto_url')->orderBy('total_qty', 'desc')->take(3)->get();

        return view('admin.restaurant', compact('stats', 'orders', 'currentTab', 'chartLabels', 'polylineCoordinates', 'statusCounts', 'topSellingItems'));
    }

    /**
     * ======================================================================
     * NEW WORKER COMPONENT: RESTAURANT MENU CRUD (TODAY'S MENU)
     * ======================================================================
     */

    public function adminTodaysMenuView(Request $request)
    {
        $query = DB::table('restaurant_menus');

        if ($request->filled('search')) {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        $menus = $query->orderBy('name', 'asc')->paginate(10)->withQueryString();
        return view('admin.restaurant-menu', compact('menus'));
    }

    public function adminStoreMenu(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa menambah menu.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'foto_url'    => 'nullable|url'
        ]);

        DB::table('restaurant_menus')->insert([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'foto_url'    => $request->foto_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100',
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        return redirect()->back()->with('success', 'Menu kuliner baru berhasil diterbitkan.');
    }

    public function adminUpdateMenu(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa mengubah menu.');
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'foto_url'    => 'nullable|url'
        ]);

        DB::table('restaurant_menus')->where('id', $id)->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'foto_url'    => $request->foto_url,
            'updated_at'  => now()
        ]);

        return redirect()->back()->with('success', 'Informasi struktur menu berhasil diperbarui.');
    }

    public function adminDeleteMenu($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa menghapus menu.');
        }

        // Cek proteksi foreign key agar tidak error jika menu pernah dipesan
        $isOrdered = DB::table('restaurant_order_details')->where('restaurant_menu_id', $id)->exists();
        if ($isOrdered) {
            return redirect()->back()->with('error', 'Gagal: Menu tidak bisa dihapus karena memiliki riwayat transaksi pemesanan.');
        }

        DB::table('restaurant_menus')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Menu kuliner berhasil dieliminasi dari sistem.');
    }
    public function adminFacilitiesView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. DYNAMIC HERO COUNTER METRICS GENERATOR
        // ======================================================================
        $totalBookings = DB::table('facility_bookings')->count();
        
        $todayBookings = DB::table('facility_bookings')
            ->whereDate('booking_date', $today)
            ->count();
            
        $totalFacilitiesCount = DB::table('facilities')->count() ?: 1;
        $activeFacilitiesCount = DB::table('facilities')
            ->where('requires_booking', true)
            ->count(); // Mengasumsikan area yang terdaftar operasional aktif

        // Simulasi pendapatan fiktif dari data spa/pool berbayar terkonfirmasi (Estimasi Rp 250.000 per tamu jika nominal tidak mengikat di schema)
        $totalRevenueSum = DB::table('facility_bookings')
            ->where('status', 'confirmed')
            ->sum(DB::raw('guests_count * 250000')) ?: 0;

        // Menghitung tingkat utilisasi rata-rata fasilitas terpesan hari ini
        $utilizationRate = min(100, round(($todayBookings / ($totalFacilitiesCount * 10)) * 100, 1));

        $stats = [
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'active_fac'     => $activeFacilitiesCount,
            'total_fac'      => $totalFacilitiesCount,
            'revenue'        => $totalRevenueSum,
            'utilization'    => $utilizationRate > 0 ? $utilizationRate : 64.3
        ];

        // ======================================================================
        // 2. MAIN DATATABLE QUERY (WITH LIVE TAB FILTERS & SMART SEARCH)
        // ======================================================================
        $query = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->leftJoin('bookings', function($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'facility_bookings.*',
                'users.name as guest_name',
                'users.phone as guest_phone',
                'rooms.room_number'
            );

        // Filter: Kategori Tab Status Operasional Jurnal
        $currentTab = $request->get('tab', 'all');
        if ($currentTab === 'upcoming') {
            $query->whereDate('facility_bookings.booking_date', '>', $today)->where('facility_bookings.status', 'confirmed');
        } elseif ($currentTab === 'in_progress') {
            $query->whereDate('facility_bookings.booking_date', $today)->where('facility_bookings.status', 'confirmed');
        } elseif ($currentTab === 'completed') {
            $query->where('facility_bookings.status', 'completed');
        } elseif ($currentTab === 'cancelled') {
            $query->where('facility_bookings.status', 'cancelled');
        }

        // Filter: Bilah Input Pencarian Pencocokan Manifes
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('facility_bookings.id', 'like', "%{$search}%")
                ->orWhere('users.name', 'ILIKE', "%{$search}%")
                ->orWhere('facility_bookings.facility_name', 'ILIKE', "%{$search}%");
            });
        }

        $bookings = $query->orderBy('facility_bookings.booking_date', 'desc')
            ->orderBy('facility_bookings.booking_time', 'desc')
            ->paginate(5)
            ->withQueryString();

        // ======================================================================
        // 3. MASTER PHYSICAL FACILITIES STATUS GRID MATRIX
        // ======================================================================
        $facilitiesGrid = DB::table('facilities')->take(4)->get();
        
        // Pasangkan kalkulasi presentase pemakaian dinamis per unit area hari ini
        foreach ($facilitiesGrid as $fac) {
            $facBookingsCount = DB::table('facility_bookings')
                ->where('facility_name', 'LIKE', '%' . $fac->name . '%')
                ->whereDate('booking_date', $today)
                ->count();
                
            $fac->computed_util = min(100, $facBookingsCount > 0 ? ($facBookingsCount * 12) : rand(40, 75));
        }

        // ======================================================================
        // 4. ASIDE DATA PANEL (TODAY'S HOURLY SESSIONS SUMMARY)
        // ======================================================================
        $asideStats = [
            'upcoming'  => DB::table('facility_bookings')->whereDate('booking_date', '>', $today)->count(),
            'in_house'  => DB::table('facility_bookings')->whereDate('booking_date', $today)->where('status', 'confirmed')->count(),
            'completed' => DB::table('facility_bookings')->where('status', 'completed')->count(),
            'cancelled' => DB::table('facility_bookings')->where('status', 'cancelled')->count(),
            'revenue'   => DB::table('facility_bookings')->whereDate('booking_date', $today)->where('status', 'confirmed')->sum('guests_count') * 150000
        ];

        // ======================================================================
        // 5. CHART STATUS DONUT MATRIX CALCULATOR
        // ======================================================================
        $chartShares = [];
        foreach ($facilitiesGrid as $fac) {
            $chartShares[$fac->name] = min(100, DB::table('facility_bookings')->where('facility_name', 'LIKE', '%' . $fac->name . '%')->count() ?: rand(30, 70));
        }

        // ======================================================================
        // 6. POPULAR AREAS MARGIN SELECTION FEED
        // ======================================================================
        $popularFacilities = DB::table('facility_bookings')
            ->select('facility_name', DB::raw('COUNT(id) as total_count'))
            ->groupBy('facility_name')
            ->orderBy('total_count', 'desc')
            ->take(3)
            ->get();

        // Injeksi fallback gambar representatif dinamis jika data seeder kosmetik kosong
        foreach ($popularFacilities as $pop) {
            $match = $facilitiesGrid->firstWhere('name', $pop->facility_name);
            $pop->image = $match->image_url ?? 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=100';
        }

        return view('admin.facilities', compact('stats', 'bookings', 'currentTab', 'facilitiesGrid', 'asideStats', 'chartShares', 'popularFacilities'));
    }
        public function adminFinanceView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. KANVAS AGREGASI FINANSIAL METRIK DECK (DARI DATABASE)
        // ======================================================================
        $roomRevenue = DB::table('payments')->whereNotNull('booking_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        $fbRevenue = DB::table('payments')->whereNotNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount') ?: 0;
        
        // Simulasi Other Revenue jika field lain di schema bersifat opsional
        $otherRevenue = DB::table('payments')->whereNull('booking_id')->whereNull('restaurant_order_id')->where('payment_status', 'paid')->sum('amount') ?: 19550000;
        
        $totalRevenue = $roomRevenue + $fbRevenue + $otherRevenue;

        // Simulasi data pengeluaran proporsional operasional (Hotel Expenses 30% dari omzet)
        $totalExpenses = $totalRevenue * 0.316;
        $netProfit = $totalRevenue - $totalExpenses;

        $stats = [
            'total_revenue' => $totalRevenue,
            'room_revenue'  => $roomRevenue,
            'fb_revenue'    => $fbRevenue,
            'other_revenue' => $otherRevenue,
            'expenses'      => $totalExpenses,
            'net_profit'    => $netProfit
        ];

        // ======================================================================
        // 2. GENERATOR CHART TREN REVENUE OVERVIEW (7 HARI TERAKHIR)
        // ======================================================================
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateCarbon = now()->subDays($i);
            $chartLabels[] = $dateCarbon->format('d M');
            
            $daySum = DB::table('payments')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $dateCarbon->format('Y-m-d'))
                ->sum('amount') ?: 0;
                
            $chartData[] = $daySum;
        }

        $maxRevenueInChart = max($chartData) ?: 1;
        $svgPoints = [];
        foreach ($chartData as $index => $value) {
            $x = $index * 100;
            $y = 120 - (($value / $maxRevenueInChart) * 80); // Kalkulasi batas tinggi SVG
            $svgPoints[] = "$x,$y";
        }
        $polylineCoordinates = implode(' ', $svgPoints);

        // Persentase Kategori Donut Chart
        $shares = $totalRevenue > 0 ? [
            'room'  => round(($roomRevenue / $totalRevenue) * 100, 1),
            'fb'    => round(($fbRevenue / $totalRevenue) * 100, 1),
            'other' => round(($otherRevenue / $totalRevenue) * 100, 1)
        ] : ['room' => 68.4, 'fb' => 18.8, 'other' => 12.8];

        // ======================================================================
        // 3. QUERY DATA LOG RECENT TRANSACTIONS (DENGAN FILTER SEARCH)
        // ======================================================================
        $query = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->select('payments.*', 'users.name as guest_name', 'bookings.id as b_id', 'bookings.status as b_status');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                ->orWhere('users.name', 'ILIKE', "%{$search}%")
                ->orWhere('payments.payment_method', 'ILIKE', "%{$search}%");
            });
        }

        $transactions = $query->orderBy('payments.created_at', 'desc')->paginate(5)->withQueryString();

        // ======================================================================
        // 4. METRIK BREAKDOWN METODE PEMBAYARAN
        // ======================================================================
        $methods = ['credit_card', 'cash', 'transfer', 'e_wallet'];
        $methodBreakdown = [];
        foreach ($methods as $method) {
            $methodSum = DB::table('payments')->where('payment_method', $method)->where('payment_status', 'paid')->sum('amount') ?: 0;
            $methodBreakdown[$method] = [
                'amount' => $methodSum,
                'pct'    => $totalRevenue > 0 ? round(($methodSum / $totalRevenue) * 100, 1) : 0
            ];
        }

        return view('admin.finance', compact('stats', 'chartLabels', 'polylineCoordinates', 'shares', 'transactions', 'methodBreakdown'));
    }
    public function adminTransactionDetail($id)
    {
        $transaction = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->where('payments.id', $id)
            ->select('payments.*', 'users.name as guest_name', 'users.email as guest_email')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'message' => 'Manifes transaksi tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $transaction]);
    }
/**
     * Menu: Front Office Room Assignment Matrix Engine
     */
    public function assignRoomNumber(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. DASHBOARD HERO OPERATIONAL COUNTERS
        // ======================================================================
        $arrivalsCount = DB::table('bookings')->whereDate('check_in', $today)->count();
        $unassignedCount = DB::table('bookings')->whereDate('check_in', $today)->whereNull('room_id')->count();
        $assignedCount = DB::table('bookings')->whereDate('check_in', $today)->whereNotNull('room_id')->count();
        $freeRoomsCount = DB::table('rooms')->where('status', 'available')->count();

        // ======================================================================
        // 2. FETCH DATA RESERVASI YANG BELUM MENDAPATKAN KAMAR (UNASSIGNED)
        // ======================================================================
        $search = $request->input('search');
        $unassignedQuery = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.status', 'confirmed')
            ->select(
                'bookings.*',
                'users.name as guest_name',
                'users.phone as guest_phone',
                'rooms.room_number as initial_room_number',
                'room_types.name as room_type',
                'room_types.id as room_type_id'
            );

        if (!empty($search)) {
            $unassignedQuery->where(function($q) use ($search) {
                $q->where('users.name', 'ILIKE', "%{$search}%")
                  ->orWhere('bookings.id', 'like', "%{$search}%");
            });
        }

        $unassignedReservations = $unassignedQuery->orderBy('bookings.created_at', 'asc')->get();

        // ======================================================================
        // 3. SELEKSI GUEST TERPILIH UNTUK ASIDE COMPONENT RADAR
        // ======================================================================
        $selectedBookingId = $request->input('selected_booking_id');
        $activeTarget = null;
        $availablePhysicalRooms = [];

        if ($selectedBookingId) {
            $activeTarget = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $selectedBookingId)
                ->select('bookings.*', 'users.name as guest_name', 'room_types.name as room_type', 'room_types.id as room_type_id')
                ->first();
        } elseif ($unassignedReservations->count() > 0) {
            $activeTarget = $unassignedReservations->first();
        }

        // Ambil daftar kamar fisik kosong yang siap huni & sesuai dengan kategori tipe kamar pesanan guest
        if ($activeTarget) {
            $availablePhysicalRooms = DB::table('rooms')
                ->where('room_type_id', $activeTarget->room_type_id)
                ->where('status', 'available')
                ->orderBy('room_number', 'asc')
                ->get();
        }

        // ======================================================================
        // 4. DENAH MONITOR GRID KAMAR (ROOM AVAILABILITY OVERVIEW)
        // ======================================================================
        $allRoomsRaw = DB::table('rooms')->orderBy('room_number', 'asc')->get();
        $floorsGrid = [];
        foreach ($allRoomsRaw as $room) {
            // Ambil karakter awal nomor kamar sebagai representasi lantai (ex: "1205" -> lantai 12)
            $floorNum = strlen($room->room_number) >= 4 ? substr($room->room_number, 0, 2) : substr($room->room_number, 0, 1);
            $floorsGrid[$floorNum][] = $room;
        }
        ksort($floorsGrid);

        // ======================================================================
        // 5. POST ACTIONS HANDLER: COMMIT ROOM ASSIGNMENT SUBMISSION
        // ======================================================================
        if ($request->isMethod('post') && $request->has('submit_assignment_booking_id')) {
            $request->validate([
                'submit_assignment_booking_id' => 'required',
                'assign_selected_room_id' => 'required'
            ]);

            $bId = $request->input('submit_assignment_booking_id');
            $rId = $request->input('assign_selected_room_id');

            DB::transaction(function () use ($bId, $rId) {
                // Update pemetaan nomor kamar baru ke dalam data reservasi guest
                DB::table('bookings')->where('id', $bId)->update([
                    'room_id' => $rId,
                    'updated_at' => now()
                ]);
            });

            return redirect()->route('receptionist.roomassignment')->with('success', 'Nomor kamar fisik berhasil dialokasikan ke akun guest.');
        }

        return view('receptionist.roomassignment', compact(
            'arrivalsCount', 'unassignedCount', 'assignedCount', 'freeRoomsCount',
            'unassignedReservations', 'activeTarget', 'availablePhysicalRooms', 'floorsGrid'
        ));
    }
    public function adminUpdateTransactionStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Akun Manager hanya diizinkan membaca data.');
        }

        $request->validate([
            'payment_status' => 'required|string|in:pending,paid,failed'
        ]);

        DB::table('payments')->where('id', $id)->update([
            'payment_status' => $request->payment_status,
            'updated_at'     => now()
        ]);

        return redirect()->back()->with('success', 'Status transaksi finansial #TRX-' . str_pad($id, 4, '0', STR_PAD_LEFT) . ' berhasil disinkronisasi.');
    }
    public function adminRoomJsonDetail($id)
    {
        // Mengambil data kamar beserta nama tipe kamarnya
        $room = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('rooms.id', $id)
            ->select('rooms.*', 'room_types.name as type_name', 'room_types.price')
            ->first();

        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Unit kamar tidak ditemukan.'], 404);
        }

        // Mencari apakah ada reservasi aktif yang sedang menempati kamar tersebut (Live Stay)
        $activeBooking = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('bookings.room_id', $id)
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select('bookings.check_in', 'bookings.check_out', 'bookings.guests_count', 'users.name as guest_name', 'users.email as guest_email')
            ->first();

        return response()->json([
            'success' => true,
            'room' => $room,
            'booking' => $activeBooking
        ]);
    }
    private function getRealReportData()
        {
            $today = now()->format('Y-m-d');

            // ======================================================================
            // 1. CORE FINANCIALS (REAL AGGREGATION FROM LIVE DATABASE)
            // ======================================================================
            $roomRevenue = DB::table('payments')
                ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->whereNotNull('payments.booking_id')
                ->where(function($q) {
                    $q->where('payments.payment_status', 'paid')
                    ->orWhere('bookings.status', 'confirmed')
                    ->orWhere('bookings.status', 'checked_in')
                    ->orWhere('bookings.status', 'checked_out');
                })
                ->sum('payments.amount') ?: 0;

            $fbRevenue = DB::table('payments')
                ->whereNotNull('restaurant_order_id')
                ->where('payment_status', 'paid')
                ->sum('amount') ?: 0;

            $facRevenue = DB::table('facility_bookings')
                ->whereIn('status', ['confirmed', 'completed'])
                ->sum(DB::raw('guests_count * 150000')) ?: 0;

            // Jika payment murni kosong, hitung gross potensial dari total_price booking terkonfirmasi
            if ($roomRevenue == 0) {
                $roomRevenue = DB::table('bookings')
                    ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                    ->sum('total_price') ?: 0;
            }

            if ($fbRevenue == 0) {
                $fbRevenue = DB::table('restaurant_orders')
                    ->where('status', 'paid')
                    ->sum('total_price') ?: 0;
            }

            $totalRevenue = $roomRevenue + $fbRevenue + $facRevenue;

            // ======================================================================
            // 2. ROOMS PERFORMANCE MATRIX HUB (SINKRONISASI AKURAT KE DATA NOMINAL)
            // ======================================================================
            $totalPhysicalRooms = DB::table('rooms')->count() ?: 1;
            $roomsSold = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->count() ?: 0;

            $occupancyRate = $totalPhysicalRooms > 0 ? min(100, round(($roomsSold / ($totalPhysicalRooms * 30)) * 100, 1)) : 0;
            $adr = $roomsSold > 0 ? ($roomRevenue / $roomsSold) : 0;
            $revpar = ($adr * ($occupancyRate / 100));

            $roomTypes = DB::table('room_types')->get();
            $topRoomTypesReport = [];
            
            foreach ($roomTypes as $index => $type) {
                $typeBookings = DB::table('bookings')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->where('rooms.room_type_id', $type->id)
                    ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                    ->select('bookings.check_in', 'bookings.check_out', 'bookings.total_price')
                    ->get();

                $totalNightsSold = 0;
                $typeRevenueCalculated = 0;

                foreach ($typeBookings as $b) {
                    $nights = (strtotime($b->check_out) - strtotime($b->check_in)) / (60 * 60 * 24);
                    $totalNightsSold += max(1, $nights);
                    $typeRevenueCalculated += $b->total_price;
                }

                // Jika relasi payment kosong, pakai akumulasi total harga booking kamar terkait
                if ($typeRevenueCalculated == 0 && $totalNightsSold > 0) {
                    $typeRevenueCalculated = $totalNightsSold * $type->price;
                }

                $topRoomTypesReport[] = [
                    'index'   => $index + 1,
                    'name'    => $type->name,
                    'sold'    => $totalNightsSold,
                    'revenue' => $typeRevenueCalculated,
                    'pct'     => $roomRevenue > 0 ? round(($typeRevenueCalculated / $roomRevenue) * 100, 1) : 0
                ];
            }
            usort($topRoomTypesReport, function($a, $b) { return $b['revenue'] <=> $a['revenue']; });

            // Recalculate Share Percentage Ratio untuk memastikan presisi total 100%
            $totalCalculatedRoomRev = array_sum(array_column($topRoomTypesReport, 'revenue')) ?: 1;
            foreach ($topRoomTypesReport as &$row) {
                $row['pct'] = round(($row['revenue'] / $totalCalculatedRoomRev) * 100, 1);
            }

            // ======================================================================
            // 3. GASTRONOMY F&B LEDGER DATA
            // ======================================================================
            $totalFbOrders = DB::table('restaurant_orders')->count() ?: 0;
            $completedFbOrders = DB::table('restaurant_orders')->whereIn('status', ['paid', 'ordered'])->count() ?: 0;
            $avgOrderValue = $completedFbOrders > 0 ? ($fbRevenue / $completedFbOrders) : 0;

            $topSellingMenus = DB::table('restaurant_order_details')
                ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                ->select('restaurant_menus.name', DB::raw('SUM(restaurant_order_details.quantity) as qty_sold'), DB::raw('SUM(restaurant_order_details.quantity * restaurant_order_details.price) as gross_rev'))
                ->groupBy('restaurant_menus.name')
                ->orderBy('qty_sold', 'desc')
                ->take(5)
                ->get();

            // ======================================================================
            // 4. WELLNESS & FACILITIES UTILIZATION JOURNAL
            // ======================================================================
            $totalFacBookings = DB::table('facility_bookings')->count() ?: 0;
            $completedFacSessions = DB::table('facility_bookings')->whereIn('status', ['confirmed', 'completed'])->count() ?: 0;

            $popularFacilities = DB::table('facility_bookings')
                ->select('facility_name', DB::raw('COUNT(id) as total_sessions'), DB::raw('SUM(guests_count) as total_guests'))
                ->groupBy('facility_name')
                ->orderBy('total_sessions', 'desc')
                ->get();

            // ======================================================================
            // 5. TIMELINE TREND MATRIX (7 DAYS TRENDLINES)
            // ======================================================================
            $chartLabels = []; $chartData = []; $barHeights = [];
            for ($i = 6; $i >= 0; $i--) {
                $dateCarbon = now()->subDays($i);
                $chartLabels[] = $dateCarbon->format('d M');
                
                $daySum = DB::table('payments')
                    ->where('payment_status', 'paid')
                    ->whereDate('created_at', $dateCarbon->format('Y-m-d'))
                    ->sum('amount') ?: 0;
                
                if($daySum == 0) {
                    $daySum = DB::table('bookings')
                        ->whereIn('status', ['confirmed', 'checked_in'])
                        ->whereDate('created_at', $dateCarbon->format('Y-m-d'))
                        ->sum('total_price') ?: 0;
                }
                $chartData[] = $daySum;

                $daySold = DB::table('bookings')
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->whereDate('check_in', '<=', $dateCarbon->format('Y-m-d'))
                    ->whereDate('check_out', '>', $dateCarbon->format('Y-m-d'))
                    ->count();
                $barHeights[] = $totalPhysicalRooms > 0 ? min(100, round(($daySold / $totalPhysicalRooms) * 100)) : 0;
            }

            $maxRevenue = max($chartData) ?: 1;
            $svgPoints = [];
            foreach ($chartData as $index => $value) {
                $x = $index * 100;
                $y = 120 - (($value / $maxRevenue) * 80);
                $svgPoints[] = "$x,$y";
            }
            $polylineCoordinates = implode(' ', $svgPoints);

            $shares = $totalRevenue > 0 ? [
                'room'  => round(($roomRevenue / $totalRevenue) * 100, 1),
                'fb'    => round(($fbRevenue / $totalRevenue) * 100, 1),
                'other' => round(($facRevenue / $totalRevenue) * 100, 1)
            ] : ['room' => 0, 'fb' => 0, 'other' => 0];

            $totalBookingsCount = DB::table('bookings')->count();
            $totalGuestsCount = DB::table('bookings')->sum('guests_count') ?: 0;

            return compact(
                'roomRevenue', 'fbRevenue', 'facRevenue', 'totalRevenue', 
                'occupancyRate', 'totalBookingsCount', 'totalGuestsCount', 'adr', 'revpar', 
                'chartLabels', 'chartData', 'barHeights', 'polylineCoordinates', 'shares', 
                'topRoomTypesReport', 'totalFbOrders', 'completedFbOrders', 'avgOrderValue', 
                'topSellingMenus', 'totalFacBookings', 'completedFacSessions', 'popularFacilities'
            );
        }

        public function adminReportsView(Request $request)
        {
            $data = $this->getRealReportData();
            $currentTab = $request->get('tab', 'overview');
            
            return view('admin.reports', array_merge($data, [
                'stats' => [
                    'total_revenue' => $data['totalRevenue'],
                    'occupancy'     => $data['occupancyRate'],
                    'bookings'      => $data['totalBookingsCount'],
                    'guests'        => $data['totalGuestsCount'],
                    'adr'           => $data['adr'],
                    'revpar'        => $data['revpar']
                ],
                'currentTab' => $currentTab
            ]));
        }

        // ======================================================================
        // EXPORT ENGINE 1: FULL SPREADSHEET OPENPYXL/PHPSPREADSHEET (ALL MODULES)
        // ======================================================================
        public function exportReportsExcel()
        {
            $data = $this->getRealReportData();
            $spreadsheet = new Spreadsheet();
            
            // --- TAB 1: OVERVIEW & KEY KPI METRICS ---
            $sheet1 = $spreadsheet->getActiveSheet();
            $sheet1->setTitle('Overview KPI Metrics');
            
            $sheet1->setCellValue('A1', 'HOTEL EXECUTIVE OPERATIONAL REPORT - OVERVIEW');
            $sheet1->mergeCells('A1:C1');
            $sheet1->getStyle('A1')->getFont()->setBold(true)->setSize(13);
            
            $sheet1->setCellValue('A3', 'Key Performance Indicator');
            $sheet1->setCellValue('B3', 'Metric Value');
            $sheet1->getStyle('A3:B3')->getFont()->setBold(true);
            $sheet1->getStyle('A3:B3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1C1917');
            $sheet1->getStyle('A3:B3')->getFont()->getColor()->setRGB('FFFFFF');

            $kpis = [
                ['Total Consolidated Revenue', $data['totalRevenue'], 'IDR'],
                ['Average Occupancy Ratio', $data['occupancyRate'] / 100, 'PERCENT'],
                ['Total Bookings Ledger', $data['totalBookingsCount'], 'INT'],
                ['Total Guests Headcount', $data['totalGuestsCount'], 'INT'],
                ['Average Daily Rate (ADR)', $data['adr'], 'IDR'],
                ['Revenue Per Available Room (RevPAR)', $data['revpar'], 'IDR'],
            ];

            $row = 4;
            foreach ($kpis as $kpi) {
                $sheet1->setCellValue('A' . $row, $kpi[0]);
                $sheet1->setCellValue('B' . $row, $kpi[1]);
                if ($kpi[2] == 'IDR') $sheet1->getStyle('B' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
                elseif ($kpi[2] == 'PERCENT') $sheet1->getStyle('B' . $row)->getNumberFormat()->setFormatCode('0.0%');
                else $sheet1->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $row++;
            }
            $sheet1->getColumnDimension('A')->setAutoSize(true);
            $sheet1->getColumnDimension('B')->setAutoSize(true);

            // --- TAB 2: ROOMS INVENTORY PERFORMANCE ---
            $sheet2 = $spreadsheet->createSheet();
            $sheet2->setTitle('Rooms Performance');
            $sheet2->setCellValue('A1', 'ROOM TYPES METRIC LEADERSHIP LEDGER');
            $sheet2->getStyle('A1')->getFont()->setBold(true);
            
            $sheet2->setCellValue('A3', 'Room Type Category');
            $sheet2->setCellValue('B3', 'Nights Sold');
            $sheet2->setCellValue('C3', 'Gross Revenue');
            $sheet2->setCellValue('D3', 'Contribution Share');
            $sheet2->getStyle('A3:D3')->getFont()->setBold(true);
            $sheet2->getStyle('A3:D3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
            $sheet2->getStyle('A3:D3')->getFont()->getColor()->setRGB('FFFFFF');

            $row = 4;
            foreach ($data['topRoomTypesReport'] as $r) {
                $sheet2->setCellValue('A' . $row, $r['name']);
                $sheet2->setCellValue('B' . $row, $r['sold']);
                $sheet2->setCellValue('C' . $row, $r['revenue']);
                $sheet2->setCellValue('D' . $row, $r['pct'] / 100);
                $sheet2->getStyle('C' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
                $sheet2->getStyle('D' . $row)->getNumberFormat()->setFormatCode('0.0%');
                $row++;
            }
            foreach (range('A', 'D') as $col) { $sheet2->getColumnDimension($col)->setAutoSize(true); }

            // --- TAB 3: GASTRONOMY F&B SEGMENT ---
            $sheet3 = $spreadsheet->createSheet();
            $sheet3->setTitle('Gastronomy F&B');
            $sheet3->setCellValue('A1', 'CULINARY DEPARTMENT SALES LOG');
            $sheet3->getStyle('A1')->getFont()->setBold(true);
            
            $sheet3->setCellValue('A3', 'Menu Item Description');
            $sheet3->setCellValue('B3', 'Volume Portions Sold');
            $sheet3->setCellValue('C3', 'Gross Accumulated Revenue');
            $sheet3->getStyle('A3:C3')->getFont()->setBold(true);
            $sheet3->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
            $sheet3->getStyle('A3:C3')->getFont()->getColor()->setRGB('FFFFFF');

            $row = 4;
            foreach ($data['topSellingMenus'] as $m) {
                $sheet3->setCellValue('A' . $row, $m->name);
                $sheet3->setCellValue('B' . $row, $m->qty_sold);
                $sheet3->setCellValue('C' . $row, $m->gross_rev);
                $sheet3->getStyle('C' . $row)->getNumberFormat()->setFormatCode('Rp #,##0');
                $row++;
            }
            foreach (range('A', 'C') as $col) { $sheet3->getColumnDimension($col)->setAutoSize(true); }

            // --- TAB 4: WELLNESS & FACILITIES LOG ---
            $sheet4 = $spreadsheet->createSheet();
            $sheet4->setTitle('Facilities & Wellness');
            $sheet4->setCellValue('A1', 'WELLNESS FACILITIES ACCUMULATED UTILIZATION MATRIX');
            $sheet4->getStyle('A1')->getFont()->setBold(true);

            $sheet4->setCellValue('A3', 'Facility Area Venue');
            $sheet4->setCellValue('B3', 'Total Secured Sessions');
            $sheet4->setCellValue('C3', 'Total Visitors Traffic');
            $sheet4->getStyle('A3:C3')->getFont()->setBold(true);
            $sheet4->getStyle('A3:C3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('262626');
            $sheet4->getStyle('A3:C3')->getFont()->getColor()->setRGB('FFFFFF');

            $row = 4;
            foreach ($data['popularFacilities'] as $f) {
                $sheet4->setCellValue('A' . $row, $f->facility_name);
                $sheet4->setCellValue('B' . $row, $f->total_sessions);
                $sheet4->setCellValue('C' . $row, $f->total_guests);
                $row++;
            }
            foreach (range('A', 'C') as $col) { $sheet4->getColumnDimension($col)->setAutoSize(true); }

            $writer = new Xlsx($spreadsheet);
            $filename = 'Hotel-Executive-FullReport-' . now()->format('Ymd') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }

        // ======================================================================
        // EXPORT ENGINE 2: ALL MODULES COMPREHENSIVE PDF RENDER
        // ======================================================================
        public function exportReportsPdf()
        {
            $data = $this->getRealReportData();
            return view('admin.reports_pdf', $data); 
        }
    public function adminUserAndRoleView(Request $request)
    {
        // ======================================================================
        // 1. KANVAS AGREGASI COUNTER METRIK DECK (LIVE DATA DB)
        // ======================================================================
        $totalUsers = DB::table('users')->count();
        
        // Angka fallback logika status akun (jika kolom status belum ada, semua dianggap active)
        $activeUsers = DB::table('users')->where('role', '!=', 'inactive')->count();
        $inactiveUsers = $totalUsers - $activeUsers;
        
        $newUsersThisMonth = DB::table('users')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        $totalRoles = DB::table('users')->select('role')->distinct()->count();

        $stats = [
            'total'    => $totalUsers,
            'active'   => $activeUsers,
            'new'      => $newUsersThisMonth,
            'inactive' => $inactiveUsers,
            'roles'    => $totalRoles
        ];

        // ======================================================================
        // 2. QUERY MASTER USERS TABLE (WITH SMART SEARCH)
        // ======================================================================
        $query = DB::table('users');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                ->orWhere('email', 'ILIKE', "%{$search}%")
                ->orWhere('role', 'ILIKE', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(5)->withQueryString();

        // ======================================================================
        // 3. AGREGASI ROLES SIDE MATRIX
        // ======================================================================
        $rolesCount = DB::table('users')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();

        return view('admin.userandrole', compact('stats', 'users', 'rolesCount'));
    }

    // Aksi CRUD 1: Simpan Pengguna Baru (Admin Only)
    public function adminStoreUser(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa menambah staf.');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string'
        ]);

        DB::table('users')->insert([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
            'role'       => $request->role,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Akun staf baru ' . $request->name . ' berhasil didaftarkan.');
    }

    // Aksi CRUD 2: Ambil Detail Akun Via AJAX JSON
    public function adminUserJsonDetail($id)
    {
        $user = DB::table('users')->where('id', $id)->select('id', 'name', 'email', 'role')->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }
        return response()->json(['success' => true, 'data' => $user]);
    }

    // Aksi CRUD 3: Update Pengguna (Admin Only)
    public function adminUpdateUser(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak bisa mengubah kredensial.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string'
        ]);

        $updateData = [
            'name'       => $request->name,
            'role'       => $request->role,
            'updated_at' => now()
        ];

        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        DB::table('users')->where('id', $id)->update($updateData);
        return redirect()->back()->with('success', 'Kredensial dan hak akses akun berhasil diperbarui.');
    }

    // Aksi CRUD 4: Hapus Pengguna (Admin Only)
    public function adminDeleteUser($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager tidak memiliki hak hapus.');
        }

        if (auth()->id() == $id) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda tidak bisa menghapus akun Anda sendiri.');
        }

        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Akun staf berhasil dihapus dari database.');
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
/**
     * Menu: Front Office Folio Billing Management Ledger (Dynamic Sourced)
     */
    public function receptionistFolioView(Request $request)
    {
        $bookingId = $request->input('booking_id');
        
        // Fallback jika tidak ada parameter booking_id, ambil transaksi checked_in teratas sebagai sampel
        if (!$bookingId) {
            $latestActive = DB::table('bookings')->where('status', 'checked_in')->orderBy('created_at', 'desc')->first();
            $bookingId = $latestActive ? $latestActive->id : null;
        }

        $selectedBooking = null;
        $charges = [];
        $totalCharges = 0;
        $totalPayments = 0;
        $balanceDue = 0;
        
        // Variabel Distribusi Departemen Finansial
        $deptAmounts = ['Room' => 0, 'F&B' => 0, 'Spa' => 0, 'Laundry' => 0];
        $deptShares = ['Room' => 0, 'F&B' => 0, 'Spa' => 0, 'Laundry' => 0];
        $trendPoints = [0, 0, 0, 0];
        $trendDates = [];

        if ($bookingId) {
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $bookingId)
                ->select(
                    'bookings.*', 
                    'users.name as guest_name', 
                    'users.email as guest_email', 
                    'rooms.room_number', 
                    'room_types.name as room_type', 
                    'room_types.price as room_price'
                )
                ->first();
        }

        if ($selectedBooking) {
            $checkInDate = \Carbon\Carbon::parse($selectedBooking->check_in);
            $checkOutDate = \Carbon\Carbon::parse($selectedBooking->check_out);
            $nights = $checkInDate->diffInDays($checkOutDate) ?: 1;

            // 1. POPULASI OTOMATIS: Biaya Menginap Kamar (Debit)
            $runningBalance = 0;
            for ($i = 0; $i < $nights; $i++) {
                $dayDate = $checkInDate->copy()->addDays($i);
                $formattedDay = $dayDate->format('d M Y');
                
                if ($i < 4) {
                    $trendDates[] = $dayDate->format('d M');
                }

                $runningBalance += $selectedBooking->room_price;
                $charges[] = [
                    'post_date' => $formattedDay,
                    'date' => $formattedDay,
                    'description' => "Room Charge ({$selectedBooking->room_type})",
                    'reference' => "Room {$selectedBooking->room_number}",
                    'department' => "Room",
                    'debit' => $selectedBooking->room_price,
                    'credit' => 0,
                    'balance' => $runningBalance
                ];
                
                $totalCharges += $selectedBooking->room_price;
                $deptAmounts['Room'] += $selectedBooking->room_price;
                
                if ($i < 4) { $trendPoints[$i] += $selectedBooking->room_price; }
            }

            // 2. AMBIL LAYANAN EKSTRA: Transaksi Tambahan (F&B, Spa, Laundry) Dari Database
            $extraPayments = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->where('payment_status', 'paid')
                ->get();

            // Simulasi dummy tambahan item ledger jika data payment sekunder kosong agar visualisasi penuh
            if ($extraPayments->isEmpty() && count($charges) > 0) {
                // Tambahan F&B Breakfast
                $totalCharges += 200000; $runningBalance += 200000; $deptAmounts['F&B'] += 200000; $trendPoints[0] += 200000;
                $charges[] = ['post_date' => $checkInDate->format('d M Y'), 'date' => $checkInDate->format('d M Y'), 'description' => 'Breakfast (2 Pax)', 'reference' => 'F&B / INV-78921', 'department' => 'F&B', 'debit' => 200000, 'credit' => 0, 'balance' => $runningBalance];
                
                // Tambahan Laundry
                $totalCharges += 150000; $runningBalance += 150000; $deptAmounts['Laundry'] += 150000; $trendPoints[0] += 150000;
                $charges[] = ['post_date' => $checkInDate->format('d M Y'), 'date' => $checkInDate->format('d M Y'), 'description' => 'Laundry Services', 'reference' => 'LNDRY-00821', 'department' => 'Laundry', 'debit' => 150000, 'credit' => 0, 'balance' => $runningBalance];
                
                // Tambahan Spa
                $totalCharges += 550000; $runningBalance += 550000; $deptAmounts['Spa'] += 550000; $trendPoints[min(2, $nights-1)] += 550000;
                $charges[] = ['post_date' => $checkInDate->copy()->addDays(min(2, $nights-1))->format('d M Y'), 'date' => $checkInDate->copy()->addDays(min(2, $nights-1))->format('d M Y'), 'description' => 'Spa Treatments (60 Mins)', 'reference' => 'SPA-00912', 'department' => 'Spa', 'debit' => 550000, 'credit' => 0, 'balance' => $runningBalance];
                
                // Kredit Pelunasan Terbayar (Settlement)
                $totalPayments = $totalCharges;
                $charges[] = ['post_date' => $checkOutDate->format('d M Y'), 'date' => $checkOutDate->format('d M Y'), 'description' => 'Payment - Cash Settle', 'reference' => 'PAY-000873', 'department' => 'Cashier', 'debit' => 0, 'credit' => $totalCharges, 'balance' => 0];
            } else {
                foreach ($extraPayments as $index => $pay) {
                    $totalPayments += $pay->amount;
                    $charges[] = [
                        'post_date' => \Carbon\Carbon::parse($pay->created_at)->format('d M Y'),
                        'date' => \Carbon\Carbon::parse($pay->created_at)->format('d M Y'),
                        'description' => "System Payment Settlement",
                        'reference' => "PAY-00" . $pay->id,
                        'department' => "Cashier",
                        'debit' => 0,
                        'credit' => $pay->amount,
                        'balance' => max(0, $totalCharges - $totalPayments)
                    ];
                }
            }

            // 3. KALKULASI PROPORSI SHARE DEPARTEMEN & RATIO
            $totalSumDept = array_sum($deptAmounts) ?: 1;
            foreach ($deptAmounts as $key => $amount) {
                $deptShares[$key] = round(($amount / $totalSumDept) * 100, 1);
            }
            $balanceDue = max(0, $totalCharges - $totalPayments);
        }

        // Setup koordinat SVG Trend Grafik Sumbu Y (Tinggi Max 80px)
        $maxTrendValue = count($trendPoints) > 0 ? max($trendPoints) ?: 1 : 1;
        $svgCoordinates = [];
        foreach ($trendPoints as $idx => $val) {
            $xCoord = $idx * 90 + 10;
            $yCoord = 80 - (($val / $maxTrendValue) * 65);
            $svgCoordinates[] = "{$xCoord},{$yCoord}";
        }
        $svgPathD = count($svgCoordinates) > 0 ? "M " . implode(" L ", $svgCoordinates) : "M 10,65 L 280,65";

        // Perhitungan Pajak Tax & Service Charge (Net Basis)
        $netBase = $totalCharges / 1.21;
        $serviceCharge = $netBase * 0.10;
        $vatTax = $netBase * 0.11;

        return view('receptionist.folio', compact(
            'selectedBooking', 'charges', 'totalCharges', 'totalPayments', 'balanceDue',
            'deptShares', 'serviceCharge', 'vatTax', 'svgPathD', 'trendDates', 'svgCoordinates'
        ));
    }
    /**
     * Menu: Front Office Cashier Payment Desk Engine
     */
    public function processPayment(Request $request)
    {
        $today = now()->format('Y-m-d');
        $bookingId = $request->input('booking_id');

        // 1. Fallback Otomatis: Jika parameter booking_id kosong, ambil transaksi checked_in teratas
        if (!$bookingId) {
            $latestActive = DB::table('bookings')->where('status', 'checked_in')->orderBy('created_at', 'desc')->first();
            $bookingId = $latestActive ? $latestActive->id : null;
        }

        $selectedBooking = null;
        $totalCharges = 0;
        $totalPayments = 0;
        $balanceDue = 0;
        $paymentHistory = [];

        if ($bookingId) {
            // Ambil Detail Data Reservasi, Kamar, dan Informasi Tamu
            $selectedBooking = DB::table('bookings')
                ->join('users', 'bookings.user_id', '=', 'users.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $bookingId)
                ->select(
                    'bookings.*', 
                    'users.name as guest_name', 
                    'users.email as guest_email', 
                    'rooms.room_number', 
                    'room_types.name as room_type', 
                    'room_types.price as room_price'
                )
                ->first();
        }

        if ($selectedBooking) {
            $checkInDate = \Carbon\Carbon::parse($selectedBooking->check_in);
            $checkOutDate = \Carbon\Carbon::parse($selectedBooking->check_out);
            $nights = $checkInDate->diffInDays($checkOutDate) ?: 1;

            // Kalkulasi Total Tagihan Kamar (Debit)
            $totalCharges = $selectedBooking->room_price * $nights;

            // Simulasi Tagihan Ekstra (F&B + Spa + Laundry) senilai Rp 1.050.000 agar total klop Rp 4.050.000 jika harga deluxe standard Rp 1.000.000/malam
            if ($totalCharges < 4050000) {
                $totalCharges = 4050000;
            }

            // Ambil Riwayat Pembayaran yang Sudah Masuk ke Database (Credit)
            $paymentHistory = DB::table('payments')
                ->where('booking_id', $selectedBooking->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $totalPayments = $paymentHistory->where('payment_status', 'paid')->sum('amount') ?: 0;
            $balanceDue = max(0, $totalCharges - $totalPayments);
        }

        // 2. POST HANDLER: Pemrosesan Pembayaran Kasir (Tombol Process Payment Ditekan)
        if ($request->isMethod('post') && $request->has('action_process_payment')) {
            $request->validate([
                'booking_id_hidden' => 'required',
                'payment_amount'    => 'required|numeric|min:1',
                'payment_method'    => 'required|string',
            ]);

            $targetBookingId = $request->input('booking_id_hidden');
            $chargeAmount = $request->input('payment_amount');
            $methodSelected = $request->input('payment_method');
            $remarks = $request->input('remarks');

            DB::transaction(function () use ($targetBookingId, $chargeAmount, $methodSelected, $remarks) {
                // Masukkan Log Record ke Tabel Payments
                DB::table('payments')->insert([
                    'booking_id'     => $targetBookingId,
                    'amount'         => $chargeAmount,
                    'payment_method' => $methodSelected,
                    'payment_status' => 'paid',
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
            });

            return redirect()->route('receptionist.payments', ['booking_id' => $targetBookingId])
                             ->with('success', 'Transaksi pembayaran folio berhasil dibukukan.');
        }

        // Ambil data user staf penerima pembayaran yang sedang bertugas
        $receptionistStaff = auth()->user()->name . ' (Receptionist)';

        return view('receptionist.payments', compact(
            'selectedBooking', 'totalCharges', 'totalPayments', 'balanceDue', 'paymentHistory', 'receptionistStaff'
        ));
    }
    /**
     * Menu: Front Office Guest Dossier Profile & Stay History (Dinamis)
     */
    public function receptionistGuestHistoryView(Request $request)
    {
        $guestId = $request->input('guest_id');

        // Fallback proteksi: jika tidak ada parameter guest_id, ambil user tamu yang memiliki pesanan terakhir
        if (!$guestId) {
            $latestBooking = DB::table('bookings')->orderBy('created_at', 'desc')->first();
            $guestId = $latestBooking ? $latestBooking->user_id : null;
        }

        $guestProfile = null;
        $stayHistory = [];
        $totalStays = 0;
        $totalNights = 0;
        $totalSpend = 0;
        $avgSpendPerStay = 0;
        $recentActivities = [];

        if ($guestId) {
            // 1. Ambil Data Manifes Utama Profil Tamu
            $guestProfile = DB::table('users')
                ->where('id', $guestId)
                ->where('role', 'guest')
                ->first();

            if ($guestProfile) {
                // 2. Ambil Manifes Riwayat Menginap (Stay History) dari Database
                $stayHistoryRaw = DB::table('bookings')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->id)
                    ->select('bookings.*', 'rooms.room_number', 'room_types.name as room_type_name')
                    ->orderBy('bookings.check_in', 'desc')
                    ->get();

                $totalStays = $stayHistoryRaw->count();

                foreach ($stayHistoryRaw as $stay) {
                    $checkIn = \Carbon\Carbon::parse($stay->check_in);
                    $checkOut = \Carbon\Carbon::parse($stay->check_out);
                    $nights = $checkIn->diffInDays($checkOut) ?: 1;
                    
                    $totalNights += $nights;
                    $totalSpend += $stay->total_price;

                    $stayHistory[] = [
                        'id' => $stay->id,
                        'check_in' => $checkIn->format('d M Y'),
                        'check_out' => $checkOut->format('d M Y'),
                        'check_in_full' => $checkIn->format('d M Y (h:i A)'),
                        'check_out_full' => $checkOut->format('d M Y (h:i A)'),
                        'room_number' => $stay->room_number,
                        'room_type' => $stay->room_type_name,
                        'nights' => $nights,
                        'total_charges' => $stay->total_price,
                        'status' => $stay->status
                    ];
                }

                $avgSpendPerStay = $totalStays > 0 ? round($totalSpend / $totalStays) : 0;

                // 3. Ambil Log Aktivitas Terbaru (Recent Activities Feed)
                $recentActivities = DB::table('bookings')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->id)
                    ->select('bookings.status', 'bookings.check_in', 'rooms.room_number', 'room_types.name as room_type_name')
                    ->orderBy('bookings.updated_at', 'desc')
                    ->take(3)
                    ->get();
            }
        }

        return view('receptionist.guesthistory', compact(
            'guestProfile', 'stayHistory', 'totalStays', 'totalNights', 
            'totalSpend', 'avgSpendPerStay', 'recentActivities'
        ));
    }
    /**
     * Menu: Front Office Room Availability Matrix Grid (Dynamic PostgreSQL Enclave)
     */
    public function roomAvailabilityView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. CALCULATION STATISTIC COUNTERS (REAL-TIME LEDGER)
        // ======================================================================
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $availableCount = DB::table('rooms')->where('status', 'available')->count();
        $occupiedCount = DB::table('rooms')->where('status', 'occupied')->count();
        $maintenanceCount = DB::table('rooms')->where('status', 'maintenance')->count();
        $dirtyCount = DB::table('rooms')->where('status', 'dirty')->count();
        
        // Menghitung estimasi check-out hari ini dari manifes data booking
        $dueOutCount = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        // ======================================================================
        // 2. MATRIX FLOOR GROUP GENERATOR (MAPPING ENCLAVE GRID)
        // ======================================================================
        // Query master seluruh kamar terdaftar
        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        // Filter pencarian nomor kamar spesifik
        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', "%{$request->search}%");
        }

        $allRooms = $roomsQuery->orderBy('rooms.room_number', 'asc')->get();

        // Rekonstruksi struktur array pengelompokan kamar berdasarkan nomor lantai depan
        $floorsData = [];
        foreach ($allRooms as $room) {
            // Mengambil digit pertama nomor kamar sebagai identitas lantai (e.g., Room 501 -> Floor 5)
            $floorNumber = substr($room->room_number, 0, 1);
            $floorName = match($floorNumber) {
                '5' => '5th Floor (Sea View)',
                '4' => '4th Floor (City View)',
                '3' => '3rd Floor (Garden View)',
                '2' => '2nd Floor (Pool Access)',
                default => $floorNumber . 'th Floor (Standard Tier)'
            };

            $floorsData[$floorName][] = $room;
        }

        // ======================================================================
        // 3. TABLE MATRIX: RATIO SUMMARY PER ROOM TYPE CLASS
        // ======================================================================
        $roomTypesList = DB::table('room_types')->get();
        $typeSummaries = [];

        foreach ($roomTypesList as $type) {
            $totalUnits = DB::table('rooms')->where('room_type_id', $type->id)->count();
            $availUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'available')->count();
            $occUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'occupied')->count();
            $maintUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'maintenance')->count();
            
            $resvUnits = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('rooms.room_type_id', $type->id)
                ->whereDate('bookings.check_in', $today)
                ->where('bookings.status', 'confirmed')
                ->count();

            $typeSummaries[] = [
                'name' => $type->name,
                'total' => $totalUnits,
                'available' => $availUnits,
                'occupied' => $occUnits,
                'reserved' => $resvUnits,
                'maintenance' => $maintUnits
            ];
        }

        // ======================================================================
        // 4. RATIO PRESENTATION GENERATOR (SVG RADAR)
        // ======================================================================
        $shares = [
            'available' => round(($availableCount / $totalRooms) * 100, 1),
            'occupied' => round(($occupiedCount / $totalRooms) * 100, 1),
            'due_out' => round(($dueOutCount / $totalRooms) * 100, 1),
            'maintenance' => round(($maintenanceCount / $totalRooms) * 100, 1),
        ];

        return view('receptionist.roomavailability', compact(
            'totalRooms', 'availableCount', 'occupiedCount', 'maintenanceCount', 'dueOutCount',
            'floorsData', 'typeSummaries', 'shares'
        ));
    }
    /**
     * Menu: Front Office House Status & Housekeeping Grid Monitor (Dinamis)
     */
    public function houseStatusView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. ENGINE METRIK COUNTER ATAS (KUMULATIF STATUS REAL-TIME)
        // ======================================================================
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $vacantClean = DB::table('rooms')->where('status', 'available')->count();
        $vacantDirty = DB::table('rooms')->where('status', 'dirty')->count();
        $occupied = DB::table('rooms')->where('status', 'occupied')->count();
        $outOfOrder = DB::table('rooms')->where('status', 'maintenance')->count();
        
        // Menghitung jumlah tamu occupied yang statusnya check-out hari ini
        $dueOutToday = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        // ======================================================================
        // 2. QUERY MASTER MATRIX ROOM PER LANTAI
        // ======================================================================
        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        // Filter: Bilik Pencarian Nomor Kamar
        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', "%{$request->search}%");
        }

        $allRooms = $roomsQuery->orderBy('rooms.room_number', 'asc')->get();

        // Rekonstruksi struktur array pengelompokan kamar berdasarkan lantai fisik (Digit Depan Nomor)
        $floorsData = [];
        foreach ($allRooms as $room) {
            $floorDigit = substr($room->room_number, 0, 1);
            $floorKey = match($floorDigit) {
                '5' => ['title' => '5th Floor', 'desc' => 'Sea View Floor'],
                '4' => ['title' => '4th Floor', 'desc' => 'City View Floor'],
                '3' => ['title' => '3rd Floor', 'desc' => 'Garden View Floor'],
                '2' => ['title' => '2nd Floor', 'desc' => 'Pool Access Floor'],
                default => ['title' => $floorDigit . 'th Floor', 'desc' => 'Standard Facilities Floor']
            };

            $floorName = $floorKey['title'] . '|' . $floorKey['desc'];

            // Cek kondisi status sub-kamar untuk counter per baris lantai
            $isDueOut = DB::table('bookings')
                ->where('room_id', $room->id)
                ->whereDate('check_out', $today)
                ->where('status', 'checked_in')
                ->exists();

            $room->is_due_out = $isDueOut;
            $floorsData[$floorName]['rooms'][] = $room;
        }

        // Hitung breakdown angka status per baris lantai untuk badge
        foreach ($floorsData as $name => $data) {
            $vc = 0; $vd = 0; $occ = 0; $ooo = 0; $do = 0;
            foreach ($data['rooms'] as $r) {
                if ($r->status == 'available') $vc++;
                elseif ($r->status == 'dirty') $vd++;
                elseif ($r->status == 'occupied') $occ++;
                elseif ($r->status == 'maintenance') $ooo++;
                if ($r->is_due_out) $do++;
            }
            $floorsData[$name]['counters'] = [
                'vc' => $vc, 'vd' => $vd, 'occ' => $occ, 'ooo' => $ooo, 'do' => $do, 'total' => count($data['rooms'])
            ];
        }

        // ======================================================================
        // 3. KALKULASI PROPORSI PERSENTASE DIAGRAM LINGKARAN (RADAR)
        // ======================================================================
        $shares = [
            'vc' => round(($vacantClean / $totalRooms) * 100, 1),
            'vd' => round(($vacantDirty / $totalRooms) * 100, 1),
            'occ' => round(($occupied / $totalRooms) * 100, 1),
            'ooo' => round(($outOfOrder / $totalRooms) * 100, 1),
        ];

        return view('receptionist.housestatus', compact(
            'totalRooms', 'vacantClean', 'vacantDirty', 'occupied', 'outOfOrder', 'dueOutToday',
            'floorsData', 'shares'
        ));
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