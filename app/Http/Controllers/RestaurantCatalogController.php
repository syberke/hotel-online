<?php

namespace App\Http\Controllers;

use App\Models\RestaurantVenue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RestaurantCatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = DB::table('restaurant_menus')->where('is_available', true);

        if ($request->filled('category') && $request->string('category')->value() !== 'All Menu') {
            $query->where('category', $request->string('category')->value());
        }

        if ($request->filled('search')) {
            $search = strtolower($request->string('search')->value());
            $query->where(function ($builder) use ($search) {
                $builder->whereRaw('LOWER(name) LIKE ?', ['%' . $search . '%'])
                    ->orWhereRaw('LOWER(description) LIKE ?', ['%' . $search . '%']);
            });
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        $culinaryMenus = $query
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString()
            ->fragment('menu-browsing-anchor');

        $venues = RestaurantVenue::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $menuCategories = DB::table('restaurant_menus')
            ->where('is_available', true)
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $totalMenuItems = DB::table('restaurant_menus')->where('is_available', true)->count();

        return view('page.restaurant', compact('culinaryMenus', 'venues', 'menuCategories', 'totalMenuItems'));
    }
}
