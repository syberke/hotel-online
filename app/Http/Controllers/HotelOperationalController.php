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
     * Menampilkan Landing Page Utama dengan data Kamar & Menu dari Database
     */
    public function index()
    {
        // 1. Ambil data kamar aktif
        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                     ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price_per_night', 'room_types.foto_url')
            ->orderBy('room_types.price_per_night', 'asc')
            ->get();

        // 2. AMBIL DARI DATABASE: Menarik data menu langsung dari tabel master restaurant_menus
        $culinaryMenus = DB::table('restaurant_menus')->get();

        return view('page.home', compact('roomsLiveList', 'culinaryMenus'));
    }

    /**
     * Menampilkan Halaman Katalog Kamar Publik
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
                'room_types.price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price_per_night', 'room_types.foto_url')
            ->orderBy('room_types.price_per_night', 'asc')
            ->get();

        // Data tambahan penunjang filter bawaan file rooms.blade.php kamu
        $allCategories = DB::table('room_types')->select('name')->get();
        $totalInventoryReady = DB::table('rooms')->where('status', 'available')->count();

        // PERBAIKAN: Langsung panggil 'rooms' karena letak filenya di resources/views/page/rooms.blade.php
        return view('page.rooms', compact('roomsLiveList', 'allCategories', 'totalInventoryReady')); 
    }
/**
     * Menampilkan halaman Room Service Portal Guest
     */
    public function indexRoomService()
    {
        // 1. Ambil seluruh data menu dari tabel kuliner kamu (pastikan nama tabelnya sesuai, misal: restaurant_menus)
        // Jika nama tabel kamu di Supabase adalah 'restaurant_menus', gunakan nama tersebut.
       $menus = DB::table('restaurant_menus')
    ->select(
        'title as name', 
        'price', 
        'description', 
        'image_url as foto_url'
    )
    ->get();
        // 2. Lempar variabel $menus ke dalam file view agar foreach di Blade tidak crash
        // Sesuaikan 'guest.roomservice' dengan lokasi path folder blade Anda
        return view('guest.roomservice', compact('menus'));
    }
    /**
     * Menampilkan halaman detail spesifik kamar berdasarkan ID
     */
  /**
     * Menampilkan halaman detail spesifik kamar berdasarkan ID (DENGAN INVENTORY DINAMIS)
     */
    public function roomShow($id, Request $request)
    {
        // Tangkap tanggal check-in & check-out jika ada kiriman filter dari halaman depan (default hari ini)
        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+1 day')));

       
       // 1. AMAN & SPESIFIK: Hitung unit terpakai HANYA untuk tipe kamar yang sedang dibuka ($id)
        $occupiedCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id') // Join untuk tahu tipe kamarnya
            ->where('rooms.room_type_id', $id) // <-- KUNCI DI SINI: Harus sesuai ID tipe kamar saat ini!
            ->whereIn('bookings.status', ['confirmed', 'pending', 'completed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in_date', '<=', $checkIn)
                      ->where('bookings.check_out_date', '>', $checkIn);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in_date', '<', $checkOut)
                      ->where('bookings.check_out_date', '>=', $checkOut);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in_date', '>=', $checkIn)
                      ->where('bookings.check_out_date', '<=', $checkOut);
                });
            })
            ->count();

        // 2. Ambil data tipe kamar, lalu hitung sisa inventory riil (Total Kamar - Kamar Terpakai)
        $room = DB::table('room_types')
            ->where('room_types.id', $id)
            ->first();

        if (!$room) { 
            abort(404); 
        }

        // Ambil total seluruh unit fisik kamar tipe ini yang berstatus available
        $totalPhysicalRooms = DB::table('rooms')
            ->where('room_type_id', $id)
            ->where('status', 'available')
            ->count();

        // Sisa kalkulasi inventory dinamis
        $liveAvailableCount = max(0, $totalPhysicalRooms - $occupiedCount);

        // Tempelkan hasilnya ke properti object agar tidak merusak layout Blade kamu
        $room->available_count = $liveAvailableCount;
        
        return view('page.rooms-detail', compact('room'));
    }
    /**
     * Menampilkan halaman detail spesifik menu makanan berdasarkan id
     */
    public function menuShow($id)
    {
        // Mengambil data menu tunggal berdasarkan id
        $menu = DB::table('restaurant_menus')->where('id', $id)->first();
        
        // Jika data menu tidak ditemukan, lempar ke halaman 404
        if (!$menu) { 
            abort(404); 
        }
        
        return view('page.restaurants-detail', compact('menu'));
    }

    /**
     * Menampilkan halaman katalog menu restoran penuh (/restaurant) dengan Filter Dinamis
     */
    public function restaurantIndex(Request $request)
    {
        $query = DB::table('restaurant_menus');

        // Filter Kategori
        if ($request->has('category') && $request->category != 'All Menu') {
            $query->where(function($q) use ($request) {
                $q->where('description', 'ILIKE', '%' . $request->category . '%')
                  ->orWhere('name', 'ILIKE', '%' . $request->category . '%');
            });
        }

        // Filter Pencarian Teks
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        // FILTER: Validasi Range Batas Harga Minimum
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        // FILTER: Validasi Range Batas Harga Maksimum
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter Dietary
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

    /**
     * Menampilkan Halaman Fasilitas Komprehensif
     */
    public function facilitiesIndex()
    {
        return view('page.facilities'); 
    }

    /**
     * Memproses Cek Ketersediaan Kamar via AJAX & Instant Booking via ORM Eloquent (ANTI OVERLAP & DOUBLE BOOKING)
     */
/**
     * Memproses Cek Ketersediaan Kamar Terjadwal & Pemesanan Instan (OTOMATISASI DAN LOCK NOMOR KAMAR FISIK)
     */
 /**
     * Memproses Cek Ketersediaan Kamar Terjadwal & Pemesanan Instan (OTOMATISASI DAN LOCK NOMOR KAMAR FISIK)
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'suite_type' => 'required|string'
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;

        // 1. KUNCI INTEGRITAS DATA: Cari semua room_id yang SUDAH TERJADWAL (Pending/Confirmed/Completed) di tanggal tersebut
        $occupiedRoomIds = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'pending', 'completed'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<=', $checkIn)
                      ->where('check_out_date', '>', $checkIn);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '<', $checkOut)
                      ->where('check_out_date', '>=', $checkOut);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('check_in_date', '>=', $checkIn)
                      ->where('check_out_date', '<=', $checkOut);
                });
            })
            ->pluck('room_id')
            ->toArray();

      // 2. CARI NOMOR KAMAR YANG KOSONG SECARA ACAK: Agar tidak rebutan ID urutan pertama
        $room = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('room_types.name', $request->suite_type)
            ->where('rooms.status', 'available') 
            ->whereNotIn('rooms.id', $occupiedRoomIds) 
            ->select('rooms.id as room_id', 'room_types.price_per_night', 'rooms.room_number')
            ->inRandomOrder()
            ->first();

        // JIKA RUNNING VIA AJAX: Mode Check Only (Sebelum Tombol Berubah Menjadi Book Now)
        if ($request->wantsJson() && $request->has('mode_check_only')) {
            if (!$room) {
                return response()->json([
                    'available' => false, 
                    'message' => 'Maaf, seluruh nomor kamar untuk tipe ' . $request->suite_type . ' sudah penuh terjadwal pada tanggal tersebut.'
                ]);
            }
            return response()->json([
                'available' => true, 
                'message' => 'Kamar nomor ' . $room->room_number . ' siap dialokasikan untuk Anda! Silakan lanjutkan.'
            ]);
        }

        // --- KUNCI UKURAN PENUH: JIKA USER KLIK "BOOK NOW" TAPI KAMAR SUDAH HABIS DIISI TRANSAKSI LAIN ---
        if (!$room) {
            $errMessage = 'Maaf, seluruh unit kamar untuk tipe ' . $request->suite_type . ' sudah penuh dipesan oleh tamu lain pada rentang tanggal tersebut. Silakan pilih tanggal atau tipe suite lainnya.';
            
            // Jika request datang dari instant-booking-form AJAX di halaman detail
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errMessage
                ], 422);
            }
            
            // Jika request datang dari form submit biasa di halaman katalog depan
            return redirect()->back()->withInput()->with('error', $errMessage);
        }

        // Kalkulasi Total Hari & Harga (Lanjutkan ke proses simpan transaksi...)
        $days = (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24);
        $totalPrice = $room->price_per_night * $days;
        if (Auth::check()) {
            // MENGUNCI TRANSAKSI: Kamar resmi diikat ke user ini untuk tanggal tersebut
            DB::transaction(function () use ($request, $room, $totalPrice, $checkIn, $checkOut) {
                
                $guestsCount = 2;
                if ($request->guests === '1 Adult') { $guestsCount = 1; }
                elseif (str_contains($request->guests, '3')) { $guestsCount = 3; }
                elseif (str_contains($request->guests, '4')) { $guestsCount = 4; }

                // Kamar fisik beserta nomor kamarnya langsung dikunci di sini
                $booking = Booking::create([
                    'user_id'        => Auth::id(), 
                    'room_id'        => $room->room_id, 
                    'check_in_date'  => $checkIn,
                    'check_out_date' => $checkOut,
                    'guests_count'   => $guestsCount,
                    'total_price'    => $totalPrice,
                    'status'         => 'pending' 
                ]);

                Payment::create([
                    'booking_id'     => $booking->id, 
                    'amount'         => $totalPrice,
                    'payment_method' => 'transfer',
                    'payment_status' => 'pending'
                ]);
            });

            return $request->wantsJson() 
                ? response()->json(['success' => true, 'redirect' => route('guest.dashboard')])
                : redirect()->route('guest.dashboard')->with('success', 'Reservasi berhasil! Nomor kamar Anda telah dikunci.');
        }

        return $request->wantsJson()
            ? response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Silakan login terlebih dahulu.'])
            : redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
    }
    /**
     * Menampilkan Portal Utama Tamu (Lama - Menggunakan ORM Eloquent & Weather API)
     */
    public function dashboard()
    {
        // 1. Tarik Data Reservasi Menggunakan ORM Eloquent agar Agnostik Cross-Database
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['room.roomType']) 
            ->orderBy('check_in_date', 'asc')
            ->get();

        // 2. Tarik Data Kuliner via Email Tunggal Tamu
        $guestRecord = DB::table('guests')->where('email', Auth::user()->email)->first();
        
        $activeOrders = $guestRecord 
            ? DB::table('restaurant_orders')->where('guest_id', $guestRecord->id)->orderBy('created_at', 'desc')->get() 
            : collect();

        // 3. Ambil Data Cuaca Live Bali Menggunakan Layanan Open-Meteo API (Gratis)
        try {
            $weatherResponse = Http::timeout(3)->get('https://api.open-meteo.com/v1/forecast', [
                'latitude' => -8.4095, 
                'longitude' => 115.1889,
                'current_weather' => true
            ]);
            
            $temperature = $weatherResponse->successful() 
                ? round($weatherResponse->json()['current_weather']['temperature']) . '°C' 
                : '28°C';
        } catch (\Exception $e) {
            $temperature = '28°C'; 
        }

        return view('guest.dashboard', compact('bookings', 'activeOrders', 'temperature'));
    }

    /**
     * PANEL GUEST DASHBOARD BARU (resources/views/guest/guest.blade.php)
     */
    public function guestDashboardIndex()
    {
        $userId = auth()->id();

        // 1. Ambil data booking kamar milik user beserta join informasi nomor kamar dan nama tipe kamar
        $bookings = DB::table('bookings')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.*',
                'rooms.room_number',
                'room_types.name as type_name'
            )
            ->orderBy('bookings.check_in_date', 'desc')
            ->get();

        // 2. Cek histori order makanan di kamar via tabel restaurant_orders Anda
        $activeOrders = DB::table('restaurant_orders')
            ->where('guest_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // 3. Konfigurasi Cuaca Tropis Live Fallback
        $temperature = "29°C"; 

        // Kirimkan semua variabel menuju file view guest.guest (folder guest, file guest.blade.php)
        return view('guest.guest', compact('bookings', 'activeOrders', 'temperature'));
    }

    /**
     * Sub-Menu Dashboard: Menampilkan Daftar Riwayat Booking Kamar Milik Tamu
     */
    public function myBookings()
    {
        $userId = auth()->id();

        $bookings = DB::table('bookings')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', $userId)
            ->select(
                'bookings.*',
                'rooms.room_number',
                'room_types.name as type_name'
            )
            ->orderBy('bookings.check_in_date', 'desc')
            ->get();

        return view('guest.mybookings', compact('bookings'));
    }

    // SUB-MENU REDIRECT VIEW PATHS (Membaca struktur view folder guest terbaru Anda)
  public function myStay()
    {
        $userId = auth()->id(); // Mengambil ID user yang sedang login

        // 1. Ambil data booking kamar aktif yang statusnya confirmed atau pending
        $currentBooking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'pending'])
            ->select(
                'bookings.id as booking_id',
                'bookings.check_in_date',
                'bookings.check_out_date',
                'bookings.guests_count',
                'room_types.name as room_name',
                'room_types.foto_url',
                'rooms.room_number',
                'bookings.total_price as room_bill'
            )
            ->first();

        // 2. Hitung total tagihan makanan dari restaurant_orders jika ada relasi guest_id (atau user_id)
        $restaurantBill = DB::table('restaurant_orders')
            ->where('guest_id', $userId) // Jika guest_id berelasi dengan id user
            ->where('status', 'ordered') // Yang belum lunas/paid
            ->sum('total_price');

        // 3. Ambil agenda aktivitas hari ini dari tabel facility_bookings
        $itineraries = DB::table('facility_bookings')
            ->where('user_id', $userId)
            ->where('booking_date', date('Y-m-d'))
            ->orderBy('booking_time', 'asc')
            ->get();

        return view('page.mystay', compact('currentBooking', 'restaurantBill', 'itineraries'));
    }
    public function roomService()
    {
        // 1. Ambil data menu makanan dari tabel restaurant_menus
        $menus = DB::table('restaurant_menus')
            ->select('id', 'name', 'description', 'price', 'foto_url')
            ->get();

        // 2. Ambil riwayat pesanan makanan terakhir dari tabel restaurant_orders
        $orderHistory = DB::table('restaurant_orders')
            ->where('guest_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('page.roomservice', compact('menus', 'orderHistory'));
    }
    public function restaurantOrders() { return view('guest.restaurantorders'); }
public function facilities()
    {
        $userId = auth()->id();

        // 1. Ambil data reservasi fasilitas aktif milik user dari tabel facility_bookings
        $myReservations = DB::table('facility_bookings')
            ->where('user_id', $userId)
            ->orderBy('booking_date', 'asc')
            ->orderBy('booking_time', 'asc')
            ->get();

        // 2. Buat data dummy koleksi fasilitas agar looping @foreach($facilities as $f) di Blade berjalan lancar
        $facilities = collect([
            (object)[
                'name' => 'Infinity Horizon Pool',
                'description' => 'Kolam renang tanpa batas yang menghadap langsung ke samudra Hindia dengan layanan cabana privat.',
                'image_url' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=600',
                'hours' => '06:00 AM - 10:00 PM',
                'access_type' => 'Complimentary',
                'requires_booking' => true
            ],
            (object)[
                'name' => 'Oasis Luxury Spa & Wellness',
                'description' => 'Pusat terapi kebugaran spiritual dengan ramuan aromaterapi tradisional khas Bali.',
                'image_url' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600',
                'hours' => '08:00 AM - 09:00 PM',
                'access_type' => 'Paid Service',
                'requires_booking' => true
            ],
            (object)[
                'name' => 'Elite Fitness Center',
                'description' => 'Fasilitas gym komplit dengan alat beban profesional dan pemandangan taman tropis.',
                'image_url' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=600',
                'hours' => '24 Hours Open',
                'access_type' => 'Complimentary',
                'requires_booking' => false
            ]
        ]);

        return view('page.facilitiesbooking', compact('myReservations', 'facilities'));
    }
    public function billingMatrix() { return view('guest.billingmatrix'); }

    /**
     * Memproses pesanan Gastronomy Fine Dining via AJAX sesuai tabel restaurant_orders Anda
     */
    public function placeGastronomyOrder(Request $request)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);

        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        // Cari profil guest berdasarkan email login user aktif
        $guest = DB::table('guests')->where('email', Auth::user()->email)->first();

        if (!$guest) {
            return response()->json([
                'success' => false, 
                'message' => 'Profil tamu Anda tidak ditemukan. Harap pastikan Anda sudah terdaftar di manifes hotel.'
            ], 404);
        }

        try {
            DB::table('restaurant_orders')->insert([
                'guest_id' => $guest->id,
                'total_price' => $request->total_price,
                'status' => 'ordered',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan kuliner dikonfirmasi! Tagihan disematkan ke manifes billing kamar Anda.'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses ke sistem dapur.'], 500);
        }
    }

    /**
     * Menyimpan pesanan slot waktu fasilitas via AJAX
     */
    public function bookFacility(Request $request)
    {
        // 1. Validasi input form secara ketat
        $request->validate([
            'facility_name' => 'required|string',
            'booking_date'  => 'required|date|after_or_equal:today',
            'booking_time'  => 'required',
            'guests_count'  => 'required|integer|min:1|max:10'
        ]);

        // 2. Cek apakah user sudah masuk ke dalam session login
        if (!Auth::check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Silakan login terlebih dahulu untuk memesan slot fasilitas.'
            ], 401);
        }

        // 3. Eksekusi penyimpanan data menggunakan Eloquent ORM
        try {
            FacilityBooking::create([
                'user_id'       => Auth::id(),
                'facility_name' => $request->facility_name,
                'booking_date'  => $request->booking_date,
                'booking_time'  => $request->booking_time,
                'guests_count'  => $request->guests_count,
                'notes'         => $request->notes ?? null,
                'status'        => 'confirmed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Slot waktu untuk ' . $request->facility_name . ' berhasil dipesan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengamankan slot waktu, silakan coba beberapa saat lagi.'
            ], 500);
        }
    }

    /**
     * Mengonfigurasi library Midtrans Core
     */
    protected function initMidtrans()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Meminta Snap Token Instan dari API Midtrans
     */
    public function getSnapToken(Request $request)
    {
        $request->validate(['booking_id' => 'required|integer']);
        $this->initMidtrans();

        $booking = DB::table('bookings')
            ->where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking || $booking->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Invoice tidak valid atau sudah lunas.'], 400);
        }

        // Membuat parameter transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => 'OA-' . $booking->id . '-' . time(), // Order ID unik dengan stempel waktu anti-duplicate
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
            'item_details' => [
                [
                    'id' => 'ROOM-' . $booking->room_id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Oasis Luxury Suite Reservation #' . $booking->id
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response()->json(['success' => true, 'token' => $snapToken]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Menangani Webhook Otomatis dari Server Midtrans (Settle Transaksi Latar Belakang)
     */
    public function handleMidtransCallback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        // Validasi keaslian signature key dari Midtrans
        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid Signature'], 403);
        }

        // Ekstrak ID asli booking dari string order_id (OA-{id}-{time})
        $orderParts = explode('-', $request->order_id);
        $bookingId = $orderParts[1] ?? null;

        $transactionStatus = $request->transaction_status;
        $statusToUpdate = null;

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $statusToUpdate = 'confirmed';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $statusToUpdate = 'cancelled';
        }

        if ($statusToUpdate && $bookingId) {
            DB::table('bookings')->where('id', $bookingId)->update([
                'status' => $statusToUpdate,
                'updated_at' => now()
            ]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Sinkronisasi Instan database lokal via Frontend Aksi Sukses Client
     */
    public function localPaymentSuccess(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|integer'
        ]);

        $booking = DB::table('bookings')
            ->where('id', $request->booking_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data reservasi tidak ditemukan.'], 404);
        }

        if ($booking->status === 'pending') {
            DB::table('bookings')
                ->where('id', $request->booking_id)
                ->update([
                    'status' => 'confirmed',
                    'updated_at' => now()
                ]);
        }

        return response()->json(['success' => true, 'message' => 'Database lokal berhasil diperbarui.']);
    }
}