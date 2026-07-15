<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CoreDashboardController extends Controller
{
    public function adminDashboardView()
    {
        $totalReservations = DB::table('bookings')->count();
        $lastWeekReservations = DB::table('bookings')->where('created_at', '<', now()->subWeek())->count();
        $reservationDiff = $lastWeekReservations > 0
            ? round((($totalReservations - $lastWeekReservations) / $lastWeekReservations) * 100, 1)
            : 0;

        $totalGuests = DB::table('bookings')->sum('guests_count') ?: 0;
        $lastWeekGuests = DB::table('bookings')->where('created_at', '<', now()->subWeek())->sum('guests_count') ?: 0;
        $guestDiff = $lastWeekGuests > 0
            ? round((($totalGuests - $lastWeekGuests) / $lastWeekGuests) * 100, 1)
            : 0;

        $totalPhysicalRooms = DB::table('rooms')->count();
        $occupiedRoomsCount = DB::table('rooms')->where('status', 'occupied')->count();
        $occupancyRate = $totalPhysicalRooms > 0 ? ($occupiedRoomsCount / $totalPhysicalRooms) * 100 : 0;

        $lastWeekOccupied = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('created_at', '<', now()->subWeek())
            ->count();
        $lastWeekOccupancyRate = $totalPhysicalRooms > 0 ? ($lastWeekOccupied / $totalPhysicalRooms) * 100 : 0;
        $occupancyDiff = round($occupancyRate - $lastWeekOccupancyRate, 1);

        $roomsSold = DB::table('bookings')->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])->count();
        $totalRoomRevenue = DB::table('payments')
            ->whereNotNull('booking_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $adr = $roomsSold > 0 ? $totalRoomRevenue / $roomsSold : 0;

        $lastWeekRoomsSold = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'checked_in', 'checked_out'])
            ->where('created_at', '<', now()->subWeek())
            ->count();
        $lastWeekRoomRevenue = DB::table('payments')
            ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->where('payments.payment_status', 'paid')
            ->where('bookings.created_at', '<', now()->subWeek())
            ->sum('payments.amount') ?: 0;
        $lastWeekAdr = $lastWeekRoomsSold > 0 ? $lastWeekRoomRevenue / $lastWeekRoomsSold : 0;
        $adrDiff = $lastWeekAdr > 0 ? round((($adr - $lastWeekAdr) / $lastWeekAdr) * 100, 1) : 0;

        $totalFbRevenue = DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->where('payment_status', 'paid')
            ->sum('amount') ?: 0;
        $totalRevenue = $totalRoomRevenue + $totalFbRevenue;

        $lastWeekFbRevenue = DB::table('payments')
            ->join('restaurant_orders', 'payments.restaurant_order_id', '=', 'restaurant_orders.id')
            ->where('payments.payment_status', 'paid')
            ->where('restaurant_orders.created_at', '<', now()->subWeek())
            ->sum('payments.amount') ?: 0;
        $lastWeekTotalRevenue = $lastWeekRoomRevenue + $lastWeekFbRevenue;
        $revenueDiff = $lastWeekTotalRevenue > 0
            ? round((($totalRevenue - $lastWeekTotalRevenue) / $lastWeekTotalRevenue) * 100, 1)
            : 0;

        $occupancyDates = [];
        $currentTrendPoints = [];
        $pastTrendPoints = [];
        for ($i = 3; $i >= 0; $i--) {
            $dayDate = now()->subDays($i)->toDateString();
            $pastDayDate = now()->subWeek()->subDays($i)->toDateString();
            $occupancyDates[] = now()->subDays($i)->format('d M');

            $dayOccupied = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in', '<=', $dayDate)
                ->where('check_out', '>', $dayDate)
                ->count();
            $pastDayOccupied = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in', '<=', $pastDayDate)
                ->where('check_out', '>', $pastDayDate)
                ->count();

            $currentTrendPoints[] = $totalPhysicalRooms > 0 ? round(($dayOccupied / $totalPhysicalRooms) * 140) : 0;
            $pastTrendPoints[] = $totalPhysicalRooms > 0 ? round(($pastDayOccupied / $totalPhysicalRooms) * 140) : 0;
        }

        $occupancyTrend = ['past' => $pastTrendPoints, 'current' => $currentTrendPoints];
        $statusShares = [
            'confirmed' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'confirmed')->count() / $totalReservations) * 100 : 0,
            'pending' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'pending')->count() / $totalReservations) * 100 : 0,
            'checked_in' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'checked_in')->count() / $totalReservations) * 100 : 0,
            'cancelled' => $totalReservations > 0 ? (DB::table('bookings')->where('status', 'canceled')->count() / $totalReservations) * 100 : 0,
        ];

        $todayArrivals = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereDate('bookings.check_in', now()->toDateString())
            ->select(
                'users.name as guest_name',
                'guests.id as guest_record_id',
                'guests.identity_number',
                'room_types.name as room_type',
                'rooms.room_number',
                DB::raw('CASE WHEN room_types.price >= 1500000 THEN 1 ELSE 0 END as is_vip')
            )
            ->orderBy('rooms.room_number')
            ->take(5)
            ->get();

        $roomPerformances = [];
        foreach (DB::table('room_types')->get() as $type) {
            $typeTotal = DB::table('rooms')->where('room_type_id', $type->id)->count();
            $typeOccupied = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'occupied')->count();
            $typeRevenue = DB::table('payments')
                ->join('bookings', 'payments.booking_id', '=', 'bookings.id')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('rooms.room_type_id', $type->id)
                ->where('payments.payment_status', 'paid')
                ->sum('payments.amount') ?: 0;

            $roomPerformances[] = [
                'type' => $type->name,
                'total' => $typeTotal,
                'occupied' => $typeOccupied,
                'rate' => $typeTotal > 0 ? ($typeOccupied / $typeTotal) * 100 : 0,
                'revenue' => $typeRevenue,
            ];
        }

        $deptRevenue = ['room_service' => 0, 'restaurant' => $totalFbRevenue, 'spa' => 0];
        $deptShares = $totalFbRevenue > 0
            ? ['room_service' => 0, 'restaurant' => 100, 'spa' => 0]
            : ['room_service' => 0, 'restaurant' => 0, 'spa' => 0];

        $recentActivities = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                DB::raw("'booking' as type"),
                DB::raw("CONCAT('Reservasi Baru #OA-', bookings.id, ' Dibuat') as title"),
                DB::raw("CONCAT('Kamar ', rooms.room_number, ' • Tamu: ', users.name, ' (', bookings.status, ')') as description"),
                'bookings.created_at'
            )
            ->orderByDesc('bookings.id')
            ->take(4)
            ->get();

        foreach ($recentActivities as $activity) {
            $activity->created_at = \Carbon\Carbon::parse($activity->created_at);
        }

        $hkStatus = [
            'clean' => DB::table('rooms')->where('status', 'available')->count(),
            'dirty' => 0,
            'inspected' => 0,
            'oos' => DB::table('rooms')->where('status', 'maintenance')->count(),
        ];

        $role = auth()->user()->role;
        $data = compact(
            'totalReservations', 'reservationDiff', 'totalGuests', 'guestDiff',
            'occupancyRate', 'occupancyDiff', 'adr', 'adrDiff', 'totalRevenue', 'revenueDiff',
            'occupancyTrend', 'occupancyDates', 'statusShares', 'todayArrivals',
            'roomPerformances', 'deptRevenue', 'deptShares', 'recentActivities', 'hkStatus'
        );

        return $role === 'manager'
            ? view('manager.dashboard', $data)
            : view('admin.dashboard', $data);
    }
}
