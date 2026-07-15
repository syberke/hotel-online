<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReservationController extends Controller
{
    public function adminReservationsView(Request $request)
    {
        $stats = [
            'total_resv' => Booking::count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'arrivals' => Booking::whereDate('check_in', now()->toDateString())->count(),
            'departures' => Booking::whereDate('check_out', now()->toDateString())->count(),
        ];

        $roomTypes = DB::table('room_types')->select('name')->distinct()->get();
        $query = Booking::with(['user.guestProfile', 'room.roomType', 'payments']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $cleanId = ltrim($search, '#OA-');
                $q->where('id', 'like', "%{$cleanId}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $needle = '%' . strtolower($search) . '%';
                        $userQuery->whereRaw('LOWER(name) LIKE ?', [$needle])
                            ->orWhereRaw('LOWER(email) LIKE ?', [$needle]);
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'All Status') {
            $statusDb = strtolower(str_replace(' ', '_', $request->status));
            if ($statusDb === 'cancelled') {
                $statusDb = 'canceled';
            }
            $query->where('status', $statusDb);
        }

        if ($request->filled('room_type') && $request->room_type !== 'All Room Types') {
            $query->whereHas('room.roomType', function ($q) use ($request) {
                $q->where('name', $request->room_type);
            });
        }

        if ($request->filled('date_range')) {
            $dates = explode(' - ', $request->date_range);
            if (count($dates) === 2) {
                $query->whereDate('check_in', '>=', Carbon::parse($dates[0])->toDateString())
                    ->whereDate('check_out', '<=', Carbon::parse($dates[1])->toDateString());
            }
        }

        $perPage = max(1, (int) $request->get('per_page', 10));
        $bookings = $query->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        $selectedBookingId = $request->get('selected_id');
        if ($selectedBookingId) {
            $selectedBooking = Booking::with(['user.guestProfile', 'room.roomType', 'payments'])->find($selectedBookingId);
        } else {
            $selectedBooking = $bookings->first();
        }

        return view('admin.reservation', compact('bookings', 'stats', 'roomTypes', 'selectedBooking'));
    }
}
