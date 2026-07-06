<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionistDeskController extends Controller
{
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
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->where('users.role', 'guest')
            ->select(
                'users.id as user_id',
                'users.name as guest_name',
                'users.email as guest_email',
                DB::raw('COALESCE(guests.phone, users.phone) as guest_phone'),
                DB::raw('COALESCE(guests.address, users.address) as guest_address'),
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
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('bookings as active_booking')
                  ->whereColumn('active_booking.user_id', 'users.id')
                  ->where('active_booking.status', 'checked_in');
            });
        } elseif ($currentTab == 'checked_out') {
            $query->whereExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('bookings as completed_booking')
                  ->whereColumn('completed_booking.user_id', 'users.id')
                  ->where('completed_booking.status', 'checked_out');
            })->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                  ->from('bookings as active_booking')
                  ->whereColumn('active_booking.user_id', 'users.id')
                  ->where('active_booking.status', 'checked_in');
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
                ->leftJoin('guests', function ($join) {
                    $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
                })
                ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('users.id', $selectedGuestId)
                ->select(
                    'users.*',
                    'bookings.*',
                    'rooms.room_number',
                    'room_types.name as room_type',
                    DB::raw('COALESCE(guests.phone, users.phone) as phone'),
                    DB::raw('COALESCE(guests.address, users.address) as address')
                )
                ->first();

            if ($selectedGuest && $currentTab === 'in_house' && $selectedGuest->current_status !== 'checked_in') {
                $selectedGuest = null;
            } elseif ($selectedGuest && $currentTab === 'checked_out' && $selectedGuest->current_status !== 'checked_out') {
                $selectedGuest = null;
            }
        } elseif (count($guestsList->items()) > 0) {
            $firstItem = $guestsList->items()[0];
            $selectedGuest = DB::table('users')
                ->leftJoin('guests', function ($join) {
                    $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
                })
                ->leftJoin('bookings', 'users.id', '=', 'bookings.user_id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('users.id', $firstItem->user_id)
                ->select(
                    'users.*',
                    'bookings.*',
                    'rooms.room_number',
                    'room_types.name as room_type',
                    DB::raw('COALESCE(guests.phone, users.phone) as phone'),
                    DB::raw('COALESCE(guests.address, users.address) as address')
                )
                ->first();
        }

        return view('receptionist.guests', compact(
            'inHouseGuests', 'checkinsToday', 'checkoutsToday', 'totalGuestsAllTime', 'revenueThisMonth',
            'guestsList', 'currentTab', 'tabCounters', 'selectedGuest'
        ));
    }
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
}