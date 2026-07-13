<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HousekeepingController extends Controller
{
    public function updateHouseStatus(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'status' => ['required', 'in:available,dirty,maintenance,occupied'],
        ]);

        DB::table('rooms')->where('id', $validated['room_id'])->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Status kamar berhasil diperbarui.');
    }

    public function roomAvailabilityView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. CALCULATION STATISTIC COUNTERS (REAL-TIME LEDGER)
        // ======================================================================
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $availableCount = DB::table('rooms')->where('status', 'available')->count();
        $occupiedCount = DB::table('rooms')->where('status', 'occupied')->count();
        $maintenanceCount = DB::table('rooms')->where('status', 'maintenance')->count();
        $dirtyCount = DB::table('rooms')->where('status', 'dirty')->count();
        
        $dueOutCount = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        // ======================================================================
        // 2. MATRIX FLOOR GROUP GENERATOR (MAPPING ENCLAVE GRID)
        // ======================================================================
        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', "%{$request->search}%");
        }

        $allRooms = $roomsQuery->orderBy('rooms.room_number', 'asc')->get();

        $floorsData = [];
        foreach ($allRooms as $room) {
            // SINKRONISASI LOGIKA DIGIT LANTAI: Potong nomor kamar tanpa 2 digit terakhir
            $floorLength = strlen($room->room_number) - 2;
            $floorNumber = $floorLength > 0 ? substr($room->room_number, 0, $floorLength) : '1';

            $floorName = match($floorNumber) {
                '12' => '12th Floor (Penthouse Zone)',
                '8'  => '8th Floor (Executive Wing)',
                '5'  => '5th Floor (Sea View)',
                '4'  => '4th Floor (City View)',
                '3'  => '3rd Floor (Garden View)',
                '2'  => '2nd Floor (Pool Access)',
                default => $floorNumber . 'th Floor (Standard Tier)'
            };

            $floorsData[$floorName][] = $room;
        }
        ksort($floorsData);

        // ======================================================================
        // 3. TABLE MATRIX: RATIO SUMMARY PER ROOM TYPE CLASS
        // ======================================================================
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
                'maintenance' => $maintUnits
            ];
        }

        // ======================================================================
        // 4. RATIO PRESENTATION GENERATOR (SVG RADAR)
        // ======================================================================
        $shares = [
            'available' => round(($availableCount / $totalRooms) * 100, 1),
            'occupied' => round(($occupiedCount / $totalRooms) * 100, 1),
            'due_out' => round(($dueOutCount / $totalRooms) * 100, 1),
            'maintenance' => round(($maintenanceCount / $totalRooms) * 100, 1),
        ];

        return view('receptionist.roomavailability', compact(
            'totalRooms', 'availableCount', 'occupiedCount', 'maintenanceCount', 'dueOutCount',
            'floorsData', 'typeSummaries', 'shares'
        ));
    }

    public function houseStatusView(Request $request)
    {
        $today = now()->format('Y-m-d');

        // ======================================================================
        // 1. ENGINE METRIK COUNTER ATAS (KUMULATIF STATUS REAL-TIME)
        // ======================================================================
        $totalRooms = DB::table('rooms')->count() ?: 1;
        $vacantClean = DB::table('rooms')->where('status', 'available')->count();
        $vacantDirty = DB::table('rooms')->where('status', 'dirty')->count();
        $occupied = DB::table('rooms')->where('status', 'occupied')->count();
        $outOfOrder = DB::table('rooms')->where('status', 'maintenance')->count();
        
        $dueOutToday = DB::table('bookings')
            ->whereDate('check_out', $today)
            ->where('status', 'checked_in')
            ->count();

        // ======================================================================
        // 2. QUERY MASTER MATRIX ROOM PER LANTAI
        // ======================================================================
        $roomsQuery = DB::table('rooms')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select('rooms.*', 'room_types.name as type_name');

        if ($request->filled('search')) {
            $roomsQuery->where('rooms.room_number', 'like', "%{$request->search}%");
        }

        $allRooms = $roomsQuery->orderBy('rooms.room_number', 'asc')->get();

        $floorsData = [];
        foreach ($allRooms as $room) {
            // SINKRONISASI LOGIKA DIGIT LANTAI: Buang 2 angka belakang nomor unit
            $floorLength = strlen($room->room_number) - 2;
            $floorDigit = $floorLength > 0 ? substr($room->room_number, 0, $floorLength) : '1';

            $floorKey = match($floorDigit) {
                '12' => ['title' => '12th Floor', 'desc' => 'Penthouse Suite Floor'],
                '8'  => ['title' => '8th Floor', 'desc' => 'Executive Wing Floor'],
                '5'  => ['title' => '5th Floor', 'desc' => 'Sea View Floor'],
                '4'  => ['title' => '4th Floor', 'desc' => 'City View Floor'],
                '3'  => ['title' => '3rd Floor', 'desc' => 'Garden View Floor'],
                '2'  => ['title' => '2nd Floor', 'desc' => 'Pool Access Floor'],
                default => ['title' => $floorDigit . 'th Floor', 'desc' => 'Standard Facilities Floor']
            };

            $floorName = $floorKey['title'] . '|' . $floorKey['desc'];

            $isDueOut = DB::table('bookings')
                ->where('room_id', $room->id)
                ->whereDate('check_out', $today)
                ->where('status', 'checked_in')
                ->exists();

            $room->is_due_out = $isDueOut;
            $floorsData[$floorName]['rooms'][] = $room;
        }
        ksort($floorsData);

        // Hitung akumulasi angka status breakdown per baris lantai
        foreach ($floorsData as $name => $data) {
            $vc = 0; $vd = 0; $occ = 0; $ooo = 0; $do = 0;
            foreach ($data['rooms'] as $r) {
                if ($r->status == 'available') $vc++;
                elseif ($r->status == 'dirty') $vd++;
                elseif ($r->status == 'occupied') $occ++;
                elseif ($r->status == 'maintenance') $ooo++;
                if ($r->is_due_out) $do++;
            }
            $floorsData[$name]['counters'] = [
                'vc' => $vc, 'vd' => $vd, 'occ' => $occ, 'ooo' => $ooo, 'do' => $do, 'total' => count($data['rooms'])
            ];
        }

        // ======================================================================
        // 3. KALKULASI PROPORSI PERSENTASE DIAGRAM LINGKARAN (RADAR)
        // ======================================================================
        $shares = [
            'vc' => round(($vacantClean / $totalRooms) * 100, 1),
            'vd' => round(($vacantDirty / $totalRooms) * 100, 1),
            'occ' => round(($occupied / $totalRooms) * 100, 1),
            'ooo' => round(($outOfOrder / $totalRooms) * 100, 1),
        ];

        return view('receptionist.housestatus', compact(
            'totalRooms', 'vacantClean', 'vacantDirty', 'occupied', 'outOfOrder', 'dueOutToday',
            'floorsData', 'shares'
        ));
    }
}
