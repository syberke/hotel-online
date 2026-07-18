<?php

namespace Tests\Feature;

use App\Http\Controllers\ReceptionistGuestHistoryController;
use App\Http\Controllers\RestaurantVenueController;
use App\Http\Controllers\RoomAssignmentController;
use App\Http\Controllers\StaffRestaurantController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class StaffOperationsRoutesTest extends TestCase
{
    public function test_room_assignment_uses_the_dedicated_controller(): void
    {
        $this->assertSame(
            RoomAssignmentController::class . '@index',
            Route::getRoutes()->getByName('receptionist.roomassignment')?->getActionName(),
        );
        $this->assertSame(
            RoomAssignmentController::class . '@store',
            Route::getRoutes()->getByName('receptionist.roomassignment.assign')?->getActionName(),
        );
    }

    public function test_receptionist_can_update_guest_identity_through_a_real_controller_method(): void
    {
        $this->assertSame(
            ReceptionistGuestHistoryController::class . '@updateIdentity',
            Route::getRoutes()->getByName('receptionist.guesthistory.identity.update')?->getActionName(),
        );
        $this->assertTrue(method_exists(ReceptionistGuestHistoryController::class, 'updateIdentity'));
    }

    public function test_admin_and_manager_restaurant_pages_share_the_venue_workspace(): void
    {
        foreach (['admin.restaurant', 'manager.restaurant'] as $routeName) {
            $this->assertSame(
                StaffRestaurantController::class . '@index',
                Route::getRoutes()->getByName($routeName)?->getActionName(),
            );
        }
    }

    public function test_only_admin_has_restaurant_venue_write_routes(): void
    {
        $this->assertSame(
            RestaurantVenueController::class . '@store',
            Route::getRoutes()->getByName('admin.restaurant.venues.store')?->getActionName(),
        );
        $this->assertSame(
            RestaurantVenueController::class . '@update',
            Route::getRoutes()->getByName('admin.restaurant.venues.update')?->getActionName(),
        );
        $this->assertSame(
            RestaurantVenueController::class . '@destroy',
            Route::getRoutes()->getByName('admin.restaurant.venues.destroy')?->getActionName(),
        );

        $this->assertNull(Route::getRoutes()->getByName('manager.restaurant.venues.store'));
        $this->assertNull(Route::getRoutes()->getByName('manager.restaurant.venues.update'));
        $this->assertNull(Route::getRoutes()->getByName('manager.restaurant.venues.destroy'));
    }

    public function test_restaurant_view_no_longer_exposes_the_today_menu_tab(): void
    {
        $view = file_get_contents(resource_path('views/admin/restaurant.blade.php'));

        $this->assertStringNotContainsString("Today's Menu", $view);
        $this->assertStringContainsString('>Venues</a>', $view);
        $this->assertStringContainsString('$mainTab === \'venues\'', $view);
    }

    public function test_staff_sidebars_do_not_expose_standalone_folio_links(): void
    {
        $adminLayout = file_get_contents(resource_path('views/layouts/admin-dashboard.blade.php'));
        $receptionistLayout = file_get_contents(resource_path('views/layouts/receptionist-dashboard.blade.php'));

        $this->assertStringNotContainsString("'.folio', 'fa-file-invoice', 'Folio'", $adminLayout);
        $this->assertStringNotContainsString("['receptionist.folio'", $receptionistLayout);
    }

    public function test_receptionist_room_workspace_uses_canonical_room_statuses(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/receptionist-dashboard.blade.php'));
        $view = file_get_contents(resource_path('views/receptionist/roomavailability.blade.php'));

        $this->assertStringContainsString("['receptionist.roomavailability', 'fa-bed', 'Rooms']", $layout);
        $this->assertStringNotContainsString("['receptionist.housestatus'", $layout);
        $this->assertStringNotContainsString("'dirty'", $view);
        $this->assertStringNotContainsString('Vacant Dirty', $view);
        $this->assertStringContainsString("'maintenance'", $view);
    }
}
