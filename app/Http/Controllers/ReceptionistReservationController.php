<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionistReservationController extends Controller
{
    public function receptionistReservationsView(Request $request)
    {
        $today = now()->format('Y-m-d');
        $startOfMonth = now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->endOfMonth()->format('Y-m-d');

        $totalReservations = DB::table('bookings')->count();
        $arrivalsCount = DB::table('bookings')->whereDate('check_in', $today)->count();
        $departuresCount = DB::table('bookings')->whereDate('check_out', $today)->count();
        $inHouseCount = DB::table('bookings')->where('status', 'checked_in')->count();

        $revenueThisMonth = DB::table('payments')
            ->whereBetween('created_at', [$startOfMonth . ' 00:00:00', $endOfMonth . ' 23:59:59'])
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        $query = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'bookings.*',
                'users.name as guest_name',
                'guests.id as guest_record_id',
                'guests.identity_number',
                'guests.phone as guest_phone',
                'guests.address as guest_address',
                'rooms.room_number',
                'room_types.name as room_type'
            );

        $currentTab = $request->get('status_tab', 'all');
        if ($currentTab === 'confirmed') {
            $query->where('bookings.status', 'confirmed');
        } elseif ($currentTab === 'tentative') {
            $query->where('bookings.status', 'pending');
        } elseif ($currentTab === 'cancelled' || $currentTab === 'canceled') {
            $query->where('bookings.status', 'canceled');
        } elseif ($currentTab === 'no_show') {
            $query->whereRaw('1 = 0');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $cleanSearch = ltrim($search, '#RES-OA-');
                $q->where('bookings.id', 'like', "%{$cleanSearch}%")
                    ->orWhereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('guests.phone', 'like', "%{$search}%")
                    ->orWhere('guests.identity_number', 'like', "%{$search}%");
            });
        }

        $bookingsList = $query
            ->orderBy('bookings.created_at', 'desc')
            ->paginate(5)
            ->withQueryString();

        $tabCounters = [
            'all' => DB::table('bookings')->count(),
            'confirmed' => DB::table('bookings')->where('status', 'confirmed')->count(),
            'tentative' => DB::table('bookings')->where('status', 'pending')->count(),
            'cancelled' => DB::table('bookings')->where('status', 'canceled')->count(),
            'no_show' => 0,
        ];

        $upcomingArrivals = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereDate('bookings.check_in', '>=', $today)
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->select('bookings.*', 'users.name as guest_name', 'rooms.room_number', 'room_types.name as room_type')
            ->orderBy('bookings.check_in', 'asc')
            ->take(2)
            ->get();

        $trendPointsArrivals = [];
        $trendPointsDepartures = [];
        for ($i = 6; $i >= 0; $i--) {
            $dayCheck = now()->subDays($i)->format('Y-m-d');
            $dayArrivals = DB::table('bookings')->whereDate('check_in', $dayCheck)->count();
            $dayDepartures = DB::table('bookings')->whereDate('check_out', $dayCheck)->count();
            $trendPointsArrivals[] = 120 - min(100, $dayArrivals * 10);
            $trendPointsDepartures[] = 120 - min(100, $dayDepartures * 10);
        }

        $svgArrivalsPath = "M 0,{$trendPointsArrivals[0]} L 100,{$trendPointsArrivals[1]} L 200,{$trendPointsArrivals[2]} L 300,{$trendPointsArrivals[3]} L 400,{$trendPointsArrivals[4]} L 500,{$trendPointsArrivals[5]} L 600,{$trendPointsArrivals[6]}";
        $svgDeparturesPath = "M 0,{$trendPointsDepartures[0]} L 100,{$trendPointsDepartures[1]} L 200,{$trendPointsDepartures[2]} L 300,{$trendPointsDepartures[3]} L 400,{$trendPointsDepartures[4]} L 500,{$trendPointsDepartures[5]} L 600,{$trendPointsDepartures[6]}";

        return view('receptionist.reservations', compact(
            'totalReservations',
            'arrivalsCount',
            'departuresCount',
            'inHouseCount',
            'revenueThisMonth',
            'bookingsList',
            'currentTab',
            'tabCounters',
            'upcomingArrivals',
            'svgArrivalsPath',
            'svgDeparturesPath'
        ));
    }
}
