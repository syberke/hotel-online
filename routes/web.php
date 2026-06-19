<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelOperationalController;
// BENAR
use App\Http\Controllers\RoomServiceController;
// 1. Taruh di BARIS PUBLIK (DI LUAR MIDDLEWARE AUTH) - Wajib tanpa proteksi auth demi webhook Midtrans
Route::post('/midtrans/callback', [HotelOperationalController::class, 'handleMidtransCallback']);

// 1. RUTE PUBLIK (Dapat Diases Tanpa Login)
Route::get('/', [HotelOperationalController::class, 'index'])->name('home');
Route::post('/rooms/check', [HotelOperationalController::class, 'checkAvailability'])->name('rooms.check');

// Katalog Halaman Penuh Sesuai Request Anda
Route::get('/rooms', [HotelOperationalController::class, 'allRoomsView'])->name('rooms');
Route::get('/rooms/{id}', [HotelOperationalController::class, 'roomShow'])->name('rooms.show');
Route::get('/restaurant', [HotelOperationalController::class, 'restaurantIndex'])->name('restaurant');

Route::get('/contact', function () { return view('page.contact'); })->name('contact');
Route::get('/facilities', [HotelOperationalController::class, 'facilitiesIndex'])->name('facilities');
Route::get('/restaurant/menu/{id}', [HotelOperationalController::class, 'menuShow'])->name('restaurant.detail');
// 2. GERBANG PENGALIHAN UTAMA DASHBOARD AUTENTIKASI
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth'])->name('dashboard');

// 3. GRUP PROTEKSI OTENTIKASI TAMU & STAF INTERNAL
Route::middleware(['auth'])->group(function () {

    // Profil Akun Global
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Endpoint AJAX Menyimpan Nota Kuliner Tamu
    Route::post('/restaurant/order', [HotelOperationalController::class, 'placeGastronomyOrder'])->name('restaurant.order');
    Route::post('/facilities/book', [HotelOperationalController::class, 'bookFacility'])->name('facilities.book');

    // PROFIL GUEST DASHBOARD SYSTEM (Dialihkan ke Operasional Kontroler Agar Data Cuaca & Kamar Muat)
    Route::get('/guest/dashboard', [HotelOperationalController::class, 'dashboard'])->name('guest.dashboard');

// --- SUB-MENU INTERNAL DASHBOARD PORTAL (VERSI SERASI) ---
    Route::get('/my-bookings', [HotelOperationalController::class, 'myBookings'])->name('bookings.my');
    Route::get('/my-stay', [HotelOperationalController::class, 'myStay'])->name('stay.my');
    Route::get('/room-service', [HotelOperationalController::class, 'roomService'])->name('room.service');
    Route::get('/restaurant-orders', [HotelOperationalController::class, 'restaurantOrders'])->name('restaurant.orders');
    Route::get('/facilities-booking', [HotelOperationalController::class, 'facilitiesBooking'])->name('facilities.booking');
    Route::get('/facilities-portal', [HotelOperationalController::class, 'facilities'])->name('facilities.portal');
    Route::get('/billing-matrix', [HotelOperationalController::class, 'billingMatrix'])->name('billing.matrix');
    Route::post('/room-service/order', [HotelOperationalController::class, 'storeRoomServiceOrder'])->name('room.service.order');
    Route::post('/bookings/{id}/cancel', [App\Http\Controllers\HotelOperationalController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::get('/admin/dashboard', function() { return view('dashboard.admin'); })->name('admin.dashboard');
    Route::get('/manager/dashboard', function() { return view('dashboard.manager'); })->name('manager.dashboard');
    Route::get('/receptionist/dashboard', function() { return view('dashboard.receptionist'); })->name('receptionist.dashboard');
    Route::post('/restaurant-order/pay', [HotelOperationalController::class, 'payRestaurantOrder'])->name('restaurant.order.pay');
    Route::post('/restaurant-order/settle', [HotelOperationalController::class, 'settleRestaurantOrder'])->name('restaurant.order.settle');
    Route::post('/restaurant-order/{id}/cancel', [HotelOperationalController::class, 'cancelRestaurantOrder'])->name('restaurant.order.cancel');
    Route::post('/restaurant-order/{id}/re-token', [HotelOperationalController::class, 'reTokenPendingOrder'])->name('restaurant.order.retoken');
    Route::post('/facilities/book', [HotelOperationalController::class, 'bookFacility'])->name('facilities.book');
    Route::get('/room-order/{id}/details', [App\Http\Controllers\HotelOperationalController::class, 'getRoomInvoiceDetails'])->name('room.invoice.details');
    Route::get('/lang/{locale}', [HotelOperationalController::class, 'changeLanguage'])->name('lang.switch');
    Route::get('/restaurant/menu/{id}', [HotelOperationalController::class, 'menuShow'])->name('restaurant.detail');
// Rute untuk update database instan via frontend setelah pop-up Midtrans sukses
Route::post('/my-bookings/payment-success', [HotelOperationalController::class, 'localPaymentSuccess'])->name('bookings.payment.success');
Route::get('/restaurant-order/{id}/details', [HotelOperationalController::class, 'getRestaurantOrderDetails'])->name('restaurant.order.details');
    // RUTE PEMBAYARAN MIDTRANS (Dapat Diakses Setelah Validasi Pemesanan Restoran atau Fasilitas)
    Route::post('/my-bookings/get-snap-token', [HotelOperationalController::class, 'getSnapToken'])->name('bookings.pay');
});

require __DIR__.'/auth.php';