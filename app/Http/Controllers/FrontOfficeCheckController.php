<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class FrontOfficeCheckController extends Controller
{
    public function receptionistCheckInView(Request $request): View
    {
        $search = trim((string) $request->input('search'));
        $selectedId = $request->integer('booking_id');
        $today = now()->toDateString();

        $selectedBooking = null;
        if ($selectedId) {
            $selectedBooking = DB::table('bookings')
                ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
                ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
                ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('bookings.id', $selectedId)
                ->select(
                    'bookings.*',
                    DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                    DB::raw("CASE WHEN bookings.booking_source = 'walk_in' THEN NULL ELSE COALESCE(users.email, guests.email) END as guest_email"),
                    'guests.phone as guest_phone',
                    'guests.address as guest_address',
                    'rooms.room_number',
                    'room_types.name as room_type',
                    'room_types.price as base_price',
                )
                ->first();
        }

        $query = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->whereIn('bookings.status', ['confirmed', 'pending']);

        if ($search !== '') {
            $cleanSearch = preg_replace('/\D+/', '', $search);
            $needle = '%' . strtolower($search) . '%';
            $query->where(function ($builder) use ($search, $cleanSearch, $needle) {
                $builder->whereRaw("LOWER(COALESCE(users.name, guests.name, '')) LIKE ?", [$needle])
                    ->orWhereRaw("LOWER(COALESCE(users.email, guests.email, '')) LIKE ?", [$needle])
                    ->orWhere('guests.phone', 'like', '%' . $search . '%');

                if ($cleanSearch !== '') {
                    $builder->orWhere('bookings.id', (int) $cleanSearch);
                }
            });
        } else {
            $query->whereDate('bookings.check_in', $today);
        }

        $bookings = $query
            ->select(
                'bookings.id',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                'bookings.check_in',
                'bookings.booking_source',
                'rooms.room_number'
            )
            ->orderBy('bookings.check_in')
            ->limit(10)
            ->get();

        return view('receptionist.checkin', compact('selectedBooking', 'bookings'));
    }

    public function processCheckIn(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'payment_method' => ['required', 'in:cash,transfer,credit_card,e_wallet'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $booking = DB::table('bookings')
                    ->where('id', $validated['booking_id'])
                    ->lockForUpdate()
                    ->first();

                if (! $booking || ! in_array($booking->status, ['confirmed', 'pending'], true)) {
                    throw new RuntimeException('Reservasi tidak berada pada status yang dapat diproses check-in.');
                }

                if (! $booking->room_id) {
                    throw new RuntimeException('Kamar fisik belum dialokasikan untuk reservasi ini.');
                }

                $room = DB::table('rooms')->where('id', $booking->room_id)->lockForUpdate()->first();
                if (! $room || ! in_array($room->status, ['available', 'occupied'], true)) {
                    throw new RuntimeException('Kamar tidak tersedia untuk check-in.');
                }

                $paidAmount = (float) DB::table('payments')
                    ->where('booking_id', $booking->id)
                    ->where('payment_status', 'paid')
                    ->sum('amount');

                $outstanding = max(0, (float) $booking->total_price - $paidAmount);
                if ($outstanding > 0) {
                    DB::table('payments')->insert([
                        'booking_id' => $booking->id,
                        'restaurant_order_id' => null,
                        'amount' => $outstanding,
                        'payment_method' => $validated['payment_method'],
                        'payment_status' => 'paid',
                        'note' => 'Front desk check-in settlement',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('bookings')->where('id', $booking->id)->update([
                    'status' => 'checked_in',
                    'updated_at' => now(),
                ]);

                DB::table('rooms')->where('id', $booking->room_id)->update([
                    'status' => 'occupied',
                    'updated_at' => now(),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('receptionist.folio', ['booking_id' => $validated['booking_id']])
            ->with('success', 'Check-in berhasil dikonfirmasi dan folio tamu telah diperbarui.');
    }

    public function receptionistGuestsView(Request $request): View
    {
        $currentTab = $request->string('guest_tab')->value() ?: 'all';
        $search = trim((string) $request->input('search'));
        $selectedGuestId = $request->integer('selected_guest_id');
        $today = now()->toDateString();

        $inHouseGuests = (int) DB::table('bookings')->where('status', 'checked_in')->sum('guests_count');
        $checkinsToday = DB::table('bookings')->whereDate('check_in', $today)->where('status', 'checked_in')->count();
        $checkoutsToday = DB::table('bookings')->whereDate('check_out', $today)->where('status', 'checked_out')->count();
        $totalGuestsAllTime = DB::table('guests')->count();
        $revenueThisMonth = (float) DB::table('payments')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $tabCounters = [
            'all' => DB::table('guests')->count(),
            'in_house' => DB::table('bookings')->where('status', 'checked_in')->distinct('guest_id')->count('guest_id'),
            'checked_out' => DB::table('bookings')->where('status', 'checked_out')->distinct('guest_id')->count('guest_id'),
        ];

        $latestBookingSub = DB::table('bookings')
            ->whereNotNull('guest_id')
            ->select('guest_id', DB::raw('MAX(id) as latest_booking_id'))
            ->groupBy('guest_id');

        $query = DB::table('guests')
            ->leftJoin('users', function ($join) {
                $join->on(DB::raw('LOWER(guests.email)'), '=', DB::raw('LOWER(users.email)'));
            })
            ->leftJoinSub($latestBookingSub, 'latest_res', function ($join) {
                $join->on('guests.id', '=', 'latest_res.guest_id');
            })
            ->leftJoin('bookings', 'latest_res.latest_booking_id', '=', 'bookings.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->select(
                'guests.id as guest_id',
                'users.id as user_id',
                'guests.name as guest_name',
                DB::raw("CASE WHEN bookings.booking_source = 'walk_in' THEN NULL ELSE COALESCE(users.email, guests.email) END as guest_email"),
                'guests.phone as guest_phone',
                'guests.identity_number',
                'bookings.booking_source',
                'bookings.status as booking_status',
                'bookings.check_in',
                'bookings.check_out',
                'rooms.room_number',
                DB::raw("(SELECT COUNT(*) FROM bookings AS completed_bookings WHERE completed_bookings.guest_id = guests.id AND completed_bookings.status = 'checked_out') as total_stays"),
            );

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $needle = '%' . strtolower($search) . '%';
                $builder->whereRaw('LOWER(guests.name) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(guests.email) LIKE ?', [$needle])
                    ->orWhere('guests.phone', 'like', '%' . $search . '%')
                    ->orWhere('guests.identity_number', 'like', '%' . $search . '%');
            });
        }

        if ($currentTab === 'in_house') {
            $query->where('bookings.status', 'checked_in');
        } elseif ($currentTab === 'checked_out') {
            $query->where('bookings.status', 'checked_out');
        }

        $guestsList = $query->orderBy('guests.name')->paginate(10)->withQueryString();
        $targetGuestId = $selectedGuestId ?: optional($guestsList->first())->guest_id;
        $selectedGuest = null;

        if ($targetGuestId) {
            $profile = DB::table('guests')->where('id', $targetGuestId)->first();
            if ($profile) {
                $linkedUser = DB::table('users')->whereRaw('LOWER(email) = ?', [strtolower($profile->email)])->first();
                $lastBooking = DB::table('bookings')
                    ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
                    ->where('bookings.guest_id', $profile->id)
                    ->select('bookings.*', 'rooms.room_number')
                    ->latest('bookings.id')
                    ->first();

                $isWalkIn = ($lastBooking?->booking_source ?? null) === 'walk_in';
                $selectedGuest = (object) [
                    'guest_id' => $profile->id,
                    'user_id' => $linkedUser?->id,
                    'name' => $profile->name,
                    'email' => $isWalkIn ? null : ($linkedUser?->email ?? $profile->email),
                    'booking_source' => $lastBooking?->booking_source ?? 'online',
                    'phone' => $profile->phone,
                    'identity_number' => $profile->identity_number,
                    'address' => $profile->address,
                    'check_in' => $lastBooking?->check_in,
                    'check_out' => $lastBooking?->check_out,
                    'guests_count' => $lastBooking?->guests_count ?? 0,
                    'current_status' => $lastBooking?->status ?? 'registered',
                    'room_number' => $lastBooking?->room_number,
                ];
            }
        }

        return view('receptionist.guests', compact(
            'inHouseGuests',
            'checkinsToday',
            'checkoutsToday',
            'totalGuestsAllTime',
            'revenueThisMonth',
            'tabCounters',
            'currentTab',
            'guestsList',
            'selectedGuest',
        ));
    }

    public function assignRoomNumber(Request $request): View|RedirectResponse
    {
        $today = now()->toDateString();
        $search = trim((string) $request->input('search'));

        if ($request->isMethod('post') && $request->has('submit_assignment_booking_id')) {
            $validated = $request->validate([
                'submit_assignment_booking_id' => ['required', 'integer', 'exists:bookings,id'],
                'assign_selected_room_id' => ['required', 'integer', 'exists:rooms,id'],
            ]);

            try {
                DB::transaction(function () use ($validated) {
                    $booking = DB::table('bookings')
                        ->where('id', $validated['submit_assignment_booking_id'])
                        ->lockForUpdate()
                        ->first();
                    $selectedRoom = DB::table('rooms')
                        ->where('id', $validated['assign_selected_room_id'])
                        ->lockForUpdate()
                        ->first();

                    if (! $booking || ! in_array($booking->status, ['confirmed', 'pending'], true)) {
                        throw new RuntimeException('Reservasi tidak dapat diubah pada status saat ini.');
                    }
                    if (! $selectedRoom || $selectedRoom->status !== 'available') {
                        throw new RuntimeException('Kamar yang dipilih tidak lagi tersedia.');
                    }

                    $currentRoomTypeId = DB::table('rooms')->where('id', $booking->room_id)->value('room_type_id');
                    if ($currentRoomTypeId && (int) $selectedRoom->room_type_id !== (int) $currentRoomTypeId) {
                        throw new RuntimeException('Kamar pengganti harus memiliki tipe yang sama dengan reservasi.');
                    }

                    DB::table('bookings')->where('id', $booking->id)->update([
                        'room_id' => $selectedRoom->id,
                        'updated_at' => now(),
                    ]);
                });
            } catch (RuntimeException $exception) {
                return back()->withInput()->with('error', $exception->getMessage());
            }

            return redirect()->route('receptionist.checkin', ['booking_id' => $validated['submit_assignment_booking_id']])
                ->with('success', 'Kamar berhasil dialokasikan. Lanjutkan proses check-in untuk menandai kamar occupied.');
        }

        $arrivalsCount = DB::table('bookings')->whereDate('check_in', $today)->count();
        $unassignedCount = DB::table('bookings')->whereDate('check_in', $today)->whereIn('status', ['confirmed', 'pending'])->count();
        $assignedCount = DB::table('bookings')->where('status', 'checked_in')->count();
        $freeRoomsCount = DB::table('rooms')->where('status', 'available')->count();

        $unassignedQuery = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereIn('bookings.status', ['confirmed', 'pending'])
            ->select(
                'bookings.*',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                DB::raw("CASE WHEN bookings.booking_source = 'walk_in' THEN NULL ELSE COALESCE(users.email, guests.email) END as guest_email"),
                'guests.phone as guest_phone',
                'room_types.name as room_type',
                'rooms.room_number as initial_room_number',
                'room_types.id as room_type_id',
            );

        if ($search !== '') {
            $unassignedQuery->where(function ($builder) use ($search) {
                $needle = '%' . strtolower($search) . '%';
                $builder->whereRaw("LOWER(COALESCE(users.name, guests.name, '')) LIKE ?", [$needle])
                    ->orWhereRaw("LOWER(COALESCE(users.email, guests.email, '')) LIKE ?", [$needle]);

                $cleanId = preg_replace('/\D+/', '', $search);
                if ($cleanId !== '') {
                    $builder->orWhere('bookings.id', (int) $cleanId);
                }
            });
        }

        $unassignedReservations = $unassignedQuery->orderBy('bookings.created_at')->get();
        $selectedBookingId = $request->integer('selected_booking_id');
        $activeTarget = $selectedBookingId
            ? $unassignedReservations->firstWhere('id', $selectedBookingId)
            : $unassignedReservations->first();

        $availablePhysicalRooms = collect();
        if ($activeTarget?->room_type_id) {
            $availablePhysicalRooms = DB::table('rooms')
                ->where('room_type_id', $activeTarget->room_type_id)
                ->where('status', 'available')
                ->orderBy('room_number')
                ->get();
        }

        $floorsGrid = [];
        foreach (DB::table('rooms')->orderBy('room_number')->get() as $room) {
            $floorLength = max(0, strlen((string) $room->room_number) - 2);
            $floorNumber = $floorLength > 0 ? substr((string) $room->room_number, 0, $floorLength) : '1';
            $floorsGrid[$floorNumber][] = $room;
        }
        ksort($floorsGrid);

        return view('receptionist.roomassignment', compact(
            'arrivalsCount',
            'unassignedCount',
            'assignedCount',
            'freeRoomsCount',
            'unassignedReservations',
            'activeTarget',
            'availablePhysicalRooms',
            'floorsGrid',
        ));
    }
}
