<?php

namespace App\Http\Controllers;

use App\Models\RestaurantVenue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RestaurantVenueController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->authorizeVenueManagement($request);

        RestaurantVenue::query()->create($this->validatedPayload($request));

        return back()->with('success', 'Dining venue berhasil ditambahkan.');
    }

    public function update(Request $request, RestaurantVenue $venue): RedirectResponse
    {
        $this->authorizeVenueManagement($request);

        $venue->update($this->validatedPayload($request, $venue));

        return back()->with('success', 'Dining venue berhasil diperbarui.');
    }

    public function destroy(Request $request, RestaurantVenue $venue): RedirectResponse
    {
        $this->authorizeVenueManagement($request);

        if ($venue->reservations()->exists()) {
            return back()->with('error', 'Venue tidak dapat dihapus karena sudah mempunyai riwayat reservasi. Nonaktifkan venue sebagai gantinya.');
        }

        $venue->delete();

        return back()->with('success', 'Dining venue berhasil dihapus.');
    }

    private function validatedPayload(Request $request, ?RestaurantVenue $venue = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('restaurant_venues', 'name')->ignore($venue?->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'image_url' => ['nullable', 'url', 'max:2048'],
            'location' => ['nullable', 'string', 'max:255'],
            'opens_at' => ['nullable', 'date_format:H:i'],
            'closes_at' => ['nullable', 'date_format:H:i'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
        ]);

        $validated['reservation_enabled'] = $request->boolean('reservation_enabled');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }

    private function authorizeVenueManagement(Request $request): void
    {
        abort_unless(in_array($request->user()?->role, ['admin', 'manager'], true), 403);
    }
}
