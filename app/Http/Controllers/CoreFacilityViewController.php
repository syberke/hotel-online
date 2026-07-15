<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreFacilityViewController extends Controller
{
    public function adminFacilitiesView(Request $request)
    {
        $today = now()->toDateString();
        $totalFacilities = DB::table('facilities')->count();
        $totalBookings = DB::table('facility_bookings')->count();
        $todayBookings = DB::table('facility_bookings')->whereDate('booking_date', $today)->count();
        $facilitiesGrid = DB::table('facilities')->orderBy('name')->get();
        $capacityByName = $facilitiesGrid->pluck('hourly_capacity', 'name');

        $todaySessions = DB::table('facility_bookings')
            ->whereDate('booking_date', $today)
            ->where('status', 'confirmed')
            ->select('facility_name', 'booking_time', DB::raw('SUM(guests_count) as booked_guests'))
            ->groupBy('facility_name', 'booking_time')
            ->get();

        $utilizationSamples = [];
        foreach ($todaySessions as $session) {
            $capacity = (int) ($capacityByName[$session->facility_name] ?? 0);
            if ($capacity > 0) {
                $utilizationSamples[] = min(100, ((int) $session->booked_guests / $capacity) * 100);
            }
        }

        $stats = [
            'total_bookings' => $totalBookings,
            'today_bookings' => $todayBookings,
            'active_fac' => $totalFacilities,
            'total_fac' => $totalFacilities,
            'revenue' => 0,
            'utilization' => $utilizationSamples === [] ? 0 : round(array_sum($utilizationSamples) / count($utilizationSamples), 1),
        ];

        $query = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->leftJoin('bookings', function ($join) use ($today) {
                $join->on('users.id', '=', 'bookings.user_id')
                    ->whereIn('bookings.status', ['confirmed', 'checked_in'])
                    ->where('bookings.check_in', '<=', $today)
                    ->where('bookings.check_out', '>=', $today);
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'facility_bookings.*',
                'users.name as guest_name',
                'guests.phone as guest_phone',
                'rooms.room_number'
            );

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
            $needle = '%' . strtolower($request->search) . '%';
            $idSql = DB::connection()->getDriverName() === 'pgsql'
                ? 'CAST(facility_bookings.id AS TEXT) LIKE ?'
                : 'CAST(facility_bookings.id AS CHAR) LIKE ?';
            $query->where(function ($q) use ($needle, $idSql) {
                $q->whereRaw($idSql, [$needle])
                    ->orWhereRaw('LOWER(users.name) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(facility_bookings.facility_name) LIKE ?', [$needle]);
            });
        }

        $bookings = $query
            ->orderByDesc('facility_bookings.booking_date')
            ->orderByDesc('facility_bookings.booking_time')
            ->paginate(5)
            ->withQueryString();

        foreach ($facilitiesGrid as $facility) {
            $todayRows = DB::table('facility_bookings')
                ->where('facility_name', $facility->name)
                ->whereDate('booking_date', $today)
                ->where('status', 'confirmed');
            $bookedGuests = (int) ($todayRows->sum('guests_count') ?: 0);
            $slotCount = (clone $todayRows)->distinct()->count('booking_time');
            $capacity = (int) $facility->hourly_capacity;
            $facility->computed_util = $capacity > 0 && $slotCount > 0
                ? min(100, round(($bookedGuests / ($capacity * $slotCount)) * 100, 1))
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
            $chartShares[$facility->name] = $totalBookings > 0 ? round(($count / $totalBookings) * 100, 1) : 0;
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
            'stats', 'bookings', 'currentTab', 'facilitiesGrid', 'asideStats', 'chartShares', 'popularFacilities'
        ));
    }

    public function adminFacilityBookingDetail($id)
    {
        $booking = DB::table('facility_bookings')
            ->join('users', 'facility_bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
            })
            ->where('facility_bookings.id', $id)
            ->select(
                'facility_bookings.*',
                'users.name as guest_name',
                'users.email as guest_email',
                'guests.phone as guest_phone'
            )
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Data booking tidak ditemukan.'], 404);
        }

        return response()->json(['success' => true, 'data' => $booking]);
    }
}
