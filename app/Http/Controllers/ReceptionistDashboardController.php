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

        $search = $request->input('search');
        $arrivalsQuery = DB::table('bookings')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->whereDate('bookings.check_in', $today)
            ->select(
                'bookings.id as booking_id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.status as booking_status',
                'users.name as guest_name',
                'guests.id as guest_record_id',
                'guests.identity_number',
                'guests.phone as guest_phone',
                'guests.address as guest_address',
                'guests.foto_url as guest_avatar',
                'rooms.room_number',
                'room_types.name as room_type'
            );

        if ($search) {
            $arrivalsQuery->where(function ($q) use ($search) {
                $cleanSearch = ltrim($search, '#RES-OA-');
                $q->where('bookings.id', 'like', "%{$cleanSearch}%")
                    ->orWhereRaw('LOWER(users.name) LIKE ?', ['%' . strtolower($search) . '%'])
                    ->orWhere('rooms.room_number', 'like', "%{$search}%");
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

        $vacantClean = DB::table('rooms')->where('status', 'available')->count();
        $vacantDirty = 0;
        $outOfOrder = DB::table('rooms')->where('status', 'maintenance')->count();

        return view('receptionist.dashboard', compact(
            'totalRooms', 'occupiedRooms', 'occupancyRate', 'checkinsToday', 'expectedCheckins',
            'checkoutsToday', 'expectedCheckouts', 'inhouseGuests', 'inhouseReservations',
            'revenueToday', 'revenueDiffPct', 'arrivals', 'arrivalsCount', 'inHouseTabCount',
            'departuresTabCount', 'noShowTabCount', 'trendDates', 'svgPathD',
            'vacantClean', 'vacantDirty', 'outOfOrder'
        ));
    }
}
