<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data agregat riil dari database PostgreSQL Anda
        $totalRevenue = DB::table('payments')->where('payment_status', 'paid')->sum('amount');
        $todayCheckins = DB::table('bookings')->whereDate('check_in_date', today())->count();
        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;
        $vacantRooms = DB::table('rooms')->where('status', 'available')->count();
        $restaurantRevenue = DB::table('restaurant_orders')->sum('total_price');

        // 2. Ambil data reservasi hari ini untuk tabel data-grid
        $liveReservations = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('bookings.id', 'users.name as guest_name', 'room_types.name as room_type', 'bookings.check_in_date', 'bookings.check_out_date', 'bookings.status')
            ->limit(5)
            ->get();

        // 3. Ambil peta status kamar fisik
        $roomRack = DB::table('rooms')->select('room_number', 'status')->get();

        return view('dashboard.admin', compact(
            'totalRevenue', 'todayCheckins', 'occupancyRate', 'vacantRooms', 
            'restaurantRevenue', 'liveReservations', 'roomRack'
        ));
    }
}