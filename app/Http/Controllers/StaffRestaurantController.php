<?php

namespace App\Http\Controllers;

use App\Models\RestaurantReservation;
use App\Models\RestaurantVenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffRestaurantController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless(in_array($request->user()?->role, ['admin', 'manager'], true), 403);

        $mainTab = in_array($request->get('view'), ['orders', 'venues'], true)
            ? $request->get('view')
            : 'orders';
        $currentTab = in_array($request->get('tab'), ['all', 'dine_in', 'room_service'], true)
            ? $request->get('tab')
            : 'all';
        $today = now()->toDateString();

        $totalOrders = DB::table('restaurant_orders')->count();
        $completedOrders = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $restaurantRevenue = (float) (DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0);

        $stats = [
            'total' => $totalOrders,
            'active' => DB::table('restaurant_orders')->whereIn('status', ['ordered', 'preparing'])->count(),
            'completed' => $completedOrders,
            'revenue' => $restaurantRevenue,
            'avg_value' => $completedOrders > 0 ? $restaurantRevenue / $completedOrders : 0,
        ];

        $orderQuery = DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->leftJoin('users', function ($join) {
                $join->on(DB::raw('LOWER(guests.email)'), '=', DB::raw('LOWER(users.email)'));
            })
            ->leftJoin('bookings', function ($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'restaurant_orders.*',
                'guests.name as guest_name',
                'guests.phone as guest_phone',
                'rooms.room_number',
            );

        if ($currentTab === 'dine_in') {
            $orderQuery->whereNull('rooms.room_number');
        } elseif ($currentTab === 'room_service') {
            $orderQuery->whereNotNull('rooms.room_number');
        }

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $needle = '%' . strtolower($search) . '%';
            $cleanId = preg_replace('/\D+/', '', $search);
            $orderQuery->where(function ($query) use ($needle, $cleanId) {
                $query->whereRaw('LOWER(guests.name) LIKE ?', [$needle]);
                if ($cleanId !== '') {
                    $query->orWhere('restaurant_orders.id', (int) $cleanId);
                }
            });
        }

        $orders = $orderQuery
            ->orderByDesc('restaurant_orders.created_at')
            ->paginate(8)
            ->withQueryString();

        $statusCounts = [
            'completed' => DB::table('restaurant_orders')->where('status', 'paid')->count(),
            'progress' => DB::table('restaurant_orders')->where('status', 'preparing')->count(),
            'pending' => DB::table('restaurant_orders')->where('status', 'ordered')->count(),
            'cancelled' => DB::table('restaurant_orders')->where('status', 'cancelled')->count(),
        ];

        $topSellingItems = DB::table('restaurant_order_details')
            ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
            ->select(
                'restaurant_menus.name',
                'restaurant_menus.foto_url',
                DB::raw('SUM(restaurant_order_details.quantity) as total_qty'),
                DB::raw('SUM(restaurant_order_details.quantity * restaurant_order_details.price) as total_revenue'),
            )
            ->groupBy('restaurant_menus.name', 'restaurant_menus.foto_url')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        $chartLabels = [];
        $chartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartValues[] = (float) (DB::table('payments')
                ->whereNotNull('restaurant_order_id')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') ?: 0);
        }
        $maxValue = max($chartValues) ?: 1;
        $points = [];
        foreach ($chartValues as $index => $value) {
            $points[] = ($index * 100) . ',' . (130 - (($value / $maxValue) * 100));
        }
        $polylineCoordinates = implode(' ', $points);

        $venues = RestaurantVenue::query()
            ->withCount('reservations')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        $venueReservations = RestaurantReservation::query()
            ->with(['venue', 'user'])
            ->orderByDesc('reservation_date')
            ->orderByDesc('reservation_time')
            ->take(25)
            ->get();
        $venueStats = [
            'total' => $venues->count(),
            'active' => $venues->where('is_active', true)->count(),
            'reservable' => $venues->where('reservation_enabled', true)->count(),
            'reservations' => $venueReservations->count(),
        ];

        return view('admin.restaurant', compact(
            'mainTab',
            'currentTab',
            'stats',
            'orders',
            'statusCounts',
            'topSellingItems',
            'chartLabels',
            'polylineCoordinates',
            'venues',
            'venueReservations',
            'venueStats',
        ));
    }
}
