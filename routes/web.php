<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\GuestStayController;
use App\Http\Controllers\GuestServiceController;
use App\Http\Controllers\GuestFacilityController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\RestaurantOrderController;
use App\Http\Controllers\ReceptionistDeskController;
use App\Http\Controllers\DynamicFrontOfficeCheckController;
use App\Http\Controllers\HousekeepingController;
use App\Http\Controllers\DynamicAdminOperationController;
use App\Http\Controllers\DynamicExecutiveReportController;
use App\Http\Controllers\ExpenseController;

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
Route::get('/contact', [PublicPortalController::class, 'contact'])->name('contact');

/*
|--------------------------------------------------------------------------
| 3. AUTHENTICATION AUTOMATIC ROLE REDIRECTOR
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| 4. PROTECTED SYSTEM ZONE
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/lang/{locale}', [PublicPortalController::class, 'changeLanguage'])->name('lang.switch');

    Route::prefix('guest')->name('guest.')->group(function () {
        Route::get('/dashboard', [GuestStayController::class, 'dashboard'])->name('dashboard');
        Route::get('/my-bookings', [GuestStayController::class, 'myBookings'])->name('bookings.my');
        Route::get('/my-stay', [GuestStayController::class, 'myStay'])->name('stay.my');
        Route::get('/room-service', [GuestServiceController::class, 'roomService'])->name('room.service');
        Route::get('/restaurant-orders', [GuestServiceController::class, 'restaurantOrders'])->name('restaurant.orders');
        Route::get('/facilities-booking', [GuestFacilityController::class, 'index'])->name('facilities.booking');
        Route::get('/facilities-portal', [PublicPortalController::class, 'facilitiesIndex'])->name('facilities.portal');
        Route::get('/billing-matrix', function () { return view('guest.billingmatrix'); })->name('billing.matrix');
        Route::get('/restaurant-order/{id}/details', [RestaurantOrderController::class, 'details'])->name('restaurant.order.details');
    });

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
    Route::post('/facilities/book', [GuestFacilityController::class, 'store'])->name('facilities.book');

    Route::prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/dashboard', [ReceptionistDeskController::class, 'receptionistDashboardView'])->name('dashboard');
        Route::post('/quick-availability-check', [ReceptionistDeskController::class, 'receptionistQuickCheck'])->name('quick_check');
        Route::get('/walk-in', function () { return view('receptionist.walkin'); })->name('walkin');
        Route::post('/walk-in/store', [DynamicFrontOfficeCheckController::class, 'storeWalkIn'])->name('walkin.store');
        Route::get('/check-in', [DynamicFrontOfficeCheckController::class, 'receptionistCheckInView'])->name('checkin');
        Route::post('/check-in/process', [DynamicFrontOfficeCheckController::class, 'processCheckIn'])->name('checkin.process');
        Route::match(['get', 'post'], '/room-assignment', [DynamicFrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment');
        Route::post('/room-assignment/assign', [DynamicFrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment.assign');
        Route::match(['get', 'post'], '/check-out', [DynamicFrontOfficeCheckController::class, 'processCheckOut'])->name('checkout');
        Route::post('/check-out/process', [DynamicFrontOfficeCheckController::class, 'processCheckOut'])->name('checkout.process');
        Route::get('/folio', [DynamicFrontOfficeCheckController::class, 'receptionistFolioView'])->name('folio');
        Route::match(['get', 'post'], '/payments', [DynamicFrontOfficeCheckController::class, 'processPayment'])->name('payments');
        Route::post('/payments/process', [DynamicFrontOfficeCheckController::class, 'processPayment'])->name('payments.process');
        Route::get('/reservations', [ReceptionistDeskController::class, 'receptionistReservationsView'])->name('reservations');
        Route::get('/guests', [ReceptionistDeskController::class, 'receptionistGuestsView'])->name('guests');
        Route::get('/guest-history', [ReceptionistDeskController::class, 'receptionistGuestHistoryView'])->name('guesthistory');
        Route::get('/room-availability', [HousekeepingController::class, 'roomAvailabilityView'])->name('roomavailability');
        Route::get('/house-status', [HousekeepingController::class, 'houseStatusView'])->name('housestatus');
        Route::post('/house-status/update', [HousekeepingController::class, 'updateHouseStatus'])->name('housestatus.update');
    });

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [DynamicExecutiveReportController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [DynamicExecutiveReportController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [DynamicExecutiveReportController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [DynamicAdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [DynamicExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [DynamicExecutiveReportController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/facilities-wellness', [DynamicExecutiveReportController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/finance-billing', [DynamicExecutiveReportController::class, 'adminFinanceView'])->name('finance');
        Route::get('/reports', [DynamicExecutiveReportController::class, 'adminReportsView'])->name('reports');
        Route::get('/users-control', [DynamicAdminOperationController::class, 'adminUserAndRoleView'])->name('userandrole');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DynamicExecutiveReportController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [DynamicExecutiveReportController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [DynamicExecutiveReportController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [DynamicAdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [DynamicExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [DynamicExecutiveReportController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/restaurant/menu', [DynamicAdminOperationController::class, 'adminTodaysMenuView'])->name('restaurant.menu');
        Route::get('/facilities-wellness', [DynamicExecutiveReportController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/users-control', [DynamicAdminOperationController::class, 'adminUserAndRoleView'])->name('userandrole');

        Route::get('/finance-billing', [DynamicExecutiveReportController::class, 'adminFinanceView'])->name('finance');
        Route::post('/finance/transaction/{id}/update', [DynamicAdminOperationController::class, 'adminUpdateTransactionStatus'])->name('finance.transaction.update');
        Route::post('/finance/expenses/store', [ExpenseController::class, 'store'])->name('finance.expenses.store');
        Route::delete('/finance/expenses/{id}', [ExpenseController::class, 'destroy'])->name('finance.expenses.delete');

        Route::get('/reports', [DynamicExecutiveReportController::class, 'adminReportsView'])->name('reports');
        Route::get('/reports/export/excel', [DynamicExecutiveReportController::class, 'exportReportsExcel'])->name('reports.export.excel');
        Route::get('/reports/export/pdf', [DynamicExecutiveReportController::class, 'exportReportsPdf'])->name('reports.export.pdf');

        Route::post('/facilities/store', [DynamicAdminOperationController::class, 'adminStoreFacility'])->name('facilities.store');
        Route::post('/facilities/{id}/update', [DynamicAdminOperationController::class, 'adminUpdateFacility'])->name('facilities.update');
        Route::delete('/facilities/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteFacility'])->name('facilities.delete');
        Route::post('/facilities/booking/{id}/update-status', [DynamicAdminOperationController::class, 'adminUpdateFacilityBookingStatus'])->name('facilities.booking.update-status');

        Route::post('/users/store', [DynamicAdminOperationController::class, 'adminStoreUser'])->name('users.store');
        Route::get('/users/{id}/json-detail', [DynamicAdminOperationController::class, 'adminUserJsonDetail'])->name('users.json');
        Route::post('/users/{id}/update', [DynamicAdminOperationController::class, 'adminUpdateUser'])->name('users.update');
        Route::delete('/users/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteUser'])->name('users.delete');
    });

    Route::middleware('deny-manager-modification')->group(function () {
        Route::post('/rooms/store', [DynamicAdminOperationController::class, 'adminStoreRoom'])->name('rooms.store');
        Route::post('/rooms/{id}/update-status', [DynamicAdminOperationController::class, 'adminUpdateRoomStatus'])->name('rooms.update-status');
        Route::delete('/rooms/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteRoom'])->name('rooms.destroy');
        Route::post('/admin/room-types/store', [DynamicAdminOperationController::class, 'storeRoomType'])->name('admin.room-types.store');
        Route::post('/admin/room-types/{id}/update', [DynamicAdminOperationController::class, 'updateRoomType'])->name('admin.room-types.update');
        Route::delete('/admin/room-types/{id}/delete', [DynamicAdminOperationController::class, 'deleteRoomType'])->name('admin.room-types.delete');
        Route::post('/admin/reservations/{id}/update', [DynamicAdminOperationController::class, 'adminUpdateReservation'])->name('admin.reservations.update');
        Route::delete('/admin/reservations/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteReservation'])->name('admin.reservations.delete');
        Route::post('/admin/restaurant-order/{id}/update-status', [DynamicExecutiveReportController::class, 'adminUpdateOrderStatus'])->name('admin.restaurant.update-status');
        Route::delete('/admin/restaurant-order/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteRestaurantOrder'])->name('admin.restaurant.order.delete');
        Route::delete('/admin/facilities/booking/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteFacilityBooking'])->name('admin.facilities.booking.delete');
        Route::post('/admin/restaurant/menu/store', [DynamicAdminOperationController::class, 'adminStoreMenu'])->name('admin.restaurant.menu.store');
        Route::post('/admin/restaurant/menu/{id}/update', [DynamicAdminOperationController::class, 'adminUpdateMenu'])->name('admin.restaurant.menu.update');
        Route::delete('/admin/restaurant/menu/{id}/delete', [DynamicAdminOperationController::class, 'adminDeleteMenu'])->name('admin.restaurant.menu.delete');
    });

    Route::get('/admin/facilities/booking/{id}/detail', [DynamicAdminOperationController::class, 'adminFacilityBookingDetail']);
    Route::get('/admin/finance/transaction/{id}/detail', [DynamicAdminOperationController::class, 'adminTransactionDetail']);
    Route::get('/admin/reservations/{id}/json-detail', [DynamicAdminOperationController::class, 'adminDetailReservation'])->name('admin.reservations.json');
    Route::get('/admin/restaurant-order/{id}/json-detail', [DynamicExecutiveReportController::class, 'adminRestaurantOrderDetailJson'])->name('admin.restaurant.order.json');
    Route::get('/admin/rooms/{id}/json-detail', [DynamicAdminOperationController::class, 'adminRoomJsonDetail'])->name('admin.room.json');
});

require __DIR__.'/auth.php';
