<?php

namespace App\Http\Controllers;

use App\Models\FacilityBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuestServiceController extends Controller
{
    public function roomService(Request $request)
    {
        $userId = auth()->id();

        $allActiveBookings = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select('bookings.id', 'bookings.status', 'rooms.room_number')
            ->orderByRaw("CASE bookings.status WHEN 'checked_in' THEN 0 ELSE 1 END")
            ->orderByDesc('bookings.check_in')
            ->get();

        $targetBookingId = $request->integer('booking_id') ?: optional($allActiveBookings->first())->id;

        $currentBooking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.id', $targetBookingId)
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select(
                'bookings.id as booking_id',
                'bookings.status',
                'rooms.room_number',
                'room_types.name as room_name',
            )
            ->first();

        $menus = DB::table('restaurant_menus')
            ->where('is_available', true)
            ->select(
                'id',
                'name',
                'description',
                'price',
                'foto_url',
                'category',
                DB::raw('0 as sales_count'),
            )
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) auth()->user()->email)])
            ->first();

        $orderHistory = $guest
            ? DB::table('restaurant_orders')
                ->leftJoin('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
                ->where('restaurant_orders.guest_id', $guest->id)
                ->when($targetBookingId, fn ($query) => $query->where('payments.booking_id', $targetBookingId))
                ->select(
                    'restaurant_orders.id',
                    'restaurant_orders.total_price',
                    'restaurant_orders.status',
                    'restaurant_orders.created_at',
                    'payments.payment_status',
                    'payments.payment_method',
                    'payments.booking_id',
                )
                ->orderByDesc('restaurant_orders.created_at')
                ->take(5)
                ->get()
            : collect();

        return view('guest.roomservice', compact(
            'menus',
            'orderHistory',
            'currentBooking',
            'allActiveBookings',
        ));
    }

    public function storeRoomServiceOrder(Request $request)
    {
        $validated = $request->validate([
            'cart_data' => ['required', 'string'],
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
        ]);

        $booking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.id', $validated['booking_id'])
            ->where('bookings.user_id', auth()->id())
            ->whereIn('bookings.status', ['confirmed', 'checked_in'])
            ->select('bookings.id', 'rooms.room_number')
            ->first();

        if (! $booking) {
            return back()->with('error', 'Room Service hanya tersedia untuk reservasi terkonfirmasi atau tamu yang sedang check-in.');
        }

        $cartItems = json_decode($validated['cart_data'], true);
        if (! is_array($cartItems) || $cartItems === []) {
            return back()->with('error', 'Keranjang Room Service masih kosong.');
        }

        $guest = DB::table('guests')
            ->whereRaw('LOWER(email) = ?', [strtolower((string) auth()->user()->email)])
            ->first();

        if (! $guest) {
            return back()->with('error', 'Profil guest belum tersedia.');
        }

        $calculation = $this->calculateRestaurantCart($cartItems);
        if ($calculation === null) {
            return back()->with('error', 'Salah satu menu sudah tidak tersedia. Muat ulang halaman dan pilih ulang pesanan.');
        }

        $orderId = DB::transaction(function () use ($guest, $booking, $calculation): int {
            $id = DB::table('restaurant_orders')->insertGetId([
                'guest_id' => $guest->id,
                'total_price' => $calculation['grand_total'],
                'status' => 'ordered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($calculation['items'] as $item) {
                DB::table('restaurant_order_details')->insert([
                    'restaurant_order_id' => $id,
                    'restaurant_menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('payments')->insert([
                'booking_id' => $booking->id,
                'restaurant_order_id' => $id,
                'amount' => $calculation['grand_total'],
                'payment_method' => 'cash',
                'payment_status' => 'pending',
                'note' => 'Room Service charged to room folio · Room ' . $booking->room_number,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $id;
        });

        return redirect()
            ->route('guest.room.service', ['booking_id' => $booking->id])
            ->with('success', 'Room Service #' . str_pad((string) $orderId, 4, '0', STR_PAD_LEFT) . ' berhasil dikirim dan ditambahkan ke folio kamar.');
    }

    public function restaurantOrders(Request $request)
    {
        $userId = auth()->id();
        $allActiveBookings = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->where('bookings.user_id', $userId)
            ->whereIn('bookings.status', ['confirmed', 'pending', 'checked_in'])
            ->select('bookings.id', 'rooms.room_number')
            ->get();

        $bookingId = $request->input('booking_id', optional($allActiveBookings->first())->id);
        $currentRoomBooking = DB::table('bookings')
            ->join('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->where('bookings.id', $bookingId)
            ->select('bookings.id as booking_id', 'rooms.room_number', 'room_types.name as room_name')
            ->first();

        $roomNumber = $currentRoomBooking?->room_number;
        $restaurantMenus = DB::table('restaurant_menus')
            ->where('is_available', true)
            ->select('id', 'name as title', 'description', 'price', 'foto_url as image_url')
            ->get();

        $restaurantMenus = $restaurantMenus->map(function ($menu, $index) {
            $menu->is_signature = ($index === 0 || $menu->id % 4 === 0);
            $menu->venue_name = ($menu->id % 3 === 0)
                ? 'The Beach Club'
                : (($menu->id % 2 === 0) ? 'The Garden Atrium' : 'Oasis Fine Dining');

            return $menu;
        });

        $guest = DB::table('guests')->whereRaw('LOWER(email) = ?', [strtolower((string) auth()->user()->email)])->first();
        $orderHistory = $guest
            ? DB::table('restaurant_orders')
                ->leftJoin('payments', 'restaurant_orders.id', '=', 'payments.restaurant_order_id')
                ->where('restaurant_orders.guest_id', $guest->id)
                ->select(
                    'restaurant_orders.id',
                    'restaurant_orders.total_price',
                    'restaurant_orders.created_at',
                    DB::raw("COALESCE(payments.payment_status, 'pending') as payment_status"),
                )
                ->orderByDesc('restaurant_orders.id')
                ->get()
            : collect();

        return view('guest.restaurantorders', [
            'restaurant_menus' => $restaurantMenus,
            'room_number' => $roomNumber,
            'booking_id' => $bookingId,
            'allActiveBookings' => $allActiveBookings,
            'orderHistory' => $orderHistory,
        ]);
    }

    public function facilitiesBooking()
    {
        $myReservations = DB::table('facility_bookings')
            ->where('user_id', auth()->id())
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();
        $facilities = DB::table('facilities')->orderBy('id')->get();

        $facilities = $facilities->map(function ($facility, $index) {
            if (empty($facility->category)) {
                $facility->category = ($index % 4 === 0)
                    ? 'Wellness'
                    : (($index % 3 === 0) ? 'Sports & Fitness' : (($index % 2 === 0) ? 'Pools & Beach' : 'Kids & Family'));
            }
            if (empty($facility->access_type)) {
                $facility->access_type = 'Complimentary';
            }

            return $facility;
        });

        return view('guest.facilitiesbooking', compact('myReservations', 'facilities'));
    }

    public function placeGastronomyOrder(Request $request)
    {
        $request->validate(['total_price' => 'required|numeric|min:0']);
        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login.'], 401);
        }

        $guest = DB::table('guests')->where('email', Auth::user()->email)->first();
        if (! $guest) {
            return response()->json(['success' => false, 'message' => 'Profil tamu tidak ditemukan.'], 404);
        }

        try {
            DB::table('restaurant_orders')->insert([
                'guest_id' => $guest->id,
                'total_price' => (int) round($request->total_price),
                'status' => 'ordered',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Pesanan kuliner dikonfirmasi!']);
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Gagal memproses.'], 500);
        }
    }

    public function bookFacility(Request $request)
    {
        $request->validate([
            'facility_name' => 'required|string',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required',
            'guests_count' => 'required|integer|min:1|max:10',
        ]);

        if (! Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login.'], 401);
        }

        try {
            FacilityBooking::create([
                'user_id' => Auth::id(),
                'facility_name' => $request->facility_name,
                'booking_date' => $request->booking_date,
                'booking_time' => $request->booking_time,
                'guests_count' => (int) $request->guests_count,
                'seating_preference' => $request->seating_preference ?? 'No Preference',
                'notes' => $request->notes ?? null,
                'status' => 'confirmed',
            ]);

            return response()->json(['success' => true, 'message' => 'Fasilitas berhasil dipesan!']);
        } catch (\Exception) {
            return response()->json(['success' => false, 'message' => 'Gagal mengamankan slot.'], 500);
        }
    }

    private function calculateRestaurantCart(array $cartItems): ?array
    {
        $normalized = collect($cartItems)
            ->filter(fn ($item) => is_array($item) && isset($item['id'], $item['quantity']))
            ->map(fn ($item) => [
                'id' => (int) $item['id'],
                'quantity' => max(1, min(20, (int) $item['quantity'])),
            ])
            ->values();

        if ($normalized->isEmpty()) {
            return null;
        }

        $menuIds = $normalized->pluck('id')->unique()->values();
        $menus = DB::table('restaurant_menus')
            ->whereIn('id', $menuIds)
            ->where('is_available', true)
            ->select('id', 'name', 'price')
            ->get()
            ->keyBy('id');

        if ($menus->count() !== $menuIds->count()) {
            return null;
        }

        $items = [];
        $subtotal = 0;

        foreach ($normalized as $cartItem) {
            $menu = $menus->get($cartItem['id']);
            $price = (int) round((float) $menu->price);
            $items[] = [
                'id' => (int) $menu->id,
                'name' => $menu->name,
                'quantity' => $cartItem['quantity'],
                'price' => $price,
            ];
            $subtotal += $price * $cartItem['quantity'];
        }

        $serviceCharge = (int) round($subtotal * 0.10);
        $tax = (int) round(($subtotal + $serviceCharge) * 0.11);

        return [
            'items' => $items,
            'grand_total' => $subtotal + $serviceCharge + $tax,
        ];
    }
}
