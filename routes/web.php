<?php

use Illuminate\Support\Facades\Route;

// Rute Publik
Route::get('/', function () { return view('home'); })->name('home');
Route::get('/rooms', function () { return view('rooms'); })->name('rooms');
Route::get('/facilities', function () { return view('facilities'); })->name('facilities');
Route::get('/restaurant', function () { return view('restaurant'); })->name('restaurant');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// Pintu Masuk Dropdown Dashboard Dinamis (Sesuai panggilan di navbar)
// Pintu Masuk Dropdown Dashboard Dinamis dengan Pengaman Fallback
Route::get('/dashboard', function () {
    // Ambil role user, jika kosong atau null langsung setel otomatis sebagai 'guest'
    $role = auth()->user()->role ?: 'guest';

    // Periksa apakah nama rute role tersebut memang terdaftar di Laravel
    if (Route::has($role . '.dashboard')) {
        return redirect()->route($role . '.dashboard');
    }

    // Jika terjadi anomali role tidak terdaftar, amankan dengan melempar ke halaman Home
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// GRUP PROTEKSI AKSES INTERNAL BERDASARKAN ROLE
Route::middleware(['auth'])->group(function () {

    // BARIS PERBAIKAN: Rute Profile Global (Bisa diakses semua role setelah login)
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. GUEST / CUSTOMER DASHBOARD
    Route::middleware(['role:guest'])->prefix('guest')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.guest'); })->name('guest.dashboard');
    });

    // 2. ADMIN DASHBOARD
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.admin'); })->name('admin.dashboard');
    });

    // 3. MANAGER DASHBOARD
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.manager'); })->name('manager.dashboard');
    });

    // 4. RECEPTIONIST DASHBOARD
    Route::middleware(['role:receptionist'])->prefix('receptionist')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.receptionist'); })->name('receptionist.dashboard');
    });
});

require __DIR__.'/auth.php';