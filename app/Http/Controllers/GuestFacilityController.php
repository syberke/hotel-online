<?php

namespace App\Http\Controllers;

use App\Models\FacilityBooking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GuestFacilityController extends Controller
{
    public function index(): View
    {
        $myReservations = DB::table('facility_bookings')
            ->where('user_id', auth()->id())
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        $facilities = DB::table('facilities')->orderBy('id')->get();

        $facilities->each(function ($facility, $index) {
            $facility->category = $facility->category
                ?: (($index % 4 === 0) ? 'Wellness' : (($index % 3 === 0) ? 'Sports & Fitness' : (($index % 2 === 0) ? 'Pools & Beach' : 'Kids & Family')));
            $facility->access_type = $facility->access_type ?: 'Complimentary';
        });

        $facilitiesPayload = $facilities->map(static fn ($facility) => [
            'id' => (int) $facility->id,
            'name' => (string) ($facility->name ?? ''),
            'description' => (string) ($facility->description ?? ''),
            'image_url' => (string) ($facility->image_url ?? ''),
            'hours' => (string) ($facility->hours ?? 'Hours unavailable'),
            'category' => (string) ($facility->category ?? 'Wellness'),
            'access_type' => (string) ($facility->access_type ?? 'Complimentary'),
            'requires_booking' => (bool) ($facility->requires_booking ?? false),
        ])->values();

        return view('guest.facilitiesbooking', compact('myReservations', 'facilities', 'facilitiesPayload'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_name' => ['required', 'string', 'max:255'],
            'booking_date' => ['required', 'date', 'after_or_equal:today'],
            'booking_time' => ['required', 'date_format:H:i:s'],
            'guests_count' => ['required', 'integer', 'min:1', 'max:10'],
            'seating_preference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $facility = DB::table('facilities')->where('name', $validated['facility_name'])->first();
        if (!$facility) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan. Muat ulang halaman lalu coba lagi.',
            ], 404);
        }

        if (!(bool) $facility->requires_booking) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas ini menggunakan akses walk-in dan tidak memerlukan reservasi.',
            ], 422);
        }

        FacilityBooking::create([
            'user_id' => $request->user()->id,
            'facility_name' => $facility->name,
            'booking_date' => $validated['booking_date'],
            'booking_time' => $validated['booking_time'],
            'guests_count' => $validated['guests_count'],
            'seating_preference' => $validated['seating_preference'] ?? 'No Preference',
            'notes' => $validated['notes'] ?? null,
            'status' => 'confirmed',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil dipesan!',
        ]);
    }
}
