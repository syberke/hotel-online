<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOperationController extends Controller
{
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
    public function adminDeleteFacility($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki akses modifikasi data.');
        }

        DB::table('facilities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus dari sistem inventori hotel.');
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

        // Calculate room count per room type
        $roomCount = [];
        foreach ($roomTypesList as $type) {
            $roomCount[$type->id] = $rawRooms->where('room_type_id', $type->id)->count();
        }

        return view('admin.rooms&inventory', compact('stats', 'rooms', 'summary', 'roomTypesList', 'roomCount'));
    }

    /**
     * ROOM TYPE CRUD OPERATIONS
     */
    public function storeRoomType(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi tipe kamar.');
        }

        $request->validate([
            'name'        => 'required|string|unique:room_types,name',
            'description' => 'nullable|string',
            'capacity'    => 'required|integer|min:1|max:10',
            'price'       => 'required|numeric|min:0'
        ]);

        DB::table('room_types')->insert([
            'name'        => $request->name,
            'description' => $request->description,
            'capacity'    => $request->capacity,
            'price'       => (int) $request->price,
            'created_at'  => now(),
            'updated_at'  => now()
        ]);

        return redirect()->back()->with('success', 'Tipe kamar "' . $request->name . '" berhasil ditambahkan.');
    }

    public function updateRoomType(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi tipe kamar.');
        }

        $request->validate([
            'name'        => 'required|string|unique:room_types,name,' . $id,
            'description' => 'nullable|string',
            'capacity'    => 'required|integer|min:1|max:10',
            'price'       => 'required|numeric|min:0'
        ]);

        DB::table('room_types')->where('id', $id)->update([
            'name'        => $request->name,
            'description' => $request->description,
            'capacity'    => $request->capacity,
            'price'       => (int) $request->price,
            'updated_at'  => now()
        ]);

        return redirect()->back()->with('success', 'Tipe kamar "' . $request->name . '" berhasil diperbarui.');
    }

    public function deleteRoomType($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses menghapus tipe kamar.');
        }

        $roomType = DB::table('room_types')->find($id);
        if (!$roomType) {
            return redirect()->back()->with('error', 'Tipe kamar tidak ditemukan.');
        }

        // Check if room type is used by any rooms
        $usedCount = DB::table('rooms')->where('room_type_id', $id)->count();
        if ($usedCount > 0) {
            return redirect()->back()->with('error', "Tidak bisa menghapus tipe kamar yang masih digunakan oleh {$usedCount} kamar. Ubah kamar terlebih dahulu.");
        }

        DB::table('room_types')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Tipe kamar "' . $roomType->name . '" berhasil dihapus.');
    }
}