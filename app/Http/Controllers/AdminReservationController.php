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
            'online' => Booking::where('booking_source', 'online')->count(),
            'walk_in' => Booking::where('booking_source', 'walk_in')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'arrivals' => Booking::whereDate('check_in', now()->toDateString())->count(),
            'departures' => Booking::whereDate('check_out', now()->toDateString())->count(),
        ];

        $roomTypes = DB::table('room_types')->select('name')->distinct()->get();
        $relations = ['user.guestProfile', 'guest', 'creator', 'room.roomType', 'payments'];
        $query = Booking::with($relations);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $cleanId = preg_replace('/\D+/', '', $search);
                if ($cleanId !== '') {
                    $q->where('id', (int) $cleanId);
                }

                $q->orWhereHas('user', function ($userQuery) use ($search) {
                    $needle = '%' . strtolower($search) . '%';
                    $userQuery->whereRaw('LOWER(name) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$needle]);
                })->orWhereHas('guest', function ($guestQuery) use ($search) {
                    $needle = '%' . strtolower($search) . '%';
                    $guestQuery->whereRaw('LOWER(name) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(email) LIKE ?', [$needle])
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhere('identity_number', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('source') && in_array($request->source, ['online', 'walk_in'], true)) {
            $query->where('booking_source', $request->source);
        }

        if ($request->filled('status') && $request->status !== 'All Status') {
            $statusDb = strtolower(str_replace(' ', '_', $request->status));
            if ($statusDb === 'cancelled') {
                $query->whereIn('status', ['cancelled', 'canceled']);
            } else {
                $query->where('status', $statusDb);
            }
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
        $selectedBooking = $selectedBookingId
            ? Booking::with($relations)->find($selectedBookingId)
            : $bookings->first();

        return view('admin.reservation', compact('bookings', 'stats', 'roomTypes', 'selectedBooking'));
    }
}
