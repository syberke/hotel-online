<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicPortalController;
use App\Http\Controllers\RestaurantCatalogController;
use App\Http\Controllers\RestaurantMenuController;
use App\Http\Controllers\RestaurantVenueController;
use App\Http\Controllers\RestaurantReservationController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\GuestStayController;
use App\Http\Controllers\GuestServiceController;
use App\Http\Controllers\GuestFacilityController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\RestaurantOrderController;
use App\Http\Controllers\ReceptionistDeskController;
use App\Http\Controllers\ReceptionistReservationController;
use App\Http\Controllers\ReceptionistDashboardController;
use App\Http\Controllers\ReceptionistGuestHistoryController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\FrontOfficeCheckController;
use App\Http\Controllers\FrontOfficeFlowController;
use App\Http\Controllers\RoomLifecycleController;
use App\Http\Controllers\CoreHousekeepingController;
use App\Http\Controllers\AdminOperationController;
use App\Http\Controllers\AdminControlController;
use App\Http\Controllers\AdminReservationController;
use App\Http\Controllers\ExecutiveReportController;
use App\Http\Controllers\OperationalViewController;
use App\Http\Controllers\StaffRestaurantController;
use App\Http\Controllers\CoreDashboardController;
use App\Http\Controllers\CoreFacilityViewController;
use App\Http\Controllers\LiveReportViewController;
use App\Http\Controllers\CoreManagerReportController;
use App\Http\Controllers\StaffRoomDetailController;
use App\Http\Controllers\StaffRoomServiceController;

Route::post('/midtrans/callback', [PaymentGatewayController::class, 'handleMidtransCallback']);

Route::get('/', [PublicPortalController::class, 'index'])->name('home');
Route::get('/rooms', [PublicPortalController::class, 'allRoomsView'])->name('rooms');
Route::get('/rooms/{id}', [PublicPortalController::class, 'roomShow'])->name('rooms.show');
Route::post('/rooms/check', [PublicPortalController::class, 'checkAvailability'])->name('rooms.check');
Route::get('/restaurant', [RestaurantCatalogController::class, 'index'])->name('restaurant');
Route::get('/restaurant/menu/{id}', [RestaurantCatalogController::class, 'show'])->name('restaurant.detail');
Route::get('/facilities', [PublicPortalController::class, 'facilitiesIndex'])->name('facilities');
Route::get('/contact', fn () => view('page.contact'))->name('contact');
Route::post('/contact', [ContactMessageController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');
Route::view('/privacy', 'page.privacy')->name('privacy');
Route::view('/terms', 'page.terms')->name('terms');

Route::get('/dashboard', function () {
    $role = auth()->user()->role ?: 'guest';
    return redirect()->route($role . '.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/instance', function () {
    $hostname = gethostname() ?: 'unknown-container';

    $html = '<!doctype html>
    <html lang="id">
    <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Bukti Load Balancer</title>
      <style>
        body{margin:0;min-height:100vh;display:grid;place-items:center;background:#0f172a;font-family:Arial;color:#e2e8f0}
        main{width:min(560px,calc(100% - 40px));padding:36px;border:1px solid #334155;border-radius:24px;background:#111827}
        .ok{display:inline-block;padding:8px 12px;border-radius:999px;background:#064e3b;color:#a7f3d0;font-weight:700}
        .host{margin-top:20px;padding:18px;border-radius:14px;background:#1e293b;font-family:monospace;font-size:22px;color:#93c5fd;word-break:break-all}
        p{line-height:1.7;color:#94a3b8}
      </style>
    </head>
    <body>
      <main>
        <span class="ok">LOAD BALANCER AKTIF</span>
        <h1>Oasis Hotel Online</h1>
        <p>Refresh halaman ini. Hostname akan berganti saat Nginx membagi request.</p>
        <div class="host">Container: '.e($hostname).'</div>
      </main>
    </body>
    </html>';

    return response($html)
        ->header('Content-Type', 'text/html; charset=UTF-8')
        ->header('X-App-Node', $hostname);
})->name('instance');
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
        Route::get('/billing-matrix', fn () => view('guest.billingmatrix'))->name('billing.matrix');
        Route::get('/restaurant-order/{id}/details', [RestaurantOrderController::class, 'details'])->name('restaurant.order.details');
    });

    Route::post('/bookings/{id}/cancel', [GuestStayController::class, 'cancelBooking'])->name('bookings.cancel');
    Route::post('/my-bookings/get-snap-token', [PaymentGatewayController::class, 'getSnapToken'])->name('bookings.pay');
    Route::post('/my-bookings/payment-success', [PaymentGatewayController::class, 'localPaymentSuccess'])->name('bookings.payment.success');
    Route::get('/room-order/{id}/details', [PaymentGatewayController::class, 'getRoomInvoiceDetails'])->name('room.invoice.details');

    Route::post('/restaurant/order', [GuestServiceController::class, 'placeGastronomyOrder'])->name('restaurant.order');
    Route::post('/restaurant/reservations', [RestaurantReservationController::class, 'store'])->name('restaurant.reservations.store');
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
        Route::get('/check-in', [FrontOfficeCheckController::class, 'receptionistCheckInView'])->name('checkin');
        Route::post('/check-in/process', [FrontOfficeCheckController::class, 'processCheckIn'])->name('checkin.process');
        Route::get('/room-assignment', [RoomAssignmentController::class, 'index'])->name('roomassignment');
        Route::post('/room-assignment/assign', [RoomAssignmentController::class, 'store'])->name('roomassignment.assign');
        Route::match(['get', 'post'], '/check-out', [RoomLifecycleController::class, 'processCheckOut'])->name('checkout');
        Route::post('/check-out/process', [RoomLifecycleController::class, 'processCheckOut'])->name('checkout.process');
        Route::get('/folio', [FolioController::class, 'show'])->name('folio');
        Route::match(['get', 'post'], '/payments', [FrontOfficeFlowController::class, 'processPayment'])->name('payments');
        Route::post('/payments/process', [FrontOfficeFlowController::class, 'processPayment'])->name('payments.process');
        Route::get('/reservations', [ReceptionistReservationController::class, 'receptionistReservationsView'])->name('reservations');
        Route::get('/guests', [FrontOfficeCheckController::class, 'receptionistGuestsView'])->name('guests');
        Route::get('/guest-history', [ReceptionistGuestHistoryController::class, 'receptionistGuestHistoryView'])->name('guesthistory');
        Route::patch('/guest-history/{userId}/identity', [ReceptionistGuestHistoryController::class, 'updateIdentity'])->name('guesthistory.identity.update');
        Route::get('/room-availability', [CoreHousekeepingController::class, 'roomAvailabilityView'])->name('roomavailability');
        Route::redirect('/house-status', '/receptionist/room-availability')->name('housestatus');
    });

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::get('/dashboard', [CoreDashboardController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [AdminReservationController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [OperationalViewController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/folio', [FolioController::class, 'show'])->name('folio');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [StaffRoomServiceController::class, 'index'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [StaffRestaurantController::class, 'index'])->name('restaurant');
        Route::redirect('/restaurant/venues', '/manager/restaurant-gastronomy?view=venues')->name('restaurant.venues');
        Route::get('/facilities-wellness', [CoreFacilityViewController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/finance-billing', [OperationalViewController::class, 'adminFinanceView'])->name('finance');
        Route::get('/reports', [LiveReportViewController::class, 'adminReportsView'])->name('reports');
        Route::get('/users-control', [AdminControlController::class, 'adminUserAndRoleView'])->name('userandrole');
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages');

        Route::redirect('/reports/export/excel', '/manager/report-export/reports/excel')->name('reports.export.excel');
        Route::redirect('/reports/export/pdf', '/manager/report-export/reports/pdf')->name('reports.export.pdf');
        Route::get('/report-export/{section}/excel', [CoreManagerReportController::class, 'excel'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.excel');
        Route::get('/report-export/{section}/pdf', [CoreManagerReportController::class, 'pdf'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.pdf');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [CoreDashboardController::class, 'adminDashboardView'])->name('dashboard');
        Route::get('/reservations', [AdminReservationController::class, 'adminReservationsView'])->name('reservation');
        Route::get('/front-desk', [OperationalViewController::class, 'adminFrontDeskView'])->name('frontdesk');
        Route::get('/folio', [FolioController::class, 'show'])->name('folio');
        Route::get('/rooms-inventory', [AdminOperationController::class, 'adminRoomsInventoryView'])->name('rooms');
        Route::get('/room-service-orders', [StaffRoomServiceController::class, 'index'])->name('roomservice');
        Route::get('/restaurant-gastronomy', [StaffRestaurantController::class, 'index'])->name('restaurant');
        Route::redirect('/restaurant/menu', '/admin/restaurant-gastronomy?view=venues')->name('restaurant.menu');
        Route::redirect('/restaurant/venues', '/admin/restaurant-gastronomy?view=venues')->name('restaurant.venues');
        Route::post('/restaurant/venues', [RestaurantVenueController::class, 'store'])->name('restaurant.venues.store');
        Route::patch('/restaurant/venues/{venue}', [RestaurantVenueController::class, 'update'])->name('restaurant.venues.update');
        Route::delete('/restaurant/venues/{venue}', [RestaurantVenueController::class, 'destroy'])->name('restaurant.venues.destroy');
        Route::patch('/restaurant/reservations/{reservation}/status', [RestaurantReservationController::class, 'updateStatus'])->name('restaurant.reservations.status');
        Route::delete('/restaurant/reservations/{reservation}', [RestaurantReservationController::class, 'destroy'])->name('restaurant.reservations.destroy');
        Route::get('/facilities-wellness', [CoreFacilityViewController::class, 'adminFacilitiesView'])->name('facilities');
        Route::get('/users-control', [AdminControlController::class, 'adminUserAndRoleView'])->name('userandrole');
        Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages');
        Route::patch('/contact-messages/{contactMessage}/status', [ContactMessageController::class, 'updateStatus'])->name('contact-messages.status');
        Route::delete('/contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');
        Route::get('/finance-billing', [OperationalViewController::class, 'adminFinanceView'])->name('finance');
        Route::post('/finance/transaction/{id}/update', [AdminOperationController::class, 'adminUpdateTransactionStatus'])->name('finance.transaction.update');
        Route::get('/reports', [LiveReportViewController::class, 'adminReportsView'])->name('reports');
        Route::redirect('/reports/export/excel', '/admin/report-export/reports/excel')->name('reports.export.excel');
        Route::redirect('/reports/export/pdf', '/admin/report-export/reports/pdf')->name('reports.export.pdf');
        Route::get('/report-export/{section}/excel', [CoreManagerReportController::class, 'excel'])
            ->where('section', 'overview|reservations|frontdesk|rooms|roomservice|restaurant|facilities|finance|reports|users')
            ->name('section-report.excel');
        Route::get('/report-export/{section}/pdf', [CoreManagerReportController::class, 'pdf'])
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
        Route::post('/admin/restaurant/menu/store', [RestaurantMenuController::class, 'store'])->name('admin.restaurant.menu.store');
        Route::post('/admin/restaurant/menu/{id}/update', [RestaurantMenuController::class, 'update'])->name('admin.restaurant.menu.update');
        Route::delete('/admin/restaurant/menu/{id}/delete', [RestaurantMenuController::class, 'destroy'])->name('admin.restaurant.menu.delete');
    });

    Route::get('/admin/facilities/booking/{id}/detail', [CoreFacilityViewController::class, 'adminFacilityBookingDetail'])->name('admin.facilities.booking.detail');
    Route::get('/admin/reservations/{id}/json-detail', [AdminOperationController::class, 'adminDetailReservation'])->name('admin.reservations.json');
    Route::get('/admin/restaurant-order/{id}/json-detail', [ExecutiveReportController::class, 'adminRestaurantOrderDetailJson'])->name('admin.restaurant.order.json');
    Route::get('/admin/rooms/{id}/json-detail', [StaffRoomDetailController::class, 'show'])->name('admin.room.json');
});

require __DIR__.'/auth.php';