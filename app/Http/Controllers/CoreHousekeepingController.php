<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CoreHousekeepingController extends Controller
{
    public function roomAvailabilityView(Request $request)
    {
        $today = now()->toDateString();
        $totalRooms = DB::table('rooms')->count();
        $statsDenominator = max(1, $totalRooms);
        $availableCount = DB::table('rooms')->where('status', 'available')->count();
        $occupiedCount = DB::table('rooms')->where('status', 'occupied')->count();
        $maintenanceCount = DB::table('rooms')->where('status', 'maintenance')->count();
        $dueOutCount = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', '%' . $request->search . '%');
        }

        $floorsData = [];
        foreach ($roomsQuery->orderBy('rooms.room_number')->get() as $room) {
            $floorLength = strlen($room->room_number) - 2;
            $floorNumber = $floorLength > 0 ? substr($room->room_number, 0, $floorLength) : '1';
            $floorName = match ($floorNumber) {
                '12' => '12th Floor (Penthouse Zone)',
                '8' => '8th Floor (Executive Wing)',
                '5' => '5th Floor (Sea View)',
                '4' => '4th Floor (City View)',
                '3' => '3rd Floor (Garden View)',
                '2' => '2nd Floor (Pool Access)',
                default => $floorNumber . 'th Floor (Standard Tier)',
            };
            $floorsData[$floorName][] = $room;
        }
        ksort($floorsData);

        $roomTypesList = DB::table('room_types')->get();
        $typeSummaries = [];
        foreach ($roomTypesList as $type) {
            $totalUnits = DB::table('rooms')->where('room_type_id', $type->id)->count();
            $availUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'available')->count();
            $occUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'occupied')->count();
            $maintUnits = DB::table('rooms')->where('room_type_id', $type->id)->where('status', 'maintenance')->count();
            $resvUnits = DB::table('bookings')
                ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
                ->where('rooms.room_type_id', $type->id)
                ->whereDate('bookings.check_in', $today)
                ->where('bookings.status', 'confirmed')
                ->count();

            $typeSummaries[] = [
                'name' => $type->name,
                'total' => $totalUnits,
                'available' => $availUnits,
                'occupied' => $occUnits,
                'reserved' => $resvUnits,
                'maintenance' => $maintUnits,
            ];
        }

        $shares = [
            'available' => round(($availableCount / $statsDenominator) * 100, 1),
            'occupied' => round(($occupiedCount / $statsDenominator) * 100, 1),
            'due_out' => round(($dueOutCount / $statsDenominator) * 100, 1),
            'maintenance' => round(($maintenanceCount / $statsDenominator) * 100, 1),
        ];

        return view('receptionist.roomavailability', compact(
            'totalRooms', 'availableCount', 'occupiedCount', 'maintenanceCount', 'dueOutCount',
            'floorsData', 'typeSummaries', 'shares'
        ));
    }

    public function houseStatusView(Request $request)
    {
        $today = now()->toDateString();
        $totalRooms = DB::table('rooms')->count();
        $statsDenominator = max(1, $totalRooms);
        $vacantClean = DB::table('rooms')->where('status', 'available')->count();
        $vacantDirty = 0;
        $occupied = DB::table('rooms')->where('status', 'occupied')->count();
        $outOfOrder = DB::table('rooms')->where('status', 'maintenance')->count();
        $dueOutToday = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', '%' . $request->search . '%');
        }

        $floorsData = [];
        foreach ($roomsQuery->orderBy('rooms.room_number')->get() as $room) {
            $floorLength = strlen($room->room_number) - 2;
            $floorDigit = $floorLength > 0 ? substr($room->room_number, 0, $floorLength) : '1';
            $floorKey = match ($floorDigit) {
                '12' => ['title' => '12th Floor', 'desc' => 'Penthouse Suite Floor'],
                '8' => ['title' => '8th Floor', 'desc' => 'Executive Wing Floor'],
                '5' => ['title' => '5th Floor', 'desc' => 'Sea View Floor'],
                '4' => ['title' => '4th Floor', 'desc' => 'City View Floor'],
                '3' => ['title' => '3rd Floor', 'desc' => 'Garden View Floor'],
                '2' => ['title' => '2nd Floor', 'desc' => 'Pool Access Floor'],
                default => ['title' => $floorDigit . 'th Floor', 'desc' => 'Standard Facilities Floor'],
            };

            $room->is_due_out = DB::table('bookings')
                ->where('room_id', $room->id)
                ->whereDate('check_out', $today)
                ->where('status', 'checked_in')
                ->exists();

            $floorName = $floorKey['title'] . '|' . $floorKey['desc'];
            $floorsData[$floorName]['rooms'][] = $room;
        }
        ksort($floorsData);

        foreach ($floorsData as $name => $data) {
            $vc = 0;
            $vd = 0;
            $occ = 0;
            $ooo = 0;
            $do = 0;
            foreach ($data['rooms'] as $room) {
                if ($room->status === 'available') {
                    $vc++;
                } elseif ($room->status === 'occupied') {
                    $occ++;
                } elseif ($room->status === 'maintenance') {
                    $ooo++;
                }
                if ($room->is_due_out) {
                    $do++;
                }
            }
            $floorsData[$name]['counters'] = [
                'vc' => $vc,
                'vd' => $vd,
                'occ' => $occ,
                'ooo' => $ooo,
                'do' => $do,
                'total' => count($data['rooms']),
            ];
        }

        $shares = [
            'vc' => round(($vacantClean / $statsDenominator) * 100, 1),
            'vd' => 0,
            'occ' => round(($occupied / $statsDenominator) * 100, 1),
            'ooo' => round(($outOfOrder / $statsDenominator) * 100, 1),
        ];

        return view('receptionist.housestatus', compact(
            'totalRooms', 'vacantClean', 'vacantDirty', 'occupied', 'outOfOrder', 'dueOutToday',
            'floorsData', 'shares'
        ));
    }

    public function updateHouseStatus(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance,dirty'],
        ]);

        $status = $validated['status'] === 'dirty' ? 'maintenance' : $validated['status'];
        DB::table('rooms')->where('id', $validated['room_id'])->update([
            'status' => $status,
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status kamar berhasil diperbarui.');
    }
}
