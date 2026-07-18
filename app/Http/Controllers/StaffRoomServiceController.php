<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StaffRoomServiceController extends Controller
{
    public function index(Request $request): View
    {
        $folioBookings = $this->folioBookingSubquery();

        $roomServiceOrders = DB::table('restaurant_orders')
            ->whereExists(function ($query): void {
                $query->selectRaw('1')
                    ->from('payments')
                    ->whereColumn('payments.restaurant_order_id', 'restaurant_orders.id')
                    ->whereNotNull('payments.booking_id');
            });

        $stats = [
            'total' => (clone $roomServiceOrders)->count(),
            'pending' => (clone $roomServiceOrders)->where('restaurant_orders.status', 'ordered')->count(),
            'in_progress' => (clone $roomServiceOrders)->where('restaurant_orders.status', 'preparing')->count(),
            'completed' => (clone $roomServiceOrders)->where('restaurant_orders.status', 'paid')->count(),
            'cancelled' => (clone $roomServiceOrders)->where('restaurant_orders.status', 'cancelled')->count(),
        ];

        $query = $this->ordersQuery($folioBookings);
        $currentTab = $request->string('tab', 'all')->toString();

        match ($currentTab) {
            'pending' => $query->where('restaurant_orders.status', 'ordered'),
            'in_progress' => $query->where('restaurant_orders.status', 'preparing'),
            'completed' => $query->where('restaurant_orders.status', 'paid'),
            'cancelled' => $query->where('restaurant_orders.status', 'cancelled'),
            default => null,
        };

        $search = trim((string) $request->input('search'));
        if ($search !== '') {
            $numericOrderId = preg_replace('/\D+/', '', $search);
            $needle = '%' . strtolower($search) . '%';

            $query->where(function ($searchQuery) use ($numericOrderId, $needle, $search): void {
                if ($numericOrderId !== '') {
                    $searchQuery->where('restaurant_orders.id', (int) $numericOrderId);
                } else {
                    $searchQuery->whereRaw('1 = 0');
                }

                $searchQuery
                    ->orWhereRaw('LOWER(guests.name) LIKE ?', [$needle])
                    ->orWhere('rooms.room_number', 'like', '%' . $search . '%');
            });
        }

        $orders = $query
            ->orderByDesc('restaurant_orders.created_at')
            ->paginate(10)
            ->withQueryString();

        $selectedOrderId = $request->integer('selected_id') ?: null;
        if ($selectedOrderId === null && $orders->isNotEmpty()) {
            $selectedOrderId = (int) $orders->first()->id;
        }

        $selectedOrder = null;
        $orderItems = collect();

        if ($selectedOrderId !== null) {
            $selectedOrder = $this->ordersQuery($folioBookings)
                ->where('restaurant_orders.id', $selectedOrderId)
                ->first();

            if ($selectedOrder !== null) {
                $orderItems = DB::table('restaurant_order_details')
                    ->join('restaurant_menus', 'restaurant_order_details.restaurant_menu_id', '=', 'restaurant_menus.id')
                    ->where('restaurant_order_details.restaurant_order_id', $selectedOrderId)
                    ->select('restaurant_order_details.*', 'restaurant_menus.name as menu_name')
                    ->orderBy('restaurant_order_details.id')
                    ->get();
            }
        }

        return view('admin.roomservice', compact(
            'stats',
            'orders',
            'currentTab',
            'selectedOrder',
            'orderItems',
            'selectedOrderId',
        ));
    }

    private function folioBookingSubquery(): Builder
    {
        return DB::table('payments')
            ->whereNotNull('restaurant_order_id')
            ->whereNotNull('booking_id')
            ->select(
                'restaurant_order_id',
                DB::raw('MAX(booking_id) as booking_id'),
            )
            ->groupBy('restaurant_order_id');
    }

    private function ordersQuery(Builder $folioBookings): Builder
    {
        return DB::table('restaurant_orders')
            ->join('guests', 'restaurant_orders.guest_id', '=', 'guests.id')
            ->joinSub(clone $folioBookings, 'order_folio', function ($join): void {
                $join->on('restaurant_orders.id', '=', 'order_folio.restaurant_order_id');
            })
            ->join('bookings', 'order_folio.booking_id', '=', 'bookings.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'restaurant_orders.*',
                'guests.name as guest_name',
                'guests.phone as guest_phone',
                'guests.foto_url as guest_avatar',
                'rooms.room_number',
                'room_types.name as room_type_name',
                DB::raw("CASE WHEN room_types.price >= 1500000 THEN 1 ELSE 0 END as is_vip"),
            );
    }
}
