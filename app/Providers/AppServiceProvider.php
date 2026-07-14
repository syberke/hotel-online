<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);

        Blade::component('layouts.guest-dashboard', 'guest-dashboard-layout');
        Blade::component('layouts.admin', 'admin-layout');
        Blade::component('layouts.admin-dashboard', 'admin-dashboard-layout');
        Blade::component('layouts.manager-dashboard', 'manager-dashboard-layout');
        Blade::component('layouts.receptionist-dashboard', 'receptionist-dashboard-layout');

        View::composer('layouts.admin-dashboard', function ($view) {
            if (request()->routeIs('admin.finance')) {
                $expenseBreakdown = DB::table('expenses')
                    ->select('category', DB::raw('SUM(amount) as amount'))
                    ->groupBy('category')
                    ->orderByDesc('amount')
                    ->get();

                $totalExpenses = (float) $expenseBreakdown->sum('amount');
                $expenseBreakdown = $expenseBreakdown->map(function ($row) use ($totalExpenses) {
                    $row->pct = $totalExpenses > 0
                        ? round(((float) $row->amount / $totalExpenses) * 100, 1)
                        : 0;
                    return $row;
                });

                $recentExpenses = DB::table('expenses')
                    ->leftJoin('users', 'expenses.created_by', '=', 'users.id')
                    ->select('expenses.*', 'users.name as creator_name')
                    ->orderByDesc('expenses.expense_date')
                    ->orderByDesc('expenses.id')
                    ->limit(10)
                    ->get();

                $view->with(compact('expenseBreakdown', 'recentExpenses'));
            }

            if (request()->routeIs('admin.facilities')) {
                $facilityPricingRows = DB::table('facilities')
                    ->orderBy('name')
                    ->get();
                $view->with('facilityPricingRows', $facilityPricingRows);
            }
        });

        View::composer('page.contact', function ($view) {
            $view->with('hotelContact', config('hotel.contact'));
        });
    }
}
