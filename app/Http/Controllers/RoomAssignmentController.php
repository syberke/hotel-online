<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class RoomAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $today = now()->toDateString();
        $search = trim((string) $request->input('search'));

        $paymentSummary = DB::table('payments')
            ->whereNotNull('booking_id')
            ->select(
                'booking_id',
                DB::raw("SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as paid_amount"),
                DB::raw("MAX(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as has_paid_payment"),
            )
            ->groupBy('booking_id');

        $queueQuery = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', function ($join) {
                $join->on(DB::raw('LOWER(guests.email)'), '=', DB::raw('LOWER(users.email)'));
            })
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoinSub($paymentSummary, 'payment_summary', function ($join) {
                $join->on('bookings.id', '=', 'payment_summary.booking_id');
            })
            ->whereIn('bookings.status', ['pending', 'confirmed'])
            ->whereDate('bookings.check_out', '>=', $today)
            ->select(
                'bookings.*',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                DB::raw("COALESCE(users.email, guests.email, '-') as guest_email"),
                'guests.phone as guest_phone',
                'rooms.room_number as initial_room_number',
                'rooms.room_type_id',
                'room_types.name as room_type',
                'payment_summary.paid_amount',
                'payment_summary.has_paid_payment',
            );

        if ($search !== '') {
            $cleanId = preg_replace('/\D+/', '', $search);
            $needle = '%' . strtolower($search) . '%';

            $queueQuery->where(function ($builder) use ($cleanId, $needle) {
                $builder->whereRaw('LOWER(COALESCE(users.name, guests.name, \'\')) LIKE ?', [$needle])
                    ->orWhereRaw('LOWER(COALESCE(users.email, guests.email, \'\')) LIKE ?', [$needle])
                    ->orWhere('rooms.room_number', 'like', $needle);

                if ($cleanId !== '') {
                    $builder->orWhere('bookings.id', (int) $cleanId);
                }
            });
        }

        $assignmentQueue = $queueQuery
            ->orderByRaw("CASE WHEN bookings.status = 'confirmed' THEN 0 ELSE 1 END")
            ->orderBy('bookings.check_in')
            ->orderBy('bookings.created_at')
            ->get();

        $selectedBookingId = $request->integer('selected_booking_id');
        $activeTarget = $selectedBookingId
            ? $assignmentQueue->firstWhere('id', $selectedBookingId)
            : $assignmentQueue->first();

        $availablePhysicalRooms = collect();
        if ($activeTarget) {
            $availableQuery = DB::table('rooms')
                ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                ->where('rooms.status', 'available')
                ->select('rooms.*', 'room_types.name as room_type_name');

            if ($activeTarget->room_type_id) {
                $availableQuery->where('rooms.room_type_id', $activeTarget->room_type_id);
            }

            $availablePhysicalRooms = $availableQuery->orderBy('rooms.room_number')->get();
        }

        $floorsGrid = [];
        $physicalRooms = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as room_type_name')
            ->orderBy('rooms.room_number')
            ->get();

        foreach ($physicalRooms as $room) {
            $roomNumber = (string) $room->room_number;
            $floorLength = max(0, strlen($roomNumber) - 2);
            $floorNumber = $floorLength > 0 ? substr($roomNumber, 0, $floorLength) : '1';
            $floorsGrid[$floorNumber][] = $room;
        }
        ksort($floorsGrid);

        $arrivalsCount = DB::table('bookings')
            ->whereDate('check_in', $today)
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->count();
        $assignedToday = DB::table('bookings')
            ->whereDate('updated_at', $today)
            ->whereNotNull('room_id')
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();
        $freeRoomsCount = DB::table('rooms')->where('status', 'available')->count();
        $paidQueueCount = $assignmentQueue->filter(fn ($booking) => (int) ($booking->has_paid_payment ?? 0) === 1)->count();
        $paymentPendingCount = $assignmentQueue->count() - $paidQueueCount;

        return view('receptionist.roomassignment', [
            'arrivalsCount' => $arrivalsCount,
            'unassignedCount' => $assignmentQueue->count(),
            'assignedCount' => $assignedToday,
            'freeRoomsCount' => $freeRoomsCount,
            'paidQueueCount' => $paidQueueCount,
            'paymentPendingCount' => $paymentPendingCount,
            'unassignedReservations' => $assignmentQueue,
            'activeTarget' => $activeTarget,
            'availablePhysicalRooms' => $availablePhysicalRooms,
            'floorsGrid' => $floorsGrid,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $booking = DB::table('bookings')->where('id', $validated['booking_id'])->lockForUpdate()->first();
                $selectedRoom = DB::table('rooms')->where('id', $validated['room_id'])->lockForUpdate()->first();

                if (! $booking || ! in_array($booking->status, ['pending', 'confirmed'], true)) {
                    throw new RuntimeException('Reservasi tidak lagi berada dalam antrean assignment.');
                }

                if (! $selectedRoom || $selectedRoom->status !== 'available') {
                    throw new RuntimeException('Kamar yang dipilih tidak lagi berstatus available.');
                }

                $requiredRoomTypeId = $booking->room_id
                    ? DB::table('rooms')->where('id', $booking->room_id)->value('room_type_id')
                    : null;

                if ($requiredRoomTypeId && (int) $selectedRoom->room_type_id !== (int) $requiredRoomTypeId) {
                    throw new RuntimeException('Kamar pengganti harus memiliki tipe yang sama dengan reservasi.');
                }

                $hasDateConflict = DB::table('bookings')
                    ->where('room_id', $selectedRoom->id)
                    ->where('id', '!=', $booking->id)
                    ->whereIn('status', ['confirmed', 'checked_in'])
                    ->where('check_in', '<', $booking->check_out)
                    ->where('check_out', '>', $booking->check_in)
                    ->exists();

                if ($hasDateConflict) {
                    throw new RuntimeException('Kamar tersebut sudah terikat reservasi lain pada rentang tanggal yang sama.');
                }

                DB::table('bookings')->where('id', $booking->id)->update([
                    'room_id' => $selectedRoom->id,
                    'updated_at' => now(),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('receptionist.checkin', ['booking_id' => $validated['booking_id']])
            ->with('success', 'Kamar berhasil dialokasikan. Lanjutkan proses check-in.');
    }
}
