<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CoreHousekeepingController extends Controller
{
    private const ROOM_STATUSES = ['available', 'occupied', 'maintenance'];

    public function roomAvailabilityView(Request $request): View
    {
        $today = now()->toDateString();
        $statusFilter = in_array($request->string('status')->toString(), self::ROOM_STATUSES, true)
            ? $request->string('status')->toString()
            : null;

        $totalRooms = DB::table('rooms')->count();
        $availableCount = DB::table('rooms')->where('status', 'available')->count();
        $occupiedCount = DB::table('rooms')->where('status', 'occupied')->count();
        $maintenanceCount = DB::table('rooms')->where('status', 'maintenance')->count();

        $dueOutRoomIds = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->whereNotNull('room_id')
            ->pluck('room_id')
            ->map(static fn ($roomId) => (int) $roomId)
            ->unique();

        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $roomsQuery->where(function ($query) use ($search): void {
                $query->where('rooms.room_number', 'like', '%' . $search . '%')
                    ->orWhereRaw('LOWER(room_types.name) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($statusFilter !== null) {
            $roomsQuery->where('rooms.status', $statusFilter);
        }

        $floorsData = [];
        foreach ($roomsQuery->orderBy('rooms.room_number')->get() as $room) {
            $room->is_due_out = $dueOutRoomIds->contains((int) $room->id);
            $floorsData[$this->floorLabel((string) $room->room_number)][] = $room;
        }

        ksort($floorsData, SORT_NATURAL);

        return view('receptionist.roomavailability', compact(
            'totalRooms',
            'availableCount',
            'occupiedCount',
            'maintenanceCount',
            'dueOutRoomIds',
            'floorsData',
            'statusFilter',
        ));
    }

    public function houseStatusView(): RedirectResponse
    {
        return redirect()->route('receptionist.roomavailability');
    }

    public function updateHouseStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'status' => ['required', 'string', 'in:' . implode(',', self::ROOM_STATUSES)],
        ]);

        DB::table('rooms')->where('id', $validated['room_id'])->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()
            ->route('receptionist.roomavailability')
            ->with('success', 'Status kamar berhasil diperbarui.');
    }

    private function floorLabel(string $roomNumber): string
    {
        $prefixLength = max(0, strlen($roomNumber) - 2);
        $floor = $prefixLength > 0 ? substr($roomNumber, 0, $prefixLength) : '1';

        return match ($floor) {
            '1' => '1st Floor',
            '2' => '2nd Floor',
            '3' => '3rd Floor',
            default => $floor . 'th Floor',
        };
    }
}
