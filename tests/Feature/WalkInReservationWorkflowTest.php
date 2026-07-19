<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class WalkInReservationWorkflowTest extends TestCase
{
    public function test_receptionist_walk_in_routes_are_registered(): void
    {
        $this->assertNotNull(Route::getRoutes()->getByName('receptionist.walk-in.create'));
        $this->assertNotNull(Route::getRoutes()->getByName('receptionist.walk-in.store'));
    }

    public function test_walk_in_controller_creates_booking_without_guest_login_account(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/WalkInReservationController.php'));

        $this->assertStringContainsString("'user_id' => null", $controller);
        $this->assertStringContainsString("'created_by_user_id' => \$request->user()->id", $controller);
        $this->assertStringContainsString("'booking_source' => 'walk_in'", $controller);
        $this->assertStringContainsString("'guest_id' => \$guestId", $controller);
        $this->assertStringContainsString("'payment_status' => \$validated['payment_status']", $controller);
        $this->assertStringContainsString("where('status', 'available')", $controller);
        $this->assertStringContainsString("whereNotIn('id', \$occupiedRoomIds)", $controller);
    }

    public function test_booking_channel_migration_uses_string_channel_and_tracks_receptionist(): void
    {
        $migration = file_get_contents(database_path('migrations/2026_07_19_120000_add_booking_channel_to_bookings_table.php'));

        $this->assertStringContainsString("string('booking_source', 20)", $migration);
        $this->assertStringContainsString("default('online')", $migration);
        $this->assertStringContainsString("foreignId('created_by_user_id')", $migration);
        $this->assertStringContainsString("constrained('users')", $migration);
        $this->assertStringNotContainsString("enum('booking_source'", $migration);
    }

    public function test_front_office_flows_resolve_walk_in_guest_records(): void
    {
        $folio = file_get_contents(app_path('Services/BookingFolioService.php'));
        $checkIn = file_get_contents(app_path('Http/Controllers/FrontOfficeCheckController.php'));
        $checkout = file_get_contents(app_path('Http/Controllers/RoomLifecycleController.php'));
        $assignment = file_get_contents(app_path('Http/Controllers/RoomAssignmentController.php'));

        foreach ([$folio, $checkIn, $checkout, $assignment] as $source) {
            $this->assertStringContainsString("leftJoin('users', 'bookings.user_id'", $source);
            $this->assertStringContainsString("leftJoin('guests', 'bookings.guest_id'", $source);
            $this->assertStringContainsString('COALESCE(users.name, guests.name', $source);
        }
    }

    public function test_dashboards_and_reservation_pages_show_both_booking_channels(): void
    {
        $files = [
            resource_path('views/guest/dashboard.blade.php'),
            resource_path('views/receptionist/dashboard.blade.php'),
            resource_path('views/receptionist/reservations.blade.php'),
            resource_path('views/admin/dashboard.blade.php'),
            resource_path('views/manager/dashboard.blade.php'),
            resource_path('views/admin/reservation.blade.php'),
        ];

        foreach ($files as $file) {
            $view = file_get_contents($file);
            $this->assertStringContainsString('Online', $view, $file);
            $this->assertStringContainsString('Walk-In', $view, $file);
        }
    }

    public function test_receptionist_navigation_exposes_walk_in_registration(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/receptionist-dashboard.blade.php'));

        $this->assertStringContainsString('receptionist.walk-in.create', $layout);
        $this->assertStringContainsString('Walk-In Registration', $layout);
    }
}
