<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReceptionistGuestHistoryController extends Controller
{
    public function receptionistGuestHistoryView(Request $request)
    {
        $guestId = $request->integer('guest_id');
        if (! $guestId) {
            $guestId = (int) (DB::table('bookings')->orderByDesc('created_at')->value('user_id') ?? 0);
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
                    'users.name',
                    'users.email',
                    'users.id as user_id',
                    'guests.id as guest_record_id',
                    'guests.identity_number',
                    'guests.phone',
                    'guests.address',
                    'guests.foto_url',
                )
                ->first();

            if ($guestProfile) {
                $stayHistoryRaw = DB::table('bookings')
                    ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->user_id)
                    ->select(
                        'bookings.*',
                        'rooms.room_number',
                        'room_types.name as room_type_name',
                    )
                    ->orderByDesc('bookings.check_in')
                    ->get();

                $totalStays = $stayHistoryRaw->count();
                foreach ($stayHistoryRaw as $stay) {
                    $checkIn = Carbon::parse($stay->check_in);
                    $checkOut = Carbon::parse($stay->check_out);
                    $nights = max(1, $checkIn->diffInDays($checkOut));
                    $totalNights += $nights;
                    $totalSpend += (float) $stay->total_price;
                    $stayHistory[] = [
                        'id' => $stay->id,
                        'check_in' => $checkIn->format('d M Y'),
                        'check_out' => $checkOut->format('d M Y'),
                        'room_number' => $stay->room_number ?: 'TBD',
                        'room_type' => $stay->room_type_name ?: 'Unassigned room type',
                        'nights' => $nights,
                        'total_charges' => (float) $stay->total_price,
                        'status' => $stay->status,
                    ];
                }

                $avgSpendPerStay = $totalStays > 0 ? round($totalSpend / $totalStays) : 0;
                $recentActivities = DB::table('bookings')
                    ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('bookings.user_id', $guestProfile->user_id)
                    ->select(
                        'bookings.id',
                        'bookings.status',
                        'bookings.updated_at',
                        'rooms.room_number',
                        'room_types.name as room_type_name',
                    )
                    ->orderByDesc('bookings.updated_at')
                    ->take(4)
                    ->get();
            }
        }

        return view('receptionist.guesthistory', compact(
            'guestProfile',
            'stayHistory',
            'totalStays',
            'totalNights',
            'totalSpend',
            'avgSpendPerStay',
            'recentActivities',
        ));
    }

    public function updateIdentity(Request $request, int $userId): RedirectResponse
    {
        abort_unless($request->user()?->role === 'receptionist', 403);

        $user = DB::table('users')->where('id', $userId)->where('role', 'guest')->first();
        abort_unless($user, 404, 'Guest tidak ditemukan.');

        $guestRecord = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) $user->email)])
            ->first();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'identity_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('guests', 'identity_number')->ignore($guestRecord?->id),
            ],
            'address' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($user, $guestRecord, $validated) {
            DB::table('users')->where('id', $user->id)->update([
                'name' => $validated['name'],
                'updated_at' => now(),
            ]);

            $guestPayload = [
                'name' => $validated['name'],
                'phone' => $validated['phone'] ?: null,
                'identity_number' => $validated['identity_number'] ?: null,
                'address' => $validated['address'] ?: null,
                'updated_at' => now(),
            ];

            if ($guestRecord) {
                DB::table('guests')->where('id', $guestRecord->id)->update($guestPayload);
                return;
            }

            DB::table('guests')->insert($guestPayload + [
                'email' => strtolower((string) $user->email),
                'email_verified_at' => $user->email_verified_at,
                'password' => $user->password,
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('receptionist.guesthistory', ['guest_id' => $userId])
            ->with('success', 'Identitas guest berhasil diperbarui.');
    }
}
