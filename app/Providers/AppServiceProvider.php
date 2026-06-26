<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\UserObserver;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
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
// 1. Registrasi Komponen Guest (Bawaan Kode Kamu)
        Blade::component('layouts.guest-dashboard', 'guest-dashboard-layout');

        // 2. REGISTRASI KOMPONEN ADMIN (Tambahkan Dua Baris Ini)
        Blade::component('layouts.admin', 'admin-layout');
        Blade::component('layouts.admin-dashboard', 'admin-dashboard-layout');
        Blade::component('layouts.manager-dashboard', 'manager-dashboard-layout');
           Blade::component('layouts.receptionist-dashboard', 'receptionist-dashboard-layout');
        
    }
}
