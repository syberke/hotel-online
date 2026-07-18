<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionistDashboardController extends Controller
{
    public function receptionistDashboardView(Request $request)
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        $totalRooms = DB::table('rooms')->count();
        $occupiedRooms = DB::table('rooms')->where('status', 'occupied')->count();
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $checkinsToday = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'checked_in')->count();
        $expectedCheckins = DB::table('bookings')->whereDate('check_in', $today)->count();
        $checkoutsToday = DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_out')->count();
        $expectedCheckouts = DB::table('bookings')->whereDate('check_out', $today)->count();
        $inhouseReservations = DB::table('bookings')->where('status', 'checked_in')->count();
        $inhouseGuests = DB::table('bookings')->where('status', 'checked_in')->sum('guests_count') ?: 0;

        $revenueToday = DB::table('payments')->whereDate('created_at', $today)->where('payment_status', 'paid')->sum('amount') ?: 0;
        $revenueYesterday = DB::table('payments')->whereDate('created_at', $yesterday)->where('payment_status', 'paid')->sum('amount') ?: 0;
        $revenueDiffPct = $revenueYesterday > 0
            ? round((($revenueToday - $revenueYesterday) / $revenueYesterday) * 100, 1)
            : 0;

        $search = trim((string) $request->input('search'));
        $arrivalsQuery = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->whereDate('bookings.check_in', $today)
            ->select(
                'bookings.id as booking_id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.status as booking_status',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                'guests.id as guest_record_id',
                'guests.identity_number',
                'guests.phone as guest_phone',
                'guests.address as guest_address',
                'guests.foto_url as guest_avatar',
                'rooms.room_number',
                'room_types.name as room_type'
            );

        if ($search !== '') {
            $cleanId = preg_replace('/\D+/', '', $search);
            $needle = '%' . strtolower($search) . '%';
            $arrivalsQuery->where(function ($query) use ($cleanId, $needle, $search) {
                $query->whereRaw("LOWER(COALESCE(users.name, guests.name, '')) LIKE ?", [$needle])
                    ->orWhere('rooms.room_number', 'like', '%' . $search . '%');
                if ($cleanId !== '') {
                    $query->orWhere('bookings.id', (int) $cleanId);
                }
            });
        }

        $arrivals = $arrivalsQuery->orderBy('bookings.created_at')->get();
        $arrivalsCount = $arrivals->count();
        $inHouseTabCount = DB::table('bookings')->where('status', 'checked_in')->count();
        $departuresTabCount = DB::table('bookings')->whereDate('check_out', $today)->count();
        $noShowTabCount = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'pending')->count();

        $occupancyTrend = [];
        $trendDates = [];
        for ($i = 4; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trendDates[] = now()->subDays($i)->format('d M');
            $dayOccupied = DB::table('bookings')
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where('check_in', '<=', $date)
                ->where('check_out', '>', $date)
                ->count();
            $occupancyTrend[] = $totalRooms > 0 ? round(($dayOccupied / $totalRooms) * 100) : 0;
        }

        $svgPoints = [];
        $stepX = 500 / 4;
        foreach ($occupancyTrend as $index => $pct) {
            $svgPoints[] = ($index * $stepX) . ',' . (100 - $pct);
        }
        $svgPathD = 'M ' . implode(' L ', $svgPoints);

        // PostgreSQL room_status_enum only permits available, occupied, and maintenance.
        // Rooms are moved to maintenance after checkout until housekeeping marks them available.
        $maintenanceRooms = DB::table('rooms')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('rooms.status', 'maintenance')
            ->select('rooms.room_number', 'room_types.name as room_type')
            ->orderBy('rooms.room_number')
            ->get();

        $pendingPayments = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('payments', function ($join) {
                $join->on('payments.booking_id', '=', 'bookings.id')
                    ->whereNull('payments.restaurant_order_id');
            })
            ->whereDate('bookings.check_in', '<=', $today)
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->groupBy('bookings.id', 'users.name')
            ->havingRaw("COALESCE(SUM(CASE WHEN payments.payment_status = 'paid' THEN payments.amount ELSE 0 END), 0) < MAX(bookings.total_price)")
            ->select(
                'bookings.id',
                'users.name as guest_name',
                DB::raw("COALESCE(SUM(CASE WHEN payments.payment_status = 'paid' THEN payments.amount ELSE 0 END), 0) as paid_amount"),
                DB::raw('MAX(bookings.total_price) as total_price'),
            )
            ->orderBy('bookings.id')
            ->get();

        $assignmentBookings = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereDate('bookings.check_in', '<=', now()->addDay()->toDateString())
            ->whereDate('bookings.check_out', '>=', $today)
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->select('bookings.id', 'users.name as guest_name', 'rooms.room_number')
            ->orderBy('bookings.check_in')
            ->take(8)
            ->get();

        $vacantClean = DB::table('rooms')->where('status', 'available')->count();
        $outOfService = $maintenanceRooms->count();
        $vacantDirty = 0;
        $outOfOrder = $outOfService;

        $attentionAlerts = collect();
        if ($maintenanceRooms->isNotEmpty()) {
            $attentionAlerts->push([
                'tone' => 'amber',
                'icon' => 'fa-broom',
                'title' => $maintenanceRooms->count() . ' room(s) need housekeeping or maintenance',
                'description' => 'Rooms move to maintenance after checkout and remain unavailable until staff marks them ready.',
                'items' => $maintenanceRooms->take(5)->map(fn ($room) => 'Room ' . $room->room_number . ' · ' . ($room->room_type ?: 'Unknown type'))->all(),
                'url' => route('receptionist.housestatus'),
                'action' => 'Review room readiness',
            ]);
        }
        if ($pendingPayments->isNotEmpty()) {
            $attentionAlerts->push([
                'tone' => 'orange',
                'icon' => 'fa-credit-card',
                'title' => $pendingPayments->count() . ' arrival(s) still have a balance',
                'description' => 'Open the payment desk before completing check-in.',
                'items' => $pendingPayments->take(5)->map(fn ($booking) => '#OA-' . str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($booking->guest_name ?: 'Guest') . ' · Rp ' . number_format(max(0, (float) $booking->total_price - (float) $booking->paid_amount), 0, ',', '.') . ' due')->all(),
                'url' => route('receptionist.payments'),
                'action' => 'Open payments',
            ]);
        }
        if ($assignmentBookings->isNotEmpty()) {
            $attentionAlerts->push([
                'tone' => 'blue',
                'icon' => 'fa-door-open',
                'title' => $assignmentBookings->count() . ' reservation(s) need room confirmation',
                'description' => 'Review the physical room before moving the reservation to check-in.',
                'items' => $assignmentBookings->take(5)->map(fn ($booking) => '#OA-' . str_pad((string) $booking->id, 5, '0', STR_PAD_LEFT) . ' · ' . ($booking->guest_name ?: 'Guest') . ' · ' . ($booking->room_number ? 'Room ' . $booking->room_number : 'No room'))->all(),
                'url' => route('receptionist.roomassignment'),
                'action' => 'Open assignment queue',
            ]);
        }

        return view('receptionist.dashboard', compact(
            'totalRooms', 'occupiedRooms', 'occupancyRate', 'checkinsToday', 'expectedCheckins',
            'checkoutsToday', 'expectedCheckouts', 'inhouseGuests', 'inhouseReservations',
            'revenueToday', 'revenueDiffPct', 'arrivals', 'arrivalsCount', 'inHouseTabCount',
            'departuresTabCount', 'noShowTabCount', 'trendDates', 'svgPathD',
            'vacantClean', 'vacantDirty', 'outOfOrder', 'outOfService', 'attentionAlerts'
        ));
    }
}
