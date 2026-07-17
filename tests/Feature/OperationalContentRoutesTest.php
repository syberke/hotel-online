<?php

namespace Tests\Feature;

use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\FolioController;
use App\Http\Controllers\RestaurantCatalogController;
use App\Http\Controllers\RestaurantMenuController;
use App\Http\Controllers\RestaurantReservationController;
use App\Http\Controllers\RestaurantVenueController;
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
            RestaurantCatalogController::class . '@show',
            Route::getRoutes()->getByName('restaurant.detail')?->getActionName(),
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

    public function test_staff_folio_routes_use_the_real_folio_controller(): void
    {
        foreach (['receptionist.folio', 'manager.folio', 'admin.folio'] as $routeName) {
            $this->assertSame(
                FolioController::class . '@show',
                Route::getRoutes()->getByName($routeName)?->getActionName(),
            );
        }

        $this->assertNull(Route::getRoutes()->getByName('receptionist.walkin'));
        $this->assertNull(Route::getRoutes()->getByName('receptionist.walkin.store'));
        $this->assertTrue(method_exists(FolioController::class, 'show'));
    }

    public function test_restaurant_admin_routes_have_real_crud_methods(): void
    {
        foreach (['store', 'update', 'destroy'] as $method) {
            $this->assertTrue(method_exists(RestaurantMenuController::class, $method));
            $this->assertTrue(method_exists(RestaurantVenueController::class, $method));
        }

        foreach (['store', 'updateStatus', 'destroy'] as $method) {
            $this->assertTrue(method_exists(RestaurantReservationController::class, $method));
        }

        $this->assertSame(
            RestaurantMenuController::class . '@store',
            Route::getRoutes()->getByName('admin.restaurant.menu.store')?->getActionName(),
        );
        $this->assertSame(
            RestaurantMenuController::class . '@update',
            Route::getRoutes()->getByName('admin.restaurant.menu.update')?->getActionName(),
        );
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
        $folio = file_get_contents(resource_path('views/receptionist/folio.blade.php'));
        $report = file_get_contents(resource_path('views/admin/reports-modern.blade.php'));

        $this->assertStringNotContainsString("route('rooms.check')", $home);
        $this->assertStringContainsString("route('contact.store')", $contact);
        $this->assertStringContainsString('$venues', $restaurant);
        $this->assertStringContainsString('$menuCategories', $restaurant);
        $this->assertStringContainsString("route('restaurant.reservations.store')", $restaurant);
        $this->assertStringNotContainsString('Breakfast & Laundry Pack', $folio);
        $this->assertStringContainsString('$charges as $charge', $folio);
        $this->assertStringNotContainsString('@elif', $report);
        $this->assertStringContainsString('Top menu item', $report);
        $this->assertStringContainsString('Top facility', $report);
    }
}
