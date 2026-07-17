<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\RestaurantCatalogController;
use App\Http\Controllers\RestaurantReservationController;
use App\Http\Controllers\RestaurantVenueController;
use App\Http\Controllers\WalkInController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class OperationalContentRoutesTest extends TestCase
{
    public function test_public_content_routes_use_real_controllers(): void
    {
        $this->assertSame(
            RestaurantCatalogController::class . '@index',
            Route::getRoutes()->getByName('restaurant')?->getActionName(),
        );
        $this->assertSame(
            ContactMessageController::class . '@store',
            Route::getRoutes()->getByName('contact.store')?->getActionName(),
        );
        $this->assertSame(
            RestaurantReservationController::class . '@store',
            Route::getRoutes()->getByName('restaurant.reservations.store')?->getActionName(),
        );
    }

    public function test_front_office_routes_do_not_reference_missing_or_dummy_controller_methods(): void
    {
        $this->assertSame(
            WalkInController::class . '@create',
            Route::getRoutes()->getByName('receptionist.walkin')?->getActionName(),
        );
        $this->assertSame(
            WalkInController::class . '@store',
            Route::getRoutes()->getByName('receptionist.walkin.store')?->getActionName(),
        );
        $this->assertSame(
            FolioController::class . '@show',
            Route::getRoutes()->getByName('receptionist.folio')?->getActionName(),
        );

        $this->assertTrue(method_exists(WalkInController::class, 'create'));
        $this->assertTrue(method_exists(WalkInController::class, 'store'));
        $this->assertTrue(method_exists(FolioController::class, 'show'));
    }

    public function test_restaurant_admin_routes_have_real_crud_methods(): void
    {
        foreach (['store', 'update', 'destroy'] as $method) {
            $this->assertTrue(method_exists(RestaurantVenueController::class, $method));
        }

        foreach (['store', 'updateStatus', 'destroy'] as $method) {
            $this->assertTrue(method_exists(RestaurantReservationController::class, $method));
        }

        $this->assertNotNull(Route::getRoutes()->getByName('admin.restaurant.venues.store'));
        $this->assertNotNull(Route::getRoutes()->getByName('admin.restaurant.venues.update'));
        $this->assertNotNull(Route::getRoutes()->getByName('admin.restaurant.venues.destroy'));
        $this->assertNotNull(Route::getRoutes()->getByName('admin.restaurant.reservations.status'));
    }

    public function test_key_views_no_longer_contain_known_dummy_patterns(): void
    {
        $home = file_get_contents(resource_path('views/page/home.blade.php'));
        $contact = file_get_contents(resource_path('views/page/contact.blade.php'));
        $restaurant = file_get_contents(resource_path('views/page/restaurant.blade.php'));
        $walkIn = file_get_contents(resource_path('views/receptionist/walkin.blade.php'));
        $folio = file_get_contents(resource_path('views/receptionist/folio.blade.php'));

        $this->assertStringNotContainsString("route('rooms.check')", $home);
        $this->assertStringContainsString("route('contact.store')", $contact);
        $this->assertStringContainsString('$venues', $restaurant);
        $this->assertStringContainsString("route('restaurant.reservations.store')", $restaurant);
        $this->assertStringContainsString("route('receptionist.walkin.store')", $walkIn);
        $this->assertStringNotContainsString('John Anderson', $walkIn);
        $this->assertStringNotContainsString('Breakfast & Laundry Pack', $folio);
        $this->assertStringContainsString('$charges as $charge', $folio);
    }
}
