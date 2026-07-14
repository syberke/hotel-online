<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LiveReportViewController extends Controller
{
    public function adminReportsView(Request $request)
    {
        $roomRevenue = (float) (DB::table('payments')
            ->whereNotNull('booking_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0);

        $fbRevenue = (float) (DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0);

        // Schema sederhana belum memiliki nominal billing fasilitas.
        // Jangan membentuk revenue fasilitas dari multiplier/fallback palsu.
        $facRevenue = 0.0;
        $totalRevenue = $roomRevenue + $fbRevenue;

        $totalPhysicalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $occupancyRate = $totalPhysicalRooms > 0
            ? round(($occupiedRooms / $totalPhysicalRooms) * 100, 1)
            : 0;

        $roomsSold = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->count();
        $adr = $roomsSold > 0 ? $roomRevenue / $roomsSold : 0;
        $revpar = $adr * ($occupancyRate / 100);

        $totalBookingsCount = DB::table('bookings')->count();
        $totalGuestsCount = DB::table('bookings')->sum('guests_count') ?: 0;

        $chartLabels = [];
        $chartData = [];
        $barHeights = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->toDateString();
            $chartLabels[] = $date->format('d M');
            $chartData[] = (float) (DB::table('payments')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $dateString)
                ->sum('amount') ?: 0);

            $occupiedOnDate = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
                ->whereDate('check_in', '<=', $dateString)
                ->whereDate('check_out', '>', $dateString)
                ->count();

            $barHeights[] = $totalPhysicalRooms > 0
                ? min(100, round(($occupiedOnDate / $totalPhysicalRooms) * 100))
                : 0;
        }

        $maxRevenue = max($chartData) ?: 1;
        $points = [];
        foreach ($chartData as $index => $value) {
            $points[] = ($index * 100) . ',' . (120 - (($value / $maxRevenue) * 80));
        }
        $polylineCoordinates = implode(' ', $points);

        $shares = $totalRevenue > 0 ? [
            'room' => round(($roomRevenue / $totalRevenue) * 100, 1),
            'fb' => round(($fbRevenue / $totalRevenue) * 100, 1),
            'other' => 0,
        ] : ['room' => 0, 'fb' => 0, 'other' => 0];

        $topRoomTypesReport = [];
        foreach (DB::table('room_types')->orderBy('name')->get() as $index => $roomType) {
            $bookings = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('rooms.room_type_id', $roomType->id)
                ->whereIn('bookings.status', ['confirmed', 'checked_in', 'checked_out'])
                ->select('bookings.id', 'bookings.check_in', 'bookings.check_out')
                ->get();

            $nightsSold = 0;
            foreach ($bookings as $booking) {
                $nightsSold += max(1, Carbon::parse($booking->check_in)->diffInDays(Carbon::parse($booking->check_out)));
            }

            $bookingIds = $bookings->pluck('id');
            $typeRevenue = $bookingIds->isEmpty()
                ? 0
                : (float) (DB::table('payments')
                    ->whereIn('booking_id', $bookingIds)
                    ->where('payment_status', 'paid')
                    ->sum('amount') ?: 0);

            $topRoomTypesReport[] = [
                'index' => $index + 1,
                'name' => $roomType->name,
                'sold' => $nightsSold,
                'revenue' => $typeRevenue,
                'pct' => $roomRevenue > 0 ? round(($typeRevenue / $roomRevenue) * 100, 1) : 0,
            ];
        }

        usort($topRoomTypesReport, static fn (array $a, array $b) => $b['revenue'] <=> $a['revenue']);

        $totalFbOrders = DB::table('restaurant_orders')->count();
        $completedFbOrders = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $avgOrderValue = $completedFbOrders > 0 ? $fbRevenue / $completedFbOrders : 0;

        $topSellingMenus = DB::table('restaurant_order_details')
            ->join('restaurant_orders', 'restaurant_order_details.restaurant_order_id', '=', 'restaurant_orders.id')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->where('restaurant_orders.status', 'paid')
            ->select(
                'restaurant_menus.name',
                DB::raw('SUM(restaurant_order_details.quantity) as qty_sold'),
                DB::raw('SUM(restaurant_order_details.quantity * restaurant_order_details.price) as gross_rev')
            )
            ->groupBy('restaurant_menus.name')
            ->orderByDesc('qty_sold')
            ->take(5)
            ->get();

        $totalFacBookings = DB::table('facility_bookings')->count();
        $completedFacSessions = DB::table('facility_bookings')->where('status', 'completed')->count();
        $popularFacilities = DB::table('facility_bookings')
            ->select(
                'facility_name',
                DB::raw('COUNT(id) as total_sessions'),
                DB::raw('SUM(guests_count) as total_guests')
            )
            ->groupBy('facility_name')
            ->orderByDesc('total_sessions')
            ->get();

        $currentTab = $request->get('tab', 'overview');
        $stats = [
            'total_revenue' => $totalRevenue,
            'occupancy' => $occupancyRate,
            'bookings' => $totalBookingsCount,
            'guests' => $totalGuestsCount,
            'adr' => $adr,
            'revpar' => $revpar,
        ];

        return view('admin.reports', compact(
            'roomRevenue',
            'fbRevenue',
            'facRevenue',
            'totalRevenue',
            'occupancyRate',
            'totalBookingsCount',
            'totalGuestsCount',
            'adr',
            'revpar',
            'chartLabels',
            'chartData',
            'barHeights',
            'polylineCoordinates',
            'shares',
            'topRoomTypesReport',
            'totalFbOrders',
            'completedFbOrders',
            'avgOrderValue',
            'topSellingMenus',
            'totalFacBookings',
            'completedFacSessions',
            'popularFacilities',
            'stats',
            'currentTab'
        ));
    }
}
