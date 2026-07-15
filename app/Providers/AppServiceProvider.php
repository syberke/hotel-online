<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);

        Blade::component('layouts.guest-dashboard', 'guest-dashboard-layout');
        Blade::component('layouts.admin', 'admin-layout');
        Blade::component('layouts.admin-dashboard', 'admin-dashboard-layout');
        Blade::component('layouts.manager-dashboard', 'manager-dashboard-layout');
        Blade::component('layouts.receptionist-dashboard', 'receptionist-dashboard-layout');

        if (getenv('RENDER_EXTERNAL_HOSTNAME')) {
            URL::forceScheme('https');
        }
    }
}
