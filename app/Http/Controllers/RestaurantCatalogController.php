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
        $query = DB::table('restaurant_menus');

        if ($request->filled('category') && $request->string('category')->value() !== 'All Menu') {
            $category = strtolower($request->string('category')->value());
            $query->where(function ($builder) use ($category) {
                $builder->whereRaw('LOWER(description) LIKE ?', ['%' . $category . '%'])
                    ->orWhereRaw('LOWER(name) LIKE ?', ['%' . $category . '%']);
            });
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
            ->orderBy('name')
            ->paginate(8)
            ->withQueryString()
            ->fragment('menu-browsing-anchor');

        $venues = RestaurantVenue::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $totalMenuItems = DB::table('restaurant_menus')->count();

        return view('page.restaurant', compact('culinaryMenus', 'venues', 'totalMenuItems'));
    }
}
