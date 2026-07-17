<?php

namespace App\Http\Controllers;

use App\Models\RestaurantReservation;
use App\Models\RestaurantVenue;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestaurantReservationController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        abort_unless($request->user()?->role === 'guest', 403);

        $validated = $request->validate([
            'restaurant_venue_id' => ['required', 'integer', 'exists:restaurant_venues,id'],
            'reservation_date' => ['required', 'date', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:20'],
            'seating_preference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $venue = RestaurantVenue::query()
            ->whereKey($validated['restaurant_venue_id'])
            ->where('is_active', true)
            ->where('reservation_enabled', true)
            ->firstOrFail();

        $time = $validated['reservation_time'] . ':00';
        $reservedGuests = (int) RestaurantReservation::query()
            ->where('restaurant_venue_id', $venue->id)
            ->whereDate('reservation_date', $validated['reservation_date'])
            ->where('reservation_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('guests_count');

        if (($reservedGuests + (int) $validated['guests_count']) > $venue->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Slot tersebut tidak memiliki kapasitas yang cukup. Pilih jam atau venue lain.',
            ], 422);
        }

        $reservation = DB::transaction(fn () => RestaurantReservation::query()->create([
            'user_id' => $request->user()->id,
            'restaurant_venue_id' => $venue->id,
            'reservation_date' => $validated['reservation_date'],
            'reservation_time' => $time,
            'guests_count' => $validated['guests_count'],
            'seating_preference' => $validated['seating_preference'] ?? 'No preference',
            'notes' => $validated['notes'] ?? null,
            'status' => 'pending',
        ]));

        $message = 'Reservasi meja #' . str_pad((string) $reservation->id, 4, '0', STR_PAD_LEFT) . ' tersimpan dan menunggu konfirmasi hotel.';

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function updateStatus(Request $request, RestaurantReservation $reservation): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $reservation->update($validated);

        return back()->with('success', 'Status reservasi restoran berhasil diperbarui.');
    }

    public function destroy(Request $request, RestaurantReservation $reservation): RedirectResponse
    {
        $this->authorizeAdmin($request);

        if (! in_array($reservation->status, ['cancelled', 'completed'], true)) {
            return back()->with('error', 'Reservasi harus selesai atau dibatalkan sebelum dihapus.');
        }

        $reservation->delete();

        return back()->with('success', 'Riwayat reservasi restoran berhasil dihapus.');
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
