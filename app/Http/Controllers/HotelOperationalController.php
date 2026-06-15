<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Http; 
use App\Models\FacilityBooking;
class HotelOperationalController extends Controller
{
    /**
     * Menampilkan Landing Page Utama dengan data kamar dinamis menggunakan ORM
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
                'room_types.price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price_per_night', 'room_types.foto_url')
            ->orderBy('room_types.price_per_night', 'asc')
            ->get();

        return view('home', compact('roomsLiveList'));
    }

    /**
     * Memproses Cek Ketersediaan Kamar via AJAX & Instant Booking via ORM Eloquent
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'suite_type' => 'required|string'
        ]);

        // Cari kamar berdasarkan tipe melalui Query Builder (Agnostik Cross-Database)
        $room = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('room_types.name', $request->suite_type)
            ->where('rooms.status', 'available')
            ->select('rooms.id as room_id', 'room_types.price_per_night', 'rooms.room_number')
            ->first();

        // JIKA RUNNING VIA AJAX: Mode Check Only (Sebelum Tombol Berubah Menjadi Book Now)
        if ($request->wantsJson() && $request->has('mode_check_only')) {
            if (!$room) {
                return response()->json(['available' => false, 'message' => 'Maaf, tipe suite ' . $request->suite_type . ' sudah penuh pada tanggal tersebut.']);
            }
            return response()->json(['available' => true, 'message' => 'Kamar tersedia! Silakan lanjutkan pemesanan oasis Anda.']);
        }

        // --- PROSES LANJUTAN JIKA TOMBOL SUDAH BERUBAH MENJADI "BOOK NOW" ---
        if (!$room) {
            return redirect('/#rooms')->with('error', 'Maaf, tipe suite sudah penuh.');
        }

        // Kalkulasi Total Hari & Harga
        $days = (strtotime($request->check_out) - strtotime($request->check_in)) / (60 * 60 * 24);
        $totalPrice = $room->price_per_night * $days;

        if (Auth::check()) {
            DB::transaction(function () use ($request, $room, $totalPrice) {
                
                // Konfigurasi dinamis jumlah tamu dari select option form
                $guestsCount = 2;
                if ($request->guests === '1 Adult') { $guestsCount = 1; }
                elseif (str_contains($request->guests, '3')) { $guestsCount = 3; }
                elseif (str_contains($request->guests, '4')) { $guestsCount = 4; }

                // 1. Buat data booking menggunakan Eloquent ORM
                $booking = Booking::create([
                    'user_id'        => Auth::id(), 
                    'room_id'        => $room->room_id,
                    'check_in_date'  => $request->check_in,
                    'check_out_date' => $request->check_out,
                    'guests_count'   => $guestsCount,
                    'total_price'    => $totalPrice,
                    'status'         => 'pending'
                ]);

                // 2. Buat data tagihan payment link otomatis
                Payment::create([
                    'booking_id'     => $booking->id, 
                    'amount'         => $totalPrice,
                    'payment_method' => 'transfer',
                    'payment_status' => 'pending'
                ]);
            });

            return $request->wantsJson() 
                ? response()->json(['success' => true, 'redirect' => route('dashboard')])
                : redirect('/dashboard')->with('success', 'Reservasi kamar berhasil dibuat via ORM.');
        }

        return $request->wantsJson()
            ? response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Silakan login terlebih dahulu.'])
            : redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
    }

    /**
     * Menampilkan Portal Utama Tamu dengan Data Real-Time & Live Weather API
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

        return view('dashboard.guest', compact('bookings', 'activeOrders', 'temperature'));
    }

    /**
     * Menampilkan halaman daftar reservasi milik tamu (My Bookings)
     */
    public function myBookings()
    {
        return $this->dashboard(); // Reuse data terstruktur dari fungsi dashboard agar hemat memory & aman
    }

    /**
     * Menampilkan halaman status menginap aktif (My Stay)
     */
    public function myStay()
    {
        return $this->dashboard();
    }

    /**
     * Layanan pemesanan kamar / Room Service
     */
    public function roomService()
    {
        return $this->dashboard();
    }

    /**
     * Menampilkan daftar pesanan restoran milik tamu
     */
    public function restaurantOrders()
    {
        return $this->dashboard();
    }

    /**
     * Pemesanan fasilitas hotel (Spa, Pool, Gym)
     */
    public function facilitiesBooking()
    {
        return $this->dashboard();
    }

    /**
     * Menampilkan rincian tagihan / Billing Matrix
     */
    public function billingMatrix()
    {
        return $this->dashboard();
    }


/**
 * Menampilkan halaman fasilitas komprehensif
 */
public function facilitiesIndex()
{
    return view('facilities'); // Nama file blade fasilitas Anda
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
}