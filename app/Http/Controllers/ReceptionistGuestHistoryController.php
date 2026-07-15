<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceptionistGuestHistoryController extends Controller
{
    public function receptionistGuestHistoryView(Request $request)
    {
        $guestId = $request->input('guest_id');
        if (!$guestId) {
            $latestBooking = DB::table('bookings')->orderByDesc('created_at')->first();
            $guestId = $latestBooking?->user_id;
        }

        $guestProfile = null;
        $stayHistory = [];
        $totalStays = 0;
        $totalNights = 0;
        $totalSpend = 0;
        $avgSpendPerStay = 0;
        $recentActivities = collect();

        if ($guestId) {
            $guestProfile = DB::table('users')
                ->leftJoin('guests', function ($join) {
                    $join->on(DB::raw('LOWER(users.email)'), '=', DB::raw('LOWER(guests.email)'));
                })
                ->where('users.id', $guestId)
                ->where('users.role', 'guest')
                ->select(
                    'users.*',
                    'users.id as user_id',
                    'guests.id as guest_record_id',
                    'guests.identity_number',
                    'guests.phone as phone',
                    'guests.address as address'
                )
                ->first();

            if ($guestProfile) {
                $stayHistoryRaw = DB::table('bookings')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->id)
                    ->select('bookings.*', 'rooms.room_number', 'room_types.name as room_type_name')
                    ->orderByDesc('bookings.check_in')
                    ->get();

                $totalStays = $stayHistoryRaw->count();
                foreach ($stayHistoryRaw as $stay) {
                    $checkIn = Carbon::parse($stay->check_in);
                    $checkOut = Carbon::parse($stay->check_out);
                    $nights = $checkIn->diffInDays($checkOut) ?: 1;
                    $totalNights += $nights;
                    $totalSpend += $stay->total_price;
                    $stayHistory[] = [
                        'id' => $stay->id,
                        'check_in' => $checkIn->format('d M Y'),
                        'check_out' => $checkOut->format('d M Y'),
                        'check_in_full' => $checkIn->format('d M Y (h:i A)'),
                        'check_out_full' => $checkOut->format('d M Y (h:i A)'),
                        'room_number' => $stay->room_number,
                        'room_type' => $stay->room_type_name,
                        'nights' => $nights,
                        'total_charges' => $stay->total_price,
                        'status' => $stay->status,
                    ];
                }

                $avgSpendPerStay = $totalStays > 0 ? round($totalSpend / $totalStays) : 0;
                $recentActivities = DB::table('bookings')
                    ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->id)
                    ->select('bookings.status', 'bookings.check_in', 'rooms.room_number', 'room_types.name as room_type_name')
                    ->orderByDesc('bookings.updated_at')
                    ->take(3)
                    ->get();
            }
        }

        return view('receptionist.guesthistory', compact(
            'guestProfile', 'stayHistory', 'totalStays', 'totalNights',
            'totalSpend', 'avgSpendPerStay', 'recentActivities'
        ));
    }
}
