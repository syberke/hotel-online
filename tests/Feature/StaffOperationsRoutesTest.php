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

        foreach (['admin', 'manager'] as $prefix) {
            $this->assertSame(
                RestaurantVenueController::class . '@store',
                Route::getRoutes()->getByName($prefix . '.restaurant.venues.store')?->getActionName(),
            );
            $this->assertSame(
                RestaurantVenueController::class . '@update',
                Route::getRoutes()->getByName($prefix . '.restaurant.venues.update')?->getActionName(),
            );
            $this->assertSame(
                RestaurantVenueController::class . '@destroy',
                Route::getRoutes()->getByName($prefix . '.restaurant.venues.destroy')?->getActionName(),
            );
        }
    }

    public function test_restaurant_view_no_longer_exposes_the_today_menu_tab(): void
    {
        $view = file_get_contents(resource_path('views/admin/restaurant.blade.php'));

        $this->assertStringNotContainsString("Today's Menu", $view);
        $this->assertStringContainsString('>Venues</a>', $view);
        $this->assertStringContainsString('$mainTab === \'venues\'', $view);
    }
}
