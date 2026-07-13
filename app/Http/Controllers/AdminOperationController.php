<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOperationController extends Controller
{
    public function adminFacilitiesView(Request $request)
    {
        return app(ExecutiveReportController::class)->adminFacilitiesView($request);
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

        $existingFacility = DB::table('facilities')->where('id', $id)->first();
        if (!$existingFacility) {
            return redirect()->back()->with('error', 'Fasilitas tidak ditemukan.');
        }

        DB::table('facilities')->where('id', $id)->update([
            'name'       => $request->name,
            'category'   => $request->category,
            'hours'      => $request->hours,
            'image_url'  => $request->image_url ?: $existingFacility->image_url,
            'updated_at' => now()
        ]);

        return redirect()->back()->with('success', 'Konfigurasi area fasilitas berhasil diperbarui.');
    }
      public function adminDetailReservation($id)
    {
        $booking = \App\Models\Booking::with(['user.guestProfile', 'room.roomType', 'payments'])->find($id);
        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
        
        $latestPayment = $booking->payments->last();
        $guestProfile = $booking->user?->guestProfile;
        
        return response()->json([
            'success' => true,
            'id' => $booking->id,
            'guest_id' => $guestProfile?->id,
            'identity_number' => $guestProfile?->identity_number,
            'guest_name' => $booking->user->name ?? 'N/A',
            'guest_email' => $booking->user->email ?? 'N/A',
            'guest_phone' => $guestProfile?->phone ?: ($booking->user->phone ?? '-'),
            'guest_address' => $guestProfile?->address ?: ($booking->user->address ?? 'Tidak ada alamat'),
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

        $facility = DB::table('facilities')->where('id', $id)->first();
        if (!$facility) {
            return redirect()->back()->with('error', 'Fasilitas tidak ditemukan.');
        }

        $hasBookings = DB::table('facility_bookings')
            ->where('facility_name', $facility->name)
            ->exists();

        if ($hasBookings) {
            return redirect()->back()->with('error', 'Fasilitas tidak dapat dihapus karena sudah memiliki riwayat reservasi.');
        }

        DB::table('facilities')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus dari sistem inventori hotel.');
    }

    public function adminDeleteRestaurantOrder($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager hanya dapat melihat pesanan.');
        }

        $order = DB::table('restaurant_orders')->where('id', $id)->first();
        if (!$order) {
            return redirect()->back()->with('error', 'Pesanan tidak ditemukan.');
        }

        if ($order->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan harus dibatalkan terlebih dahulu sebelum dihapus.');
        }

        if (DB::table('payments')->where('restaurant_order_id', $id)->exists()) {
            return redirect()->back()->with('error', 'Pesanan tidak dapat dihapus karena sudah memiliki jejak transaksi pembayaran.');
        }

        DB::transaction(function () use ($id) {
            DB::table('restaurant_order_details')->where('restaurant_order_id', $id)->delete();
            DB::table('restaurant_orders')->where('id', $id)->delete();
        });

        return redirect()->back()->with('success', 'Pesanan yang dibatalkan berhasil dihapus.');
    }

    public function adminDeleteFacilityBooking($id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Manager hanya dapat melihat reservasi fasilitas.');
        }

        $booking = DB::table('facility_bookings')->where('id', $id)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Reservasi fasilitas tidak ditemukan.');
        }

        if ($booking->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Reservasi fasilitas harus dibatalkan terlebih dahulu sebelum dihapus.');
        }

        DB::table('facility_bookings')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Reservasi fasilitas yang dibatalkan berhasil dihapus.');
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
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%']);
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

        $existingMenu = DB::table('restaurant_menus')->where('id', $id)->first();
        if (!$existingMenu) {
            return redirect()->back()->with('error', 'Menu tidak ditemukan.');
        }

        DB::table('restaurant_menus')->where('id', $id)->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'foto_url'    => $request->foto_url ?: $existingMenu->foto_url,
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
                $needle = '%' . strtolower($search) . '%';
                $q->whereRaw('LOWER(name) LIKE ?', [$needle])
                ->orWhereRaw('LOWER(email) LIKE ?', [$needle])
                ->orWhereRaw('LOWER(role) LIKE ?', [$needle]);
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
    private function roomBookingManifests(string $today)
    {
        return DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->whereNotNull('bookings.room_id')
            ->whereIn('bookings.status', ['pending', 'confirmed', 'checked_in'])
            ->where('bookings.check_out', '>', $today)
            ->select(
                'bookings.id',
                'bookings.room_id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.guests_count',
                'bookings.status',
                'users.name as guest_name',
                'users.email as guest_email'
            )
            ->orderBy('bookings.check_in')
            ->get()
            ->groupBy('room_id');
    }

    private function resolveRoomDisplayState(object $room, $roomBookings, string $today): array
    {
        $roomBookings ??= collect();

        $occupiedBooking = $roomBookings->first(function ($booking) use ($today) {
            return $booking->status === 'checked_in'
                && $booking->check_in <= $today
                && $booking->check_out > $today;
        });

        if ($occupiedBooking) {
            return ['occupied', $occupiedBooking];
        }

        $reservedBooking = $roomBookings->first();
        if ($reservedBooking) {
            return ['reserved', $reservedBooking];
        }

        // Status occupied yang tertinggal setelah checkout tidak boleh membuat
        // kamar terus terlihat ditempati. Status fisik lain tetap dipertahankan.
        $physicalStatus = $room->status === 'occupied' ? 'available' : $room->status;

        return [$physicalStatus, null];
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

        $today = now()->toDateString();
        $roomBookings = $this->roomBookingManifests($today)->get($room->id, collect());
        [$displayStatus, $activeBooking] = $this->resolveRoomDisplayState($room, $roomBookings, $today);

        $room->physical_status = $room->status;
        $room->status = $displayStatus;

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
        $today = now()->toDateString();
        $bookingManifests = $this->roomBookingManifests($today);

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

        // Status di bawah adalah status efektif untuk layar. Nilai fisik pada
        // rooms.status tidak ditimpa oleh status reservasi.
        foreach ($rawRooms as $room) {
            $room->physical_status = $room->status;
            [$room->status, $room->active_booking] = $this->resolveRoomDisplayState(
                $room,
                $bookingManifests->get($room->id, collect()),
                $today
            );
        }

        $totalRooms = $rawRooms->count();
        $statsDenominator = max(1, $totalRooms);
        $availableRooms = $rawRooms->where('status', 'available')->count();
        $reservedRooms = $rawRooms->where('status', 'reserved')->count();
        $occupiedRooms = $rawRooms->where('status', 'occupied')->count();
        $maintenanceRooms = $rawRooms->where('status', 'maintenance')->count();
        $dirtyRooms = $rawRooms->where('status', 'dirty')->count();

        $stats = [
            'total' => $totalRooms,
            'available' => $availableRooms,
            'available_pct' => round(($availableRooms / $statsDenominator) * 100, 1),
            'reserved' => $reservedRooms,
            'reserved_pct' => round(($reservedRooms / $statsDenominator) * 100, 1),
            'occupied' => $occupiedRooms,
            'occupied_pct' => round(($occupiedRooms / $statsDenominator) * 100, 1),
            'maintenance' => $maintenanceRooms,
            'maintenance_pct' => round(($maintenanceRooms / $statsDenominator) * 100, 1),
            'cleaning' => $dirtyRooms,
            'cleaning_pct' => round(($dirtyRooms / $statsDenominator) * 100, 1),
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
                ->orWhereRaw('LOWER(room_types.name) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        // Jalankan Filter Kategori Status
        if ($request->filled('status_filter')) {
            $statusFilter = $request->status_filter;
            $matchingRoomIds = $rawRooms->where('status', $statusFilter)->pluck('id')->all();
            $query->whereIn('rooms.id', $matchingRoomIds);
        }

        $rooms = $query->orderBy('rooms.room_number', 'asc')->paginate(10)->withQueryString();

        $roomDisplayStates = $rawRooms->keyBy('id');
        foreach ($rooms->items() as $room) {
            if ($displayRoom = $roomDisplayStates->get($room->id)) {
                $room->physical_status = $displayRoom->physical_status;
                $room->status = $displayRoom->status;
                $room->active_booking = $displayRoom->active_booking;
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
                    'reserved' => $typeRooms->where('status', 'reserved')->count(),
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
