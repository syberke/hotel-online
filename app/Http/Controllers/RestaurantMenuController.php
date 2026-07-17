<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RestaurantMenuController extends Controller
{
    private const CATEGORIES = [
        'Appetizers',
        'Main Courses',
        'Seafood',
        'Steak Selection',
        'Desserts',
        'Beverages',
    ];

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $validated = $this->validatedPayload($request);

        DB::table('restaurant_menus')->insert($validated + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Menu restoran berhasil ditambahkan.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $this->authorizeAdmin($request);
        $menu = DB::table('restaurant_menus')->where('id', $id)->first();
        abort_unless($menu, 404);

        $validated = $this->validatedPayload($request);
        if (blank($validated['foto_url'])) {
            $validated['foto_url'] = $menu->foto_url;
        }

        DB::table('restaurant_menus')->where('id', $id)->update($validated + [
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Menu restoran berhasil diperbarui.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $this->authorizeAdmin($request);

        if (DB::table('restaurant_order_details')->where('restaurant_menu_id', $id)->exists()) {
            return back()->with('error', 'Menu tidak dapat dihapus karena mempunyai riwayat pesanan. Nonaktifkan menu sebagai gantinya.');
        }

        DB::table('restaurant_menus')->where('id', $id)->delete();

        return back()->with('success', 'Menu restoran berhasil dihapus.');
    }

    private function validatedPayload(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category' => ['required', Rule::in(self::CATEGORIES)],
            'foto_url' => ['nullable', 'url', 'max:2048'],
        ]);

        $validated['foto_url'] = $validated['foto_url'] ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=900';
        $validated['is_available'] = $request->boolean('is_available');

        return $validated;
    }

    private function authorizeAdmin(Request $request): void
    {
        abort_unless($request->user()?->role === 'admin', 403);
    }
}
