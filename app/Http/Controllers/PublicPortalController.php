<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PublicPortalController extends Controller
{
    public function index()
    {
        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                    ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $culinaryMenus = DB::table('restaurant_menus')->get();

        return view('page.home', compact('roomsLiveList', 'culinaryMenus'));
    }

    public function allRoomsView()
    {
        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                    ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night', 
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $allCategories = DB::table('room_types')->select('name')->get();
        $totalInventoryReady = DB::table('rooms')->where('status', 'available')->count();

        return view('page.rooms', compact('roomsLiveList', 'allCategories', 'totalInventoryReady')); 
    }

    public function roomShow($id, Request $request)
    {
        $checkIn = $request->input('check_in', date('Y-m-d'));
        $checkOut = $request->input('check_out', date('Y-m-d', strtotime('+1 day')));

        $occupiedCount = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id') 
            ->where('rooms.room_type_id', $id) 
            ->whereIn('bookings.status', ['confirmed', 'pending', 'checked_in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->where(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '<=', $checkIn)
                    ->where('bookings.check_out', '>', $checkIn);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '<', $checkOut)
                    ->where('bookings.check_out', '>=', $checkOut);
                })
                ->orWhere(function ($q) use ($checkIn, $checkOut) {
                    $q->where('bookings.check_in', '>=', $checkIn)
                    ->where('bookings.check_out', '<=', $checkOut);
                });
            })
            ->count();

        $room = DB::table('room_types')->where('id', $id)->first();

        if (!$room) { 
            abort(404); 
        }

        $room->price_per_night = $room->price;

        $totalPhysicalRooms = DB::table('rooms')
            ->where('room_type_id', $id)
            ->where('status', 'available')
            ->count();

        $liveAvailableCount = max(0, $totalPhysicalRooms - $occupiedCount);
        $room->available_count = $liveAvailableCount;
        
        return view('page.rooms-detail', compact('room'));
    }

 public function checkAvailability(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'suite_type' => 'required|string',
            'guests' => 'required|string',
            'room_id' => 'nullable|integer'
        ]);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $guestsCount = (int) filter_var($request->guests, FILTER_SANITIZE_NUMBER_INT) ?: 2;

        $roomTypeMaster = DB::table('room_types')->where('name', $request->suite_type)->first();
        if (!$roomTypeMaster) {
            return response()->json(['success' => false, 'message' => 'Tipe suite tidak terdaftar.'], 404);
        }

        $maxCapacityAllowed = 2;
        if (str_contains(strtolower($roomTypeMaster->name), 'deluxe')) { $maxCapacityAllowed = 4; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'executive')) { $maxCapacityAllowed = 6; }
        elseif (str_contains(strtolower($roomTypeMaster->name), 'family')) { $maxCapacityAllowed = 8; }

        if ($guestsCount > $maxCapacityAllowed) {
            return response()->json(['success' => false, 'message' => "Kamar " . $roomTypeMaster->name . " maksimal untuk " . $maxCapacityAllowed . " tamu."], 422);
        }

        // Hitung okupansi kamar riil untuk memastikan kuota tipe kamar masih tersedia
        $occupiedRoomIds = DB::table('bookings')
            ->whereIn('status', ['confirmed', 'pending', 'checked_in'])
            ->where('check_in', '<', $checkOut)
            ->where('check_out', '>', $checkIn)
            ->pluck('room_id')
            ->toArray();

        $room = DB::table('rooms')
            ->where('room_type_id', $roomTypeMaster->id)
            ->where('status', 'available') 
            ->whereNotIn('id', $occupiedRoomIds) 
            ->orderBy('room_number', 'asc')
            ->first();

        // KONDISI A: Hanya mengecek ketersediaan (AJAX Live Check)
        if ($request->wantsJson() && $request->has('mode_check_only')) {
            if (!$room) {
                return response()->json([
                    'available' => false, 
                    'message' => 'Maaf, seluruh nomor kamar tipe ' . $request->suite_type . ' sudah penuh pada tanggal tersebut.'
                ]);
            }
            return response()->json([
                'available' => true, 
                'room_id' => $room->id, // Kirim ID kamar ke front-end
                'message' => 'Kamar tipe ' . $request->suite_type . ' tersedia dan siap dipesan!'
            ]);
        }

        // KONDISI B: Eksekusi Menyimpan Pemesanan Kamar Nyata
        if (!$room) {
            return response()->json(['success' => false, 'message' => 'Kamar pilihan Anda baru saja terisi. Silakan lakukan cek ulang.'], 422);
        }

        $days = max(1, (strtotime($checkOut) - strtotime($checkIn)) / (60 * 60 * 24));
        $totalPrice = $roomTypeMaster->price * $days;

        if (Auth::check()) {
            $user = Auth::user();

            // STRICT PROFILE GUARD GATE
            if ($user->role === 'guest') {
                $guestRecord = DB::table('guests')->where('email', $user->email)->first();

                if (!$guestRecord || empty($guestRecord->phone) || empty($guestRecord->identity_number) || empty($guestRecord->address)) {
                    Session::flash('info', 'Harap lengkapi nomor identitas (KTP/Passport), nomor telepon aktif, dan alamat rumah Anda terlebih dahulu sebelum membuat reservasi online.');
                    return response()->json([
                        'success' => false,
                        'redirect' => route('profile.edit'),
                        'message' => 'Profil akun belum lengkap.'
                    ], 403);
                }
            }

            DB::transaction(function () use ($room, $totalPrice, $checkIn, $checkOut) {
                // Simpan dengan status 'pending' (Wajib bayar dulu)
                $bookingId = DB::table('bookings')->insertGetId([
                    'user_id'      => Auth::id(),
                    'guest_id'     => Auth::id(), 
                    'room_id'      => $room->id, 
                    'check_in'     => $checkIn,
                    'check_out'    => $checkOut,
                    'total_price'  => $totalPrice,
                    'status'       => 'pending', // KOREKSI: Set awal ke pending
                    'created_at'   => now(),
                    'updated_at'   => now()
                ]);

                DB::table('payments')->insert([
                    'booking_id'     => $bookingId, 
                    'amount'         => $totalPrice,
                    'payment_method' => 'transfer',
                    'payment_status' => 'pending', // Status pembayaran tertahan sebelum lunas
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
        }); 

            // Mengembalikan redirect sukses ke dashboard guest untuk memproses invoice/Midtrans
            return response()->json(['success' => true, 'redirect' => route('guest.dashboard')]);
        }

        return response()->json(['success' => false, 'redirect' => route('login'), 'message' => 'Silakan login terlebih dahulu.']);
    }

  public function restaurantIndex(Request $request)
    {
        $query = DB::table('restaurant_menus');

        if ($request->has('category') && $request->category != 'All Menu') {
            $query->where(function($q) use ($request) {
                $q->where('description', 'ILIKE', '%' . $request->category . '%')
                ->orWhere('name', 'ILIKE', '%' . $request->category . '%');
            });
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('dietary') && is_array($request->dietary)) {
            $query->where(function($q) use ($request) {
                $q->orWhere('description', 'ILIKE', '%' . $diet . '%');
            });
        }

        // 1. Eksekusi ambil data menu hasil filter
        $culinaryMenus = $query->get();

        // 2. KOREKSI SAKTI: Hitung jumlah total item menu yang berhasil ditarik
        $totalMenuItems = $culinaryMenus->count();

        // 3. Kirimkan KEDUA variabel tersebut ke dalam file Blade
        return view('page.restaurant', compact('culinaryMenus', 'totalMenuItems'));
    }

    public function menuShow($id)
    {
        $menu = DB::table('restaurant_menus')->where('id', $id)->first();
        if (!$menu) { abort(404); }
        return view('page.restaurants-detail', compact('menu'));
    }

    public function facilitiesIndex()
    {
        $facilities = DB::table('facilities')
            ->select('id', 'name', 'description', 'image_url', 'hours', 'requires_booking', 'category', 'access_type')
            ->orderBy('id', 'asc')
            ->get();

        $facilities = $facilities->map(function($f, $index) {
            if (empty($f->category)) {
                if ($index % 4 === 0) $f->category = 'Wellness';
                elseif ($index % 3 === 0) $f->category = 'Sports & Fitness';
                elseif ($index % 2 === 0) $f->category = 'Pools & Beach';
                else $f->category = 'Kids & Family';
            }
            if (empty($f->access_type)) { $f->access_type = 'Premium Access'; }
            return $f;
        });

        $roomsLiveList = DB::table('room_types')
            ->leftJoin('rooms', function($join) {
                $join->on('room_types.id', '=', 'rooms.room_type_id')
                    ->where('rooms.status', '=', 'available');
            })
            ->select(
                'room_types.id',
                'room_types.name',
                'room_types.description',
                'room_types.price as price_per_night',
                'room_types.foto_url',
                DB::raw('COUNT(rooms.id) as available_count')
            )
            ->groupBy('room_types.id', 'room_types.name', 'room_types.description', 'room_types.price', 'room_types.foto_url')
            ->orderBy('room_types.price', 'asc')
            ->get();

        $culinaryMenus = DB::table('restaurant_menus')->take(3)->get();

        return view('page.facilities', compact('facilities', 'roomsLiveList', 'culinaryMenus')); 
    }

    public function changeLanguage($locale)
    {
        if (in_array($locale, ['en', 'id'])) { Session::put('locale', $locale); }
        return redirect()->back();
    }
}