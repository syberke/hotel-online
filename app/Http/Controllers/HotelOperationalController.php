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

  
 
   
    /**
     * ======================================================================
     * NEW WORKER COMPONENT: RESTAURANT MENU CRUD (TODAY'S MENU)
     * ======================================================================
     */


 



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
  
    }