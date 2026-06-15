<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelOperationalController;
use App\Http\Controllers\RestaurantOrderController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomTypeController;

// ROUTE REVISION: Pasang index beranda dinamis ke controller operasional
Route::get('/', [HotelOperationalController::class, 'index'])->name('home');

// Form validasi penjelajah booking utama
Route::post('/rooms/check', [HotelOperationalController::class, 'checkAvailability'])->name('rooms.check');

// Jalur modul katalog dan detail kamar terintegrasi
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Jalur Sub-Halaman Detail Kamar Spesifik Berdasarkan ID
Route::get('/rooms/{id}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/facilities', function () { return view('facilities'); })->name('facilities');
Route::get('/restaurant', function () { return view('restaurant'); })->name('restaurant');
Route::get('/contact', function () { return view('contact'); })->name('contact');

// 2. Gerbang Otentikasi Pintu Masuk Dashboard Dinamis
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth'])->name('dashboard');

// 3. Grup Proteksi Otentikasi User Internal
Route::middleware(['auth'])->group(function () {

    // Manajemen Akun Profile Global Oasis
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Aksi Pesanan Restoran Langsung
    Route::post('/restaurant/order', [RestaurantOrderController::class, 'storeOrder'])->name('restaurant.order');

    // GUEST DASHBOARD SYSTEM
    Route::middleware(['role:guest'])->prefix('guest')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.guest'); })->name('guest.dashboard');
    });

    // ADMIN EXECUTIVE MANAGEMENT SYSTEM
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    });

    // MANAGER BUSINESS INTELLIGENCE SYSTEM
    Route::middleware(['role:manager'])->prefix('manager')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.manager'); })->name('manager.dashboard');
    });

    // RECEPTIONIST TERMINAL FRONT DESK
    Route::middleware(['role:receptionist'])->prefix('receptionist')->group(function () {
        Route::get('/dashboard', function () { return view('dashboard.receptionist'); })->name('receptionist.dashboard');
    });

    Route::get('/dashboard', function () {
        return view('bookings.index');
    })->name('dashboard');
    // Cari baris rute /guest/dashboard lama kamu, lalu ganti menjadi seperti ini:
Route::get('/guest/dashboard', [HotelOperationalController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');
    Route::get('/my-bookings', [HotelOperationalController::class, 'myBookings'])->name('bookings.my');
    Route::get('/my-stay', [HotelOperationalController::class, 'myStay'])->name('stay.my');
    Route::get('/room-service', [HotelOperationalController::class, 'roomService'])->name('room.service');
    Route::get('/restaurant-orders', [HotelOperationalController::class, 'restaurantOrders'])->name('restaurant.orders');
    Route::get('/facilities-booking', [HotelOperationalController::class, 'facilitiesBooking'])->name('facilities.booking');
    Route::get('/billing-matrix', [HotelOperationalController::class, 'billingMatrix'])->name('billing.matrix');
    // Membuka halaman visualisasi fasilitas
    Route::get('/facilities', [HotelOperationalController::class, 'facilitiesIndex'])->name('facilities');
    
    // Endpoint AJAX untuk menyimpan pesanan slot waktu fasilitas
    Route::post('/facilities/book', [HotelOperationalController::class, 'bookFacility'])->name('facilities.book');
});

require __DIR__.'/auth.php';