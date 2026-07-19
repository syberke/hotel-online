<?php

namespace App\Http\Controllers;

use App\Services\BookingFolioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomLifecycleController extends FrontOfficeCheckController
{
    public function __construct(private readonly BookingFolioService $folioService)
    {
    }

    public function adminStoreRoom(Request $request)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $validated = $request->validate([
            'room_number' => ['required', 'string', 'unique:rooms,room_number'],
            'room_type_id' => ['required', 'integer', 'exists:room_types,id'],
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
        ]);

        DB::table('rooms')->insert([
            'room_number' => $validated['room_number'],
            'room_type_id' => $validated['room_type_id'],
            'status' => $validated['status'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Kamar baru nomor ' . $validated['room_number'] . ' berhasil didaftarkan.');
    }

    public function adminUpdateRoomStatus(Request $request, $id)
    {
        if (auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Manager tidak memiliki hak akses memodifikasi inventori.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:available,occupied,maintenance'],
        ]);

        DB::table('rooms')->where('id', $id)->update([
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status operasional kamar berhasil diperbarui.');
    }

    public function processCheckOut(Request $request)
    {
        if ($request->isMethod('post') && $request->has('confirm_checkout_id')) {
            $targetId = $request->integer('confirm_checkout_id');
            $bookingRecord = DB::table('bookings')->where('id', $targetId)->first();
            $ledger = $targetId ? $this->folioService->build($targetId) : null;

            if (! $bookingRecord || $bookingRecord->status !== 'checked_in') {
                return redirect()
                    ->route('receptionist.checkout')
                    ->with('error', 'Tamu ini tidak lagi aktif untuk check-out.');
            }

            if (! $ledger) {
                return redirect()
                    ->route('receptionist.checkout', ['booking_id' => $targetId])
                    ->with('error', 'Folio reservasi tidak dapat ditemukan.');
            }

            if ((float) $ledger['balance_due'] > 0) {
                return redirect()
                    ->route('receptionist.payments', ['booking_id' => $targetId])
                    ->with('error', 'Check-out ditahan karena masih ada saldo folio Rp '
                        . number_format($ledger['balance_due'], 0, ',', '.')
                        . ', termasuk Room Service yang belum dibayar.');
            }

            DB::transaction(function () use ($bookingRecord): void {
                DB::table('bookings')->where('id', $bookingRecord->id)->update([
                    'status' => 'checked_out',
                    'updated_at' => now(),
                ]);

                DB::table('rooms')->where('id', $bookingRecord->room_id)->update([
                    'status' => 'maintenance',
                    'updated_at' => now(),
                ]);
            });

            return redirect()
                ->route('receptionist.dashboard')
                ->with('success', 'Check-out berhasil diselesaikan dan folio sudah lunas.');
        }

        $search = trim((string) $request->input('search'));
        $bookingId = $request->integer('booking_id');

        $activeBookingsQuery = DB::table('bookings')
            ->leftJoin('users', 'bookings.user_id', '=', 'users.id')
            ->leftJoin('guests', 'bookings.guest_id', '=', 'guests.id')
            ->leftJoin('rooms', 'bookings.room_id', '=', 'rooms.id')
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->whereIn('bookings.status', ['checked_in', 'confirmed'])
            ->select(
                'bookings.id',
                'bookings.check_in',
                'bookings.check_out',
                'bookings.total_price',
                'bookings.status',
                'bookings.booking_source',
                DB::raw("COALESCE(users.name, guests.name, 'Registered guest') as guest_name"),
                DB::raw("CASE WHEN bookings.booking_source = 'walk_in' THEN NULL ELSE COALESCE(users.email, guests.email) END as guest_email"),
                'rooms.room_number',
                'room_types.name as room_type',
                'room_types.price as room_price'
            );

        if ($search !== '') {
            $cleanId = preg_replace('/\D+/', '', $search);
            $needle = '%' . strtolower($search) . '%';

            $activeBookingsQuery->where(function ($query) use ($cleanId, $needle, $search) {
                $query->whereRaw("LOWER(COALESCE(users.name, guests.name, '')) LIKE ?", [$needle])
                    ->orWhere('rooms.room_number', 'like', '%' . $search . '%');

                if ($cleanId !== '') {
                    $query->orWhere('bookings.id', (int) $cleanId);
                }
            });
        }

        $activeBookings = $activeBookingsQuery
            ->orderByDesc('bookings.created_at')
            ->get();

        if (! $bookingId && $activeBookings->isNotEmpty()) {
            $bookingId = (int) (($search !== '' ? $activeBookings->first() : $activeBookings->firstWhere('status', 'checked_in'))?->id
                ?? $activeBookings->first()->id);
        }

        $selectedBooking = null;
        $charges = [];
        $totalCharges = 0.0;
        $totalPayments = 0.0;
        $balanceDue = 0.0;

        if ($bookingId) {
            $ledger = $this->folioService->build($bookingId);

            if ($ledger && $activeBookings->contains('id', $bookingId)) {
                $selectedBooking = $ledger['booking'];
                $charges = $ledger['charges']
                    ->map(fn ($charge) => [
                        'date' => $charge->date,
                        'description' => $charge->description,
                        'reference' => $charge->reference,
                        'debit' => $charge->debit,
                        'credit' => $charge->credit,
                    ])
                    ->all();
                $totalCharges = $ledger['total_charges'];
                $totalPayments = $ledger['total_payments'];
                $balanceDue = $ledger['balance_due'];
            }
        }

        return view('receptionist.checkout', compact(
            'selectedBooking',
            'activeBookings',
            'charges',
            'totalCharges',
            'totalPayments',
            'balanceDue',
            'search'
        ));
    }
}
