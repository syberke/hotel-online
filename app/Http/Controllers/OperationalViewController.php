<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperationalViewController extends ExecutiveReportController
{
    public function adminFinanceView(Request $request)
    {
        $roomRevenue = DB::table('payments')
            ->whereNotNull('booking_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        $fbRevenue = DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        $otherRevenue = DB::table('payments')
            ->whereNull('booking_id')
            ->whereNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        $totalRevenue = $roomRevenue + $fbRevenue + $otherRevenue;
        $totalExpenses = 0;

        $stats = [
            'total_revenue' => $totalRevenue,
            'room_revenue' => $roomRevenue,
            'fb_revenue' => $fbRevenue,
            'other_revenue' => $otherRevenue,
            'expenses' => $totalExpenses,
            'net_profit' => $totalRevenue - $totalExpenses,
        ];

        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = DB::table('payments')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') ?: 0;
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
            'other' => round(($otherRevenue / $totalRevenue) * 100, 1),
        ] : ['room' => 0, 'fb' => 0, 'other' => 0];

        $query = DB::table('payments')
            ->leftJoin('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->leftJoin('guests as restaurant_guests', 'restaurant_orders.guest_id', '=', 'restaurant_guests.id')
            ->select(
                'payments.*',
                DB::raw("COALESCE(users.name, restaurant_guests.name, 'Outside Customer') as guest_name"),
                'bookings.status as booking_status',
                'restaurant_orders.status as order_status'
            );

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payments.id', 'like', "%{$search}%")
                    ->orWhereRaw('LOWER(COALESCE(users.name, restaurant_guests.name, ?)) LIKE ?', ['outside customer', '%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(payments.payment_method) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($request->filled('payment_status') && in_array($request->payment_status, ['pending', 'paid', 'failed'], true)) {
            $query->where('payments.payment_status', $request->payment_status);
        }

        $transactions = $query
            ->orderByDesc('payments.created_at')
            ->paginate(5)
            ->withQueryString();

        $transactions->getCollection()->transform(function ($transaction) {
            $status = strtolower(trim((string) $transaction->payment_status));
            $transaction->payment_status = in_array($status, ['pending', 'paid', 'failed'], true)
                ? $status
                : 'pending';
            return $transaction;
        });

        $methods = ['credit_card', 'cash', 'transfer', 'e_wallet'];
        $methodBreakdown = [];
        $paidRevenue = DB::table('payments')->where('payment_status', 'paid')->sum('amount') ?: 0;

        foreach ($methods as $method) {
            $amount = DB::table('payments')
                ->where('payment_method', $method)
                ->where('payment_status', 'paid')
                ->sum('amount') ?: 0;

            $methodBreakdown[$method] = [
                'amount' => $amount,
                'pct' => $paidRevenue > 0 ? round(($amount / $paidRevenue) * 100, 1) : 0,
            ];
        }

        return view('admin.finance', compact(
            'stats',
            'chartLabels',
            'polylineCoordinates',
            'shares',
            'transactions',
            'methodBreakdown'
        ));
    }

    public function adminRestaurantView(Request $request)
    {
        $today = now()->toDateString();

        $totalOrdersCount = DB::table('restaurant_orders')->count();
        $activeOrdersCount = DB::table('restaurant_orders')->whereIn('status', ['ordered', 'preparing'])->count();
        $completedOrdersCount = DB::table('restaurant_orders')->where('status', 'paid')->count();
        $totalRevenueSum = DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;

        $stats = [
            'total' => $totalOrdersCount,
            'active' => $activeOrdersCount,
            'completed' => $completedOrdersCount,
            'revenue' => $totalRevenueSum,
            'avg_value' => $completedOrdersCount > 0 ? $totalRevenueSum / $completedOrdersCount : 0,
        ];

        $query = DB::table('restaurant_orders')
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
            ->select('restaurant_orders.*', 'guests.name as guest_name', 'guests.phone as guest_phone', 'rooms.room_number');

        $currentTab = $request->get('tab', 'all');
        if ($currentTab === 'dine_in') {
            $query->whereNull('rooms.room_number');
        } elseif ($currentTab === 'room_service') {
            $query->whereNotNull('rooms.room_number');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('restaurant_orders.id', 'like', "%{$search}%")
                    ->orWhereRaw('LOWER(guests.name) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $orders = $query->orderByDesc('restaurant_orders.created_at')->paginate(5)->withQueryString();

        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = DB::table('payments')
                ->whereNotNull('restaurant_order_id')
                ->where('payment_status', 'paid')
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') ?: 0;
        }

        $maxRevenue = max($chartData) ?: 1;
        $points = [];
        foreach ($chartData as $index => $value) {
            $points[] = ($index * 100) . ',' . (130 - (($value / $maxRevenue) * 100));
        }
        $polylineCoordinates = implode(' ', $points);

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
                DB::raw('SUM(restaurant_order_details.quantity * restaurant_order_details.price) as total_revenue')
            )
            ->groupBy('restaurant_menus.name', 'restaurant_menus.foto_url')
            ->orderByDesc('total_qty')
            ->take(3)
            ->get();

        $menus = DB::table('restaurant_menus')->orderBy('name')->get();
        $mainTab = $request->get('view', 'orders');

        return view('admin.restaurant', compact(
            'stats',
            'orders',
            'currentTab',
            'chartLabels',
            'polylineCoordinates',
            'statusCounts',
            'topSellingItems',
            'menus',
            'mainTab'
        ));
    }

    public function adminFacilitiesView(Request $request)
    {
        $today = now()->toDateString();

        $totalBookings = DB::table('facility_bookings')->count();
        $todayBookings = DB::table('facility_bookings')->whereDate('booking_date', $today)->count();
        $totalFacilitiesCount = DB::table('facilities')->count();

        $todaySessionRows = DB::table('facility_bookings')
            ->whereDate('booking_date', $today)
            ->where('status', 'confirmed')
            ->select('facility_name', 'booking_time', DB::raw('SUM(guests_count) as booked_guests'))
            ->groupBy('facility_name', 'booking_time')
            ->get();

        $capacityByName = DB::table('facilities')->pluck('hourly_capacity', 'name');
        $utilizationSamples = [];
        foreach ($todaySessionRows as $session) {
            $capacity = (int) ($capacityByName[$session->facility_name] ?? 0);
            if ($capacity > 0) {
                $utilizationSamples[] = min(100, ((int) $session->booked_guests / $capacity) * 100);
            }
        }

        $utilizationRate = count($utilizationSamples) > 0
            ? round(array_sum($utilizationSamples) / count($utilizationSamples), 1)
            : 0;

        $stats = [
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'active_fac' => $totalFacilitiesCount,
            'total_fac' => $totalFacilitiesCount,
            'revenue' => 0,
            'utilization' => $utilizationRate,
        ];

        $query = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->leftJoin('bookings', function ($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select('facility_bookings.*', 'users.name as guest_name', 'users.phone as guest_phone', 'rooms.room_number');

        $currentTab = $request->get('tab', 'all');
        if ($currentTab === 'upcoming') {
            $query->whereDate('facility_bookings.booking_date', '>', $today)->where('facility_bookings.status', 'confirmed');
        } elseif ($currentTab === 'in_progress') {
            $query->whereDate('facility_bookings.booking_date', $today)->where('facility_bookings.status', 'confirmed');
        } elseif ($currentTab === 'completed') {
            $query->where('facility_bookings.status', 'completed');
        } elseif ($currentTab === 'cancelled') {
            $query->where('facility_bookings.status', 'cancelled');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('facility_bookings.id', 'like', "%{$search}%")
                    ->orWhereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhereRaw('LOWER(facility_bookings.facility_name) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        $bookings = $query
            ->orderByDesc('facility_bookings.booking_date')
            ->orderByDesc('facility_bookings.booking_time')
            ->paginate(5)
            ->withQueryString();

        $facilitiesGrid = DB::table('facilities')->orderBy('name')->get();
        $allFacilityBookings = DB::table('facility_bookings')->count();

        foreach ($facilitiesGrid as $facility) {
            $bookedGuests = DB::table('facility_bookings')
                ->where('facility_name', $facility->name)
                ->whereDate('booking_date', $today)
                ->where('status', 'confirmed')
                ->sum('guests_count') ?: 0;

            $slots = DB::table('facility_bookings')
                ->where('facility_name', $facility->name)
                ->whereDate('booking_date', $today)
                ->where('status', 'confirmed')
                ->distinct()
                ->count('booking_time');

            $capacity = (int) $facility->hourly_capacity;
            $facility->computed_util = ($capacity > 0 && $slots > 0)
                ? min(100, round(($bookedGuests / ($capacity * $slots)) * 100, 1))
                : 0;
        }

        $asideStats = [
            'upcoming' => DB::table('facility_bookings')->whereDate('booking_date', '>', $today)->count(),
            'in_house' => DB::table('facility_bookings')->whereDate('booking_date', $today)->where('status', 'confirmed')->count(),
            'completed' => DB::table('facility_bookings')->where('status', 'completed')->count(),
            'cancelled' => DB::table('facility_bookings')->where('status', 'cancelled')->count(),
            'revenue' => 0,
        ];

        $chartShares = [];
        foreach ($facilitiesGrid as $facility) {
            $count = DB::table('facility_bookings')->where('facility_name', $facility->name)->count();
            $chartShares[$facility->name] = $allFacilityBookings > 0
                ? round(($count / $allFacilityBookings) * 100, 1)
                : 0;
        }

        $popularFacilities = DB::table('facility_bookings')
            ->select('facility_name', DB::raw('COUNT(id) as total_count'))
            ->groupBy('facility_name')
            ->orderByDesc('total_count')
            ->take(3)
            ->get();

        foreach ($popularFacilities as $popular) {
            $facility = $facilitiesGrid->firstWhere('name', $popular->facility_name);
            $popular->image = $facility->image_url ?? null;
        }

        return view('admin.facilities', compact(
            'stats',
            'bookings',
            'currentTab',
            'facilitiesGrid',
            'asideStats',
            'chartShares',
            'popularFacilities'
        ));
    }
}
