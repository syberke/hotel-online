        <?php

        use Illuminate\Support\Facades\Route;
        use App\Http\Controllers\ProfileController;
        use App\Http\Controllers\HotelOperationalController;

        // 1. WEBHOOK MIDTRANS (Wajib Di Luar Middleware Auth)
        Route::post('/midtrans/callback', [HotelOperationalController::class, 'handleMidtransCallback']);

        // 2. RUTE PUBLIK GLOBAL (Tanpa Proteksi Login)
        Route::get('/', [HotelOperationalController::class, 'index'])->name('home');
        Route::post('/rooms/check', [HotelOperationalController::class, 'checkAvailability'])->name('rooms.check');
        Route::get('/rooms', [HotelOperationalController::class, 'allRoomsView'])->name('rooms');
        Route::get('/rooms/{id}', [HotelOperationalController::class, 'roomShow'])->name('rooms.show');
        Route::get('/restaurant', [HotelOperationalController::class, 'restaurantIndex'])->name('restaurant');
        Route::get('/contact', function () { return view('page.contact'); })->name('contact');
        Route::get('/facilities', [HotelOperationalController::class, 'facilitiesIndex'])->name('facilities');
        Route::get('/restaurant/menu/{id}', [HotelOperationalController::class, 'menuShow'])->name('restaurant.detail');

        // 3. GERBANG AUTOMATIC ROLE REDIRECT
        Route::get('/dashboard', function () {
            $role = auth()->user()->role ?: 'guest';
            return redirect()->route($role . '.dashboard');
        })->middleware(['auth'])->name('dashboard');

        // 4. GRUP PROTEKSI OTENTIKASI (Wajib Login)
        Route::middleware(['auth'])->group(function () {

        // Profil Akun Global
            Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            // BARIS YANG ERROR SUDAH DIHAPUS DARI SINI
            Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
            // Layanan AJAX Tamu / In-House
            Route::post('/restaurant/order', [HotelOperationalController::class, 'placeGastronomyOrder'])->name('restaurant.order');
            Route::post('/facilities/book', [HotelOperationalController::class, 'bookFacility'])->name('facilities.book');
            Route::post('/room-service/order', [HotelOperationalController::class, 'storeRoomServiceOrder'])->name('room.service.order');
            Route::post('/bookings/{id}/cancel', [HotelOperationalController::class, 'cancelBooking'])->name('bookings.cancel');
            Route::post('/restaurant-order/pay', [HotelOperationalController::class, 'payRestaurantOrder'])->name('restaurant.order.pay');
            Route::post('/restaurant-order/settle', [HotelOperationalController::class, 'settleRestaurantOrder'])->name('restaurant.order.settle');
            Route::post('/restaurant-order/{id}/cancel', [HotelOperationalController::class, 'cancelRestaurantOrder'])->name('restaurant.order.cancel');
            Route::post('/restaurant-order/{id}/re-token', [HotelOperationalController::class, 'reTokenPendingOrder'])->name('restaurant.order.retoken');
            Route::get('/room-order/{id}/details', [HotelOperationalController::class, 'getRoomInvoiceDetails'])->name('room.invoice.details');
            Route::get('/restaurant-order/{id}/details', [HotelOperationalController::class, 'getRestaurantOrderDetails'])->name('restaurant.order.details');
            Route::post('/my-bookings/payment-success', [HotelOperationalController::class, 'localPaymentSuccess'])->name('bookings.payment.success');
            Route::post('/my-bookings/get-snap-token', [HotelOperationalController::class, 'getSnapToken'])->name('bookings.pay');
            Route::get('/lang/{locale}', [HotelOperationalController::class, 'changeLanguage'])->name('lang.switch');

            // JALUR KONTROL GUEST PORTAL
            Route::get('/guest/dashboard', [HotelOperationalController::class, 'dashboard'])->name('guest.dashboard');
            Route::get('/my-bookings', [HotelOperationalController::class, 'myBookings'])->name('bookings.my');
            Route::get('/my-stay', [HotelOperationalController::class, 'myStay'])->name('stay.my');
            Route::get('/room-service', [HotelOperationalController::class, 'roomService'])->name('room.service');
            Route::get('/restaurant-orders', [HotelOperationalController::class, 'restaurantOrders'])->name('restaurant.orders');
            Route::get('/facilities-booking', [HotelOperationalController::class, 'facilitiesBooking'])->name('facilities.booking');
            Route::get('/facilities-portal', [HotelOperationalController::class, 'facilities'])->name('facilities.portal');
            Route::get('/billing-matrix', [HotelOperationalController::class, 'billingMatrix'])->name('billing.matrix');

            // ======================================================================
            // JALUR KONTROL STAF INTERNAL RECEPTIONIST (FRONT OFFICE DESK)
            // ======================================================================
            Route::prefix('receptionist')->name('receptionist.')->group(function () {
                
                // Main Dashboard
                Route::get('/receptionist/dashboard', [HotelOperationalController::class, 'adminDashboardView'])->name('receptionist.dashboard');
                
                // Walk-in Management
                Route::get('/walk-in', function() { return view('receptionist.walkin'); })->name('walkin');
                Route::post('/walk-in/store', [HotelOperationalController::class, 'storeWalkIn'])->name('walkin.store');

                // Reservations Ledger
                Route::get('/reservations', function() { return view('receptionist.reservations'); })->name('reservations');
                
                // Guests Dossier
                Route::get('/guests', function() { return view('receptionist.guests'); })->name('guests');
                
                // Check-in Processing Hub
                Route::get('/check-in', function() { return view('receptionist.checkin'); })->name('checkin');
                Route::post('/check-in/process', [HotelOperationalController::class, 'processCheckIn'])->name('checkin.process');
                
                // Check-out & Settlement Hub
                Route::get('/check-out', function() { return view('receptionist.checkout'); })->name('checkout');
                Route::post('/check-out/process', [HotelOperationalController::class, 'processCheckOut'])->name('checkout.process');
                
                // Room Assignment Matrix
                Route::get('/room-assignment', function() { return view('receptionist.roomassignment'); })->name('roomassignment');
                Route::post('/room-assignment/assign', [HotelOperationalController::class, 'assignRoomNumber'])->name('roomassignment.assign');
                
                // Folio Ledger System
                Route::get('/folio', function() { return view('receptionist.folio'); })->name('folio');
                Route::post('/folio/charge/add', [HotelOperationalController::class, 'addFolioCharge'])->name('folio.charge.add');
                
                // Payments Processing
                Route::get('/payments', function() { return view('receptionist.payments'); })->name('payments');
                Route::post('/payments/process', [HotelOperationalController::class, 'processPayment'])->name('payments.process');
                
                // Wake-up Call Registry
                Route::get('/wakeup-call', function() { return view('receptionist.wakeupcall'); })->name('wakeupcall');
                Route::post('/wakeup-call/schedule', [HotelOperationalController::class, 'scheduleWakeUp'])->name('wakeupcall.schedule');
                
                // Guest History Archive
                Route::get('/guest-history', function() { return view('receptionist.guesthistory'); })->name('guesthistory');
                
                // Room Stock Availability Grid
                Route::get('/room-availability', function() { return view('receptionist.roomavailability'); })->name('roomavailability');
                
                // Real-time House Status Matrix
                Route::get('/house-status', function() { return view('receptionist.housestatus'); })->name('housestatus');
                Route::post('/house-status/update', [HotelOperationalController::class, 'updateHouseStatus'])->name('housestatus.update');
            });

            // ======================================================================
            // JALUR KONTROL STRATEGI STRATEGIC WORKSPACE MANAGER PORTAL (READ-ONLY)
            // ======================================================================
        Route::get('/manager/dashboard', [HotelOperationalController::class, 'adminDashboardView'])->name('manager.dashboard');
            Route::get('/manager/reservations', [HotelOperationalController::class, 'adminReservationsView'])->name('manager.reservation');
            Route::get('/manager/front-desk', [HotelOperationalController::class, 'adminFrontDeskView'])->name('manager.frontdesk');
            Route::get('/manager/rooms-inventory', [HotelOperationalController::class, 'adminRoomsInventoryView'])->name('manager.rooms');
            Route::get('/manager/room-service-orders', [HotelOperationalController::class, 'adminRoomServiceView'])->name('manager.roomservice');
            Route::get('/manager/restaurant-gastronomy', [HotelOperationalController::class, 'adminRestaurantView'])->name('manager.restaurant');
            Route::get('/manager/facilities-wellness', [HotelOperationalController::class, 'adminFacilitiesView'])->name('manager.facilities');
            Route::get('/manager/finance-billing', [HotelOperationalController::class, 'adminFinanceView'])->name('manager.finance');
            Route::get('/manager/reports', [HotelOperationalController::class, 'adminReportsView'])->name('manager.reports');
            Route::get('/manager/users-control', [HotelOperationalController::class, 'adminUserAndRoleView'])->name('manager.userandrole');

            // ======================================================================
            // JALUR INTEGRASI BACK-OFFICE ADMIN PORTAL (MURNI KHUSUS STAF ADMIN)
            // ======================================================================
            Route::get('/admin/dashboard', [HotelOperationalController::class, 'adminDashboardView'])->name('admin.dashboard');
            Route::get('/admin/reservations', [HotelOperationalController::class, 'adminReservationsView'])->name('admin.reservation');
            Route::get('/admin/front-desk', [HotelOperationalController::class, 'adminFrontDeskView'])->name('admin.frontdesk');
            Route::get('/admin/rooms-inventory', [HotelOperationalController::class, 'adminRoomsInventoryView'])->name('admin.rooms');
            Route::get('/admin/room-service-orders', [HotelOperationalController::class, 'adminRoomServiceView'])->name('admin.roomservice');
            Route::get('/admin/restaurant-gastronomy', [HotelOperationalController::class, 'adminRestaurantView'])->name('admin.restaurant');
            Route::get('/admin/facilities-wellness', [HotelOperationalController::class, 'adminFacilitiesView'])->name('admin.facilities');
            Route::get('/admin/finance-billing', [HotelOperationalController::class, 'adminFinanceView'])->name('admin.finance');
            Route::get('/admin/reports', [HotelOperationalController::class, 'adminReportsView'])->name('admin.reports');
            Route::get('/admin/users-control', [HotelOperationalController::class, 'adminUserAndRoleView'])->name('admin.userandrole');
        });

        require __DIR__.'/auth.php';