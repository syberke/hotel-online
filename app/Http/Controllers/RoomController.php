<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Base
        $query = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                     ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price_per_night', 'room_types.foto_url');

        // 2. Logika Filter Tipe Suite (Dari Dropdown Top Bar)
        if ($request->has('suite_type') && $request->suite_type != 'All Room Types') {
            $query->where('room_types.name', $request->suite_type);
        }

        // 3. Logika Filter Checkbox Kategori (Dari Sidebar)
        if ($request->has('categories') && is_array($request->categories)) {
            $query->whereIn('room_types.name', $request->categories);
        }

        // 4. Logika Sorting Sistem
        $sort = $request->get('sort', 'Recommended');
        if ($sort == 'Lowest Price') {
            $query->orderBy('room_types.price_per_night', 'asc');
        } elseif ($sort == 'Highest Price') {
            $query->orderBy('room_types.price_per_night', 'desc');
        } else {
            $query->orderBy('room_types.price_per_night', 'asc'); // Default Recommended
        }

        $roomsLiveList = $query->get();

        // 5. Ambil semua kategori unik untuk menu filter sidebar
        $allCategories = DB::table('room_types')->select('name', 'id')->get();
        $totalInventoryReady = DB::table('rooms')->where('status', 'available')->count();

        return view('rooms', compact('roomsLiveList', 'totalInventoryReady', 'allCategories'));
    }

    /**
     * Menampilkan Detail Room Tertentu
     */
    public function show($id)
    {
        $room = DB::table('room_types')->where('id', $id)->first();
        if (!$room) {
            abort(404, 'Suite Sanctuary Not Found');
        }
        return view('rooms-detail', compact('room'));
    }
}