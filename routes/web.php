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
use App\Http\Controllers\ReceptionistReservationController;
use App\Http\Controllers\ReceptionistDashboardController;
use App\Http\Controllers\FrontOfficeCheckController;
use App\Http\Controllers\FrontOfficeFlowController;
use App\Http\Controllers\RoomLifecycleController;
use App\Http\Controllers\CoreHousekeepingController;
use App\Http\Controllers\AdminOperationController;
use App\Http\Controllers\AdminControlController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\ExecutiveReportController;
use App\Http\Controllers\OperationalViewController;
use App\Http\Controllers\CoreDashboardController;
use App\Http\Controllers\CoreFacilityViewController;
use App\Http\Controllers\LiveReportViewController;
use App\Http\Controllers\ManagerReportController;

Route::post('/midtrans/callback', [PaymentGatewayController::class, 'handleMidtransCallback']);

Route::get('/', [PublicPortalController::class, 'index'])->name('home');
Route::get('/rooms', [PublicPortalController::class, 'allRoomsView'])->name('rooms');
Route::get('/rooms/{id}', [PublicPortalController::class, 'roomShow'])->name('rooms.show');
Route::post('/rooms/check', [PublicPortalController::class, 'checkAvailability'])->name('rooms.check');
Route::get('/restaurant', [PublicPortalController::class, 'restaurantIndex'])->name('restaurant');
Route::get('/restaurant/menu/{id}', [PublicPortalController::class, 'menuShow'])->name('restaurant.detail');
Route::get('/facilities', [PublicPortalController::class, 'facilitiesIndex'])->name('facilities');
Route::get('/contact', function () { return view('page.contact'); })->name('contact');

Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
        Route::get('/dashboard', [ReceptionistDashboardController::class, 'receptionistDashboardView'])->name('dashboard');
        Route::post('/quick-availability-check', [ReceptionistDeskController::class, 'receptionistQuickCheck'])->name('quick_check');
        Route::get('/walk-in', function () { return view('receptionist.walkin'); })->name('walkin');
        Route::post('/walk-in/store', [FrontOfficeCheckController::class, 'storeWalkIn'])->name('walkin.store');
        Route::get('/check-in', [FrontOfficeCheckController::class, 'receptionistCheckInView'])->name('checkin');
        Route::post('/check-in/process', [FrontOfficeCheckController::class, 'processCheckIn'])->name('checkin.process');
        Route::match(['get', 'post'], '/room-assignment', [FrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment');
        Route::post('/room-assignment/assign', [FrontOfficeCheckController::class, 'assignRoomNumber'])->name('roomassignment.assign');
        Route::match(['get', 'post'], '/check-out', [RoomLifecycleController::class, 'processCheckOut'])->name('checkout');
        Route::post('/check-out/process', [RoomLifecycleController::class, 'processCheckOut'])->name('checkout.process');
        Route::get('/folio', [FrontOfficeCheckController::class, 'receptionistFolioView'])->name('folio');
        Route::match(['get', 'post'], '/payments', [FrontOfficeFlowController::class, 'processPayment'])->name('payments');
        Route::post('/payments/process', [FrontOfficeFlowController::class, 'processPayment'])->name('payments.process');
        Route::get('/reservations', [ReceptionistReservationController::class, 'receptionistReservationsView'])->name('reservations');
        Route::get('/guests', [FrontOfficeCheckController::class, 'receptionistGuestsView'])->name('guests');
        Route::get('/guest-history', [ReceptionistDeskController::class, 'receptionistGuestHistoryView'])->name('guesthistory');
        Route::get('/room-availability', [CoreHousekeepingController::class, 'roomAvailabilityView'])->name('roomavailability');
        Route::get('/house-status', [CoreHousekeepingController::class, 'houseStatusView'])->name('housestatus');
        Route::post('/house-status/update', [CoreHousekeepingController::class, 'updateHouseStatus'])->name('housestatus.update');
    });

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [CoreDashboardController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [AdminReservationController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [OperationalViewController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [ExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [OperationalViewController::class, 'adminRestaurantView'])->name('restaurant');
        Route::get('/facilities-wellness', [CoreFacilityViewController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/finance-billing', [OperationalViewController::class, 'adminFinanceView'])->name('finance');
        Route::get('/reports', [LiveReportViewController::class, 'adminReportsView'])->name('reports');
        Route::get('/users-control', [AdminControlController::class, 'adminUserAndRoleView'])->name('userandrole');

        Route::redirect('/reports/export/excel', '/manager/report-export/reports/excel')->name('reports.export.excel');
        Route::redirect('/reports/export/pdf', '/manager/report-export/reports/pdf')->name('reports.export.pdf');
        Route::get('/report-export/{section}/excel', [ManagerReportController::class, 'excel'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.excel');
        Route::get('/report-export/{section}/pdf', [ManagerReportController::class, 'pdf'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.pdf');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [CoreDashboardController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [AdminReservationController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [OperationalViewController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [ExecutiveReportController::class, 'adminRoomServiceView'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [OperationalViewController::class, 'adminRestaurantView'])->name('restaurant');
        Route::redirect('/restaurant/menu', '/admin/restaurant-gastronomy?view=menu')->name('restaurant.menu');
        Route::get('/facilities-wellness', [CoreFacilityViewController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/users-control', [AdminControlController::class, 'adminUserAndRoleView'])->name('userandrole');
        Route::get('/finance-billing', [OperationalViewController::class, 'adminFinanceView'])->name('finance');
        Route::post('/finance/transaction/{id}/update', [AdminOperationController::class, 'adminUpdateTransactionStatus'])->name('finance.transaction.update');
        Route::get('/reports', [LiveReportViewController::class, 'adminReportsView'])->name('reports');
        Route::redirect('/reports/export/excel', '/admin/report-export/reports/excel')->name('reports.export.excel');
        Route::redirect('/reports/export/pdf', '/admin/report-export/reports/pdf')->name('reports.export.pdf');
        Route::get('/report-export/{section}/excel', [ManagerReportController::class, 'excel'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.excel');
        Route::get('/report-export/{section}/pdf', [ManagerReportController::class, 'pdf'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.pdf');

        Route::post('/facilities/store', [AdminOperationController::class, 'adminStoreFacility'])->name('facilities.store');
        Route::post('/facilities/{id}/update', [AdminOperationController::class, 'adminUpdateFacility'])->name('facilities.update');
        Route::delete('/facilities/{id}/delete', [AdminOperationController::class, 'adminDeleteFacility'])->name('facilities.delete');
        Route::post('/facilities/booking/{id}/update-status', [AdminOperationController::class, 'adminUpdateFacilityBookingStatus'])->name('facilities.booking.update-status');

        Route::post('/users/store', [AdminControlController::class, 'adminStoreUser'])->name('users.store');
        Route::get('/users/{id}/json-detail', [AdminControlController::class, 'adminUserJsonDetail'])->name('users.json');
        Route::post('/users/{id}/update', [AdminControlController::class, 'adminUpdateUser'])->name('users.update');
        Route::delete('/users/{id}/delete', [AdminOperationController::class, 'adminDeleteUser'])->name('users.delete');
    });

    Route::middleware('deny-manager-modification')->group(function () {
        Route::post('/rooms/store', [RoomLifecycleController::class, 'adminStoreRoom'])->name('rooms.store');
        Route::post('/rooms/{id}/update-status', [RoomLifecycleController::class, 'adminUpdateRoomStatus'])->name('rooms.update-status');
        Route::delete('/rooms/{id}/delete', [AdminOperationController::class, 'adminDeleteRoom'])->name('rooms.destroy');
        Route::post('/admin/room-types/store', [AdminOperationController::class, 'storeRoomType'])->name('admin.room-types.store');
        Route::post('/admin/room-types/{id}/update', [AdminOperationController::class, 'updateRoomType'])->name('admin.room-types.update');
        Route::delete('/admin/room-types/{id}/delete', [AdminOperationController::class, 'deleteRoomType'])->name('admin.room-types.delete');
        Route::post('/admin/reservations/{id}/update', [AdminControlController::class, 'adminUpdateReservation'])->name('admin.reservations.update');
        Route::delete('/admin/reservations/{id}/delete', [AdminOperationController::class, 'adminDeleteReservation'])->name('admin.reservations.delete');
        Route::post('/admin/restaurant-order/{id}/update-status', [ExecutiveReportController::class, 'adminUpdateOrderStatus'])->name('admin.restaurant.update-status');
        Route::delete('/admin/restaurant-order/{id}/delete', [AdminOperationController::class, 'adminDeleteRestaurantOrder'])->name('admin.restaurant.order.delete');
        Route::delete('/admin/facilities/booking/{id}/delete', [AdminOperationController::class, 'adminDeleteFacilityBooking'])->name('admin.facilities.booking.delete');
        Route::post('/admin/restaurant/menu/store', [AdminOperationController::class, 'adminStoreMenu'])->name('admin.restaurant.menu.store');
        Route::post('/admin/restaurant/menu/{id}/update', [AdminOperationController::class, 'adminUpdateMenu'])->name('admin.restaurant.menu.update');
        Route::delete('/admin/restaurant/menu/{id}/delete', [AdminOperationController::class, 'adminDeleteMenu'])->name('admin.restaurant.menu.delete');
    });

    Route::get('/admin/facilities/booking/{id}/detail', [CoreFacilityViewController::class, 'adminFacilityBookingDetail']);
    Route::get('/admin/finance/transaction/{id}/detail', [AdminOperationController::class, 'adminTransactionDetail']);
    Route::get('/admin/reservations/{id}/json-detail', [AdminOperationController::class, 'adminDetailReservation'])->name('admin.reservations.json');
    Route::get('/admin/restaurant-order/{id}/json-detail', [ExecutiveReportController::class, 'adminRestaurantOrderDetailJson'])->name('admin.restaurant.order.json');
    Route::get('/admin/rooms/{id}/json-detail', [AdminOperationController::class, 'adminRoomJsonDetail'])->name('admin.room.json');
});

require __DIR__.'/auth.php';