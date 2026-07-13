<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\GuestStayController;
use App\Http\Controllers\GuestServiceController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\RestaurantOrderController;
use App\Http\Controllers\ReceptionistDeskController;
use App\Http\Controllers\FrontOfficeCheckController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\AdminOperationController;
use App\Http\Controllers\ExecutiveReportController;

/*
|--------------------------------------------------------------------------
| 1. EXTERNAL INTEGRATION & WEBHOOKS (Bypass Auth Protection)
|--------------------------------------------------------------------------
*/
Route::post('/midtrans/callback', [PaymentGatewayController::class, 'handleMidtransCallback']);


/*
|--------------------------------------------------------------------------
| 2. PUBLIC & GLOBAL PORTAL ROUTES (Accessible Without Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicPortalController::class, 'index'])->name('home');
Route::get('/rooms', [PublicPortalController::class, 'allRoomsView'])->name('rooms');
Route::get('/rooms/{id}', [PublicPortalController::class, 'roomShow'])->name('rooms.show');
Route::post('/rooms/check', [PublicPortalController::class, 'checkAvailability'])->name('rooms.check');
Route::get('/restaurant', [PublicPortalController::class, 'restaurantIndex'])->name('restaurant');
Route::get('/restaurant/menu/{id}', [PublicPortalController::class, 'menuShow'])->name('restaurant.detail');
Route::get('/facilities', [PublicPortalController::class, 'facilitiesIndex'])->name('facilities');
Route::get('/contact', function () { return view('page.contact'); })->name('contact');


/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION AUTOMATIC ROLE REDIRECTOR (Amankan dengan verified)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| 4. PROTECTED SYSTEM ZONE (Requires Authentication & Email Verified)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /* --- GLOBAL ACCOUNT PROFILE MANAGEMENT --- */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/lang/{locale}', [PublicPortalController::class, 'changeLanguage'])->name('lang.switch');

    /* --- IN-HOUSE GUEST PORTAL & AJAX SERVICES --- */
    Route::prefix('guest')->name('guest.')->group(function () {
        Route::get('/dashboard', [GuestStayController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-bookings', [GuestStayController::class, 'myBookings'])->name('bookings.my');
        Route::get('/my-stay', [GuestStayController::class, 'myStay'])->name('stay.my');
        Route::get('/room-service', [GuestServiceController::class, 'roomService'])->name('room.service');
        Route::get('/restaurant-orders', [GuestServiceController::class, 'restaurantOrders'])->name('restaurant.orders');
        Route::get('/facilities-booking', [GuestServiceController::class, 'facilitiesBooking'])->name('facilities.booking');
        Route::get('/facilities-portal', [PublicPortalController::class, 'facilitiesIndex'])->name('facilities.portal');
        Route::get('/billing-matrix', function () { return view('guest.billingmatrix'); })->name('billing.matrix');
        Route::get('/restaurant-order/{id}/details', [RestaurantOrderController::class, 'details'])->name('restaurant.order.details');
    });

    // Guest Transactional Handlers
    Route::post('/bookings/{id}/cancel', [GuestStayController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::post('/my-bookings/get-snap-token', [PaymentGatewayController::class, 'getSnapToken'])->name('bookings.pay');
    Route::post('/my-bookings/payment-success', [PaymentGatewayController::class, 'localPaymentSuccess'])->name('bookings.payment.success');
    Route::get('/room-order/{id}/details', [PaymentGatewayController::class, 'getRoomInvoiceDetails'])->name('room.invoice.details');
    
    Route::post('/restaurant/order', [GuestServiceController::class, 'placeGastronomyOrder'])->name('restaurant.order');
    Route::post('/restaurant-order/pay', [PaymentGatewayController::class, 'payRestaurantOrder'])->name('restaurant.order.pay');
    Route::post('/restaurant-order/settle', [PaymentGatewayController::class, 'settleRestaurantOrder'])->name('restaurant.order.settle');
    Route::post('/restaurant-order/{id}/cancel', [PaymentGatewayController::class, 'cancelRestaurantOrder'])->name('restaurant.order.cancel');
    Route::post('/restaurant-order/{id}/re-token', [PaymentGatewayController::class, 'reTokenPendingOrder'])->name('restaurant.order.retoken');
    Route::get('/restaurant-order/{id}/details', [RestaurantOrderController::class, 'details'])->name('restaurant.order.details');
    
    Route::post('/room-service/order', [GuestServiceController::class, 'storeRoomServiceOrder'])->name('room.service.order');
    Route::post('/facilities/book', [GuestServiceController::class, 'bookFacility'])->name('facilities.book');

    /* ======================================================================
       5. RECEPTIONIST / FRONT OFFICE DESK CONTROL PANEL
       ====================================================================== */
    Route::prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/dashboard', [ReceptionistDeskController::class, 'receptionistDashboardView'])->name('dashboard');
        Route::post('/quick-availability-check', [ReceptionistDeskController::class, 'receptionistQuickCheck'])->name('quick_check'); 
        Route::get('/walk-in', function() { return view('receptionist.walkin'); })->name('walkin');
        Route::post('/walk-in/store', [FrontOfficeCheckController::class, 'storeWalkIn'])->name('walkin.store');
        
        Route::get('/check-in', [FrontOfficeCheckController::class, 'receptionistCheckInView'])->name('checkin');
        Route::post('/check-in/process', [FrontOfficeCheckController::class, 'processCheckIn'])->name('checkin.process');
        Route::match(['get', 'post'], '/room-assignment', [FrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment');
        Route::post('/room-assignment/assign', [FrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment.assign');
        
        Route::match(['get', 'post'], '/check-out', [FrontOfficeCheckController::class, 'processCheckOut'])->name('checkout');
        Route::post('/check-out/process', [FrontOfficeCheckController::class, 'processCheckOut'])->name('checkout.process');
        Route::get('/folio', [FrontOfficeCheckController::class, 'receptionistFolioView'])->name('folio');
        Route::match(['get', 'post'], '/payments', [FrontOfficeCheckController::class, 'processPayment'])->name('payments');
        Route::post('/payments/process', [FrontOfficeCheckController::class, 'processPayment'])->name('payments.process');
        
        Route::get('/reservations', [ReceptionistDeskController::class, 'receptionistReservationsView'])->name('reservations');
        Route::get('/guests', [ReceptionistDeskController::class, 'receptionistGuestsView'])->name('guests');
        Route::get('/guest-history', [ReceptionistDeskController::class, 'receptionistGuestHistoryView'])->name('guesthistory');
        
        Route::get('/room-availability', [HousekeepingController::class, 'roomAvailabilityView'])->name('roomavailability');
        Route::get('/house-status', [HousekeepingController::class, 'houseStatusView'])->name('housestatus');
        Route::post('/house-status/update', [HousekeepingController::class, 'updateHouseStatus'])->name('housestatus.update');
    });

    /* ======================================================================
       6. MANAGER & EXECUTIVE STRATEGIC WORKSPACE PORTAL (READ ONLY)
       ====================================================================== */
    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [ExecutiveReportController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [ExecutiveReportController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [ExecutiveReportController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [ExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [ExecutiveReportController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/facilities-wellness', [AdminOperationController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/finance-billing', [ExecutiveReportController::class, 'adminFinanceView'])->name('finance');
        Route::get('/reports', [ExecutiveReportController::class, 'adminReportsView'])->name('reports');
        Route::get('/users-control', [AdminOperationController::class, 'adminUserAndRoleView'])->name('userandrole');
    });

    /* ======================================================================
       7. BACK-OFFICE OPERATIONS HUB (ADMIN - FULL WRITE ACCESS)
       ====================================================================== */
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [ExecutiveReportController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [ExecutiveReportController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [ExecutiveReportController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [ExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [ExecutiveReportController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/restaurant/menu', [AdminOperationController::class, 'adminTodaysMenuView'])->name('restaurant.menu');
        Route::get('/facilities-wellness', [ExecutiveReportController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/users-control', [AdminOperationController::class, 'adminUserAndRoleView'])->name('userandrole');
        
        Route::get('/finance-billing', [ExecutiveReportController::class, 'adminFinanceView'])->name('finance');
        Route::post('/finance/transaction/{id}/update', [AdminOperationController::class, 'adminUpdateTransactionStatus'])->name('finance.transaction.update');
        
        Route::get('/reports', [ExecutiveReportController::class, 'adminReportsView'])->name('reports');
        Route::get('/reports/export/excel', [ExecutiveReportController::class, 'exportReportsExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf', [ExecutiveReportController::class, 'exportReportsPdf'])->name('reports.export.pdf');

        Route::post('/facilities/store', [AdminOperationController::class, 'adminStoreFacility'])->name('facilities.store');
        Route::post('/facilities/{id}/update', [AdminOperationController::class, 'adminUpdateFacility'])->name('facilities.update');
        Route::delete('/facilities/{id}/delete', [AdminOperationController::class, 'adminDeleteFacility'])->name('facilities.delete');
        Route::post('/facilities/booking/{id}/update-status', [AdminOperationController::class, 'adminUpdateFacilityBookingStatus'])->name('facilities.booking.update-status');
        
        Route::post('/users/store', [AdminOperationController::class, 'adminStoreUser'])->name('users.store');
        Route::get('/users/{id}/json-detail', [AdminOperationController::class, 'adminUserJsonDetail'])->name('users.json');
        Route::post('/users/{id}/update', [AdminOperationController::class, 'adminUpdateUser'])->name('users.update');
        Route::delete('/users/{id}/delete', [AdminOperationController::class, 'adminDeleteUser'])->name('users.delete');
    });

    /* ======================================================================
       8. HARD SECURITY POLICY: ANTI-MANAGER MODIFICATION GATEWAY
       ====================================================================== */
    Route::middleware('deny-manager-modification')->group(function () {
        Route::post('/rooms/store', [AdminOperationController::class, 'adminStoreRoom'])->name('rooms.store');
        Route::post('/rooms/{id}/update-status', [AdminOperationController::class, 'adminUpdateRoomStatus'])->name('rooms.update-status');
        Route::delete('/rooms/{id}/delete', [AdminOperationController::class, 'adminDeleteRoom'])->name('rooms.destroy');
        
        Route::post('/admin/room-types/store', [AdminOperationController::class, 'storeRoomType'])->name('admin.room-types.store');
        Route::post('/admin/room-types/{id}/update', [AdminOperationController::class, 'updateRoomType'])->name('admin.room-types.update');
        Route::delete('/admin/room-types/{id}/delete', [AdminOperationController::class, 'deleteRoomType'])->name('admin.room-types.delete');
        
        Route::post('/reservations/{id}/update', [AdminOperationController::class, 'adminUpdateReservation'])->name('admin.reservations.update');
        Route::delete('/reservations/{id}/delete', [AdminOperationController::class, 'adminDeleteReservation'])->name('admin.reservations.delete');
        
        Route::post('/restaurant-order/{id}/update-status', [ExecutiveReportController::class, 'adminUpdateOrderStatus'])->name('admin.restaurant.update-status');
        Route::post('/admin/restaurant/menu/store', [AdminOperationController::class, 'adminStoreMenu'])->name('admin.restaurant.menu.store');
        Route::post('/admin/restaurant/menu/{id}/update', [AdminOperationController::class, 'adminUpdateMenu'])->name('admin.restaurant.menu.update');
        Route::delete('/admin/restaurant/menu/{id}/delete', [AdminOperationController::class, 'adminDeleteMenu'])->name('admin.restaurant.menu.delete');
    });

    /* ======================================================================
       9. SHARED READ-ONLY / AUDIT DATA FETCHERS
       ====================================================================== */
    Route::get('/admin/facilities/booking/{id}/detail', [AdminOperationController::class, 'adminFacilityBookingDetail']);
    Route::get('/admin/finance/transaction/{id}/detail', [AdminOperationController::class, 'adminTransactionDetail']);
    Route::get('/admin/reservations/{id}/json-detail', [AdminOperationController::class, 'adminDetailReservation'])->name('admin.reservations.json');
    Route::get('/admin/restaurant-order/{id}/json-detail', [ExecutiveReportController::class, 'adminRestaurantOrderDetailJson'])->name('admin.restaurant.order.json');
    Route::get('/admin/rooms/{id}/json-detail', [AdminOperationController::class, 'adminRoomJsonDetail'])->name('admin.room.json');
});

// Load file autentikasi bawaan
require __DIR__.'/auth.php';