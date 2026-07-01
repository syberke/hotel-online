<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HotelOperationalController;

/*
|--------------------------------------------------------------------------
| 1. EXTERNAL INTEGRATION & WEBHOOKS (Bypass Auth Protection)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [HotelOperationalController::class, 'handleMidtransCallback']);


/*
|--------------------------------------------------------------------------
| 2. PUBLIC & GLOBAL PORTAL ROUTES (Accessible Without Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [HotelOperationalController::class, 'index'])->name('home');
Route::get('/rooms', [HotelOperationalController::class, 'allRoomsView'])->name('rooms');
Route::get('/rooms/{id}', [HotelOperationalController::class, 'roomShow'])->name('rooms.show');
Route::post('/rooms/check', [HotelOperationalController::class, 'checkAvailability'])->name('rooms.check');
Route::get('/restaurant', [HotelOperationalController::class, 'restaurantIndex'])->name('restaurant');
Route::get('/restaurant/menu/{id}', [HotelOperationalController::class, 'menuShow'])->name('restaurant.detail');
Route::get('/facilities', [HotelOperationalController::class, 'facilitiesIndex'])->name('facilities');
Route::get('/contact', function () { return view('page.contact'); })->name('contact');


/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION AUTOMATIC ROLE REDIRECTOR
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| 4. PROTECTED SYSTEM ZONE (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* --- GLOBAL ACCOUNT PROFILE MANAGEMENT --- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/lang/{locale}', [HotelOperationalController::class, 'changeLanguage'])->name('lang.switch');

    /* --- IN-HOUSE GUEST PORTAL & AJAX SERVICES --- */
    Route::prefix('guest')->name('guest.')->group(function () {
        Route::get('/dashboard', [HotelOperationalController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-bookings', [HotelOperationalController::class, 'myBookings'])->name('bookings.my');
        Route::get('/my-stay', [HotelOperationalController::class, 'myStay'])->name('stay.my');
        Route::get('/room-service', [HotelOperationalController::class, 'roomService'])->name('room.service');
        Route::get('/restaurant-orders', [HotelOperationalController::class, 'restaurantOrders'])->name('restaurant.orders');
        Route::get('/facilities-booking', [HotelOperationalController::class, 'facilitiesBooking'])->name('facilities.booking');
        Route::get('/facilities-portal', [HotelOperationalController::class, 'facilities'])->name('facilities.portal');
        Route::get('/billing-matrix', [HotelOperationalController::class, 'billingMatrix'])->name('billing.matrix');
    });

    // Guest Transactional Handlers
    Route::post('/bookings/{id}/cancel', [HotelOperationalController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::post('/my-bookings/get-snap-token', [HotelOperationalController::class, 'getSnapToken'])->name('bookings.pay');
    Route::post('/my-bookings/payment-success', [HotelOperationalController::class, 'localPaymentSuccess'])->name('bookings.payment.success');
    Route::get('/room-order/{id}/details', [HotelOperationalController::class, 'getRoomInvoiceDetails'])->name('room.invoice.details');
    
    Route::post('/restaurant/order', [HotelOperationalController::class, 'placeGastronomyOrder'])->name('restaurant.order');
    Route::post('/restaurant-order/pay', [HotelOperationalController::class, 'payRestaurantOrder'])->name('restaurant.order.pay');
    Route::post('/restaurant-order/settle', [HotelOperationalController::class, 'settleRestaurantOrder'])->name('restaurant.order.settle');
    Route::post('/restaurant-order/{id}/cancel', [HotelOperationalController::class, 'cancelRestaurantOrder'])->name('restaurant.order.cancel');
    Route::post('/restaurant-order/{id}/re-token', [HotelOperationalController::class, 'reTokenPendingOrder'])->name('restaurant.order.retoken');
    Route::get('/restaurant-order/{id}/details', [HotelOperationalController::class, 'getRestaurantOrderDetails'])->name('restaurant.order.details');
    
    Route::post('/room-service/order', [HotelOperationalController::class, 'storeRoomServiceOrder'])->name('room.service.order');
    Route::post('/facilities/book', [HotelOperationalController::class, 'bookFacility'])->name('facilities.book');

/* ======================================================================
       5. RECEPTIONIST / FRONT OFFICE DESK CONTROL PANEL
       ====================================================================== */
    Route::prefix('receptionist')->name('receptionist.')->group(function () {
        
        // Dashboard Utama Resepsionis
        Route::get('/dashboard', [HotelOperationalController::class, 'receptionistDashboardView'])->name('dashboard');
        
        // FIX: Rute AJAX Quick Checker Terintegrasi Nama Group
        Route::post('/quick-availability-check', [HotelOperationalController::class, 'receptionistQuickCheck'])->name('quick_check');
        
        // Walk-in Management
        Route::get('/walk-in', function() { return view('receptionist.walkin'); })->name('walkin');
        Route::post('/walk-in/store', [HotelOperationalController::class, 'storeWalkIn'])->name('walkin.store');

        // Check-in & Room Assignment Matrix
        Route::get('/check-in', [HotelOperationalController::class, 'receptionistCheckInView'])->name('checkin');
        Route::post('/check-in/process', [HotelOperationalController::class, 'processCheckIn'])->name('checkin.process');
     Route::match(['get', 'post'], '/room-assignment', [HotelOperationalController::class, 'assignRoomNumber'])->name('roomassignment');
        Route::post('/room-assignment/assign', [HotelOperationalController::class, 'assignRoomNumber'])->name('roomassignment.assign');

        // Check-out, Folio & Payments
    Route::match(['get', 'post'], '/check-out', [HotelOperationalController::class, 'processCheckOut'])->name('checkout');
        Route::post('/check-out/process', [HotelOperationalController::class, 'processCheckOut'])->name('checkout.process');
       Route::get('/folio', [HotelOperationalController::class, 'receptionistFolioView'])->name('folio');
        Route::post('/folio/charge/add', [HotelOperationalController::class, 'addFolioCharge'])->name('folio.charge.add');
       Route::match(['get', 'post'], '/payments', [HotelOperationalController::class, 'processPayment'])->name('payments');
        Route::post('/payments/process', [HotelOperationalController::class, 'processPayment'])->name('payments.process');

        // Operational Registry & Archives
        Route::get('/reservations', [HotelOperationalController::class, 'receptionistReservationsView'])->name('reservations');
        Route::get('/guests', [HotelOperationalController::class, 'receptionistGuestsView'])->name('guests');
      Route::get('/guest-history', [HotelOperationalController::class, 'receptionistGuestHistoryView'])->name('guesthistory');
       Route::get('/wakeup-call', [HotelOperationalController::class, 'wakeupCallView'])->name('wakeupcall');
        Route::post('/wakeup-call/schedule', [HotelOperationalController::class, 'scheduleWakeUp'])->name('wakeupcall.schedule');
        
        // Stock & House Status
        Route::get('/room-availability', [HotelOperationalController::class, 'roomAvailabilityView'])->name('roomavailability');
        Route::get('/house-status', [HotelOperationalController::class, 'houseStatusView'])->name('housestatus');
        Route::post('/house-status/update', [HotelOperationalController::class, 'updateHouseStatus'])->name('housestatus.update');
    });


    /* ======================================================================
       6. MANAGER & EXECUTIVE STRATEGIC WORKSPACE PORTAL (READ ONLY)
       ====================================================================== */
    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [HotelOperationalController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [HotelOperationalController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [HotelOperationalController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [HotelOperationalController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [HotelOperationalController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [HotelOperationalController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/facilities-wellness', [HotelOperationalController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/finance-billing', [HotelOperationalController::class, 'adminFinanceView'])->name('finance');
        Route::get('/reports', [HotelOperationalController::class, 'adminReportsView'])->name('reports');
        Route::get('/users-control', [HotelOperationalController::class, 'adminUserAndRoleView'])->name('userandrole');
    });


    /* ======================================================================
       7. BACK-OFFICE OPERATIONS HUB (ADMIN - FULL WRITE ACCESS)
       ====================================================================== */
    Route::prefix('admin')->name('admin.')->group(function () {
        // View Standard Dashboards
        Route::get('/dashboard', [HotelOperationalController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [HotelOperationalController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [HotelOperationalController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [HotelOperationalController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [HotelOperationalController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [HotelOperationalController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/restaurant/menu', [HotelOperationalController::class, 'adminTodaysMenuView'])->name('restaurant.menu');
        Route::get('/facilities-wellness', [HotelOperationalController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/users-control', [HotelOperationalController::class, 'adminUserAndRoleView'])->name('userandrole');
        
        // Finance & Analytics Engine
        Route::get('/finance-billing', [HotelOperationalController::class, 'adminFinanceView'])->name('finance');
        Route::post('/finance/transaction/{id}/update', [HotelOperationalController::class, 'adminUpdateTransactionStatus'])->name('finance.transaction.update');
        
        Route::get('/reports', [HotelOperationalController::class, 'adminReportsView'])->name('reports');
        Route::get('/reports/export/excel', [HotelOperationalController::class, 'exportReportsExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf', [HotelOperationalController::class, 'exportReportsPdf'])->name('reports.export.pdf');

        // CRUD Fasilitas
        Route::post('/facilities/store', [HotelOperationalController::class, 'adminStoreFacility'])->name('facilities.store');
        Route::post('/facilities/{id}/update', [HotelOperationalController::class, 'adminUpdateFacility'])->name('facilities.update');
        Route::delete('/facilities/{id}/delete', [HotelOperationalController::class, 'adminDeleteFacility'])->name('facilities.delete');
        Route::post('/facilities/booking/{id}/update-status', [HotelOperationalController::class, 'adminUpdateFacilityBookingStatus'])->name('facilities.booking.update-status');
        
        // CRUD User System
        Route::post('/users/store', [HotelOperationalController::class, 'adminStoreUser'])->name('users.store');
        Route::get('/users/{id}/json-detail', [HotelOperationalController::class, 'adminUserJsonDetail'])->name('users.json');
        Route::post('/users/{id}/update', [HotelOperationalController::class, 'adminUpdateUser'])->name('users.update');
        Route::delete('/users/{id}/delete', [HotelOperationalController::class, 'adminDeleteUser'])->name('users.delete');
    });


    /* ======================================================================
       8. HARD SECURITY POLICY: ANTI-MANAGER MODIFICATION GATEWAY
       ====================================================================== */
    Route::group(['middleware' => function ($request, $next) {
        if (auth()->check() && auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Akun Manager hanya diizinkan membaca data.');
        }
        return $next($request);
    }], function () {
        // Admin Write CRUD Kamar
        Route::post('/rooms/store', [HotelOperationalController::class, 'adminStoreRoom'])->name('rooms.store');
        Route::post('/rooms/{id}/update-status', [HotelOperationalController::class, 'adminUpdateRoomStatus'])->name('rooms.update-status');
        Route::delete('/rooms/{id}/delete', [HotelOperationalController::class, 'adminDeleteRoom'])->name('rooms.destroy');
        
        // Admin Write CRUD Reservasi
        Route::post('/reservations/{id}/update', [HotelOperationalController::class, 'adminUpdateReservation'])->name('admin.reservations.update');
        Route::delete('/reservations/{id}/delete', [HotelOperationalController::class, 'adminDeleteReservation'])->name('admin.reservations.delete');
        
        // Admin Write CRUD F&B Menu
        Route::post('/restaurant-order/{id}/update-status', [HotelOperationalController::class, 'adminUpdateOrderStatus'])->name('admin.restaurant.update-status');
        Route::post('/admin/restaurant/menu/store', [HotelOperationalController::class, 'adminStoreMenu'])->name('admin.restaurant.menu.store');
        Route::post('/admin/restaurant/menu/{id}/update', [HotelOperationalController::class, 'adminUpdateMenu'])->name('admin.restaurant.menu.update');
        Route::delete('/admin/restaurant/menu/{id}/delete', [HotelOperationalController::class, 'adminDeleteMenu'])->name('admin.restaurant.menu.delete');
    });

    /* ======================================================================
       9. SHARED READ-ONLY / AUDIT DATA FETCHERS (Admin & Manager Access Zone)
       ====================================================================== */
    Route::get('/admin/facilities/booking/{id}/detail', [HotelOperationalController::class, 'adminFacilityBookingDetail']);
    Route::get('/admin/finance/transaction/{id}/detail', [HotelOperationalController::class, 'adminTransactionDetail']);
    Route::get('/admin/reservations/{id}/json-detail', [HotelOperationalController::class, 'adminDetailReservation'])->name('admin.reservations.json');
    Route::get('/admin/restaurant-order/{id}/json-detail', [HotelOperationalController::class, 'adminRestaurantOrderDetailJson'])->name('admin.restaurant.order.json');
    Route::get('/admin/rooms/{id}/json-detail', [HotelOperationalController::class, 'adminRoomJsonDetail'])->name('admin.room.json');
});

require __DIR__.'/auth.php';