<?php

namespace Tests\Feature;

use App\Http\Controllers\GuestServiceController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoomServicePaymentFlowTest extends TestCase
{
    public function test_room_service_uses_only_the_folio_order_route(): void
    {
        $this->assertSame(
            GuestServiceController::class . '@storeRoomServiceOrder',
            Route::getRoutes()->getByName('room.service.order')?->getActionName(),
        );

        $this->assertNull(Route::getRoutes()->getByName('room.service.pay'));
        $this->assertNull(Route::getRoutes()->getByName('room.service.settle'));
        $this->assertFileDoesNotExist(app_path('Http/Controllers/RoomServicePaymentController.php'));
    }

    public function test_room_service_page_exposes_folio_billing_without_midtrans(): void
    {
        $view = file_get_contents(resource_path('views/guest/roomservice.blade.php'));

        $this->assertStringContainsString('Place order & add to folio', $view);
        $this->assertStringContainsString('No payment is required now', $view);
        $this->assertStringContainsString("route('room.service.order')", $view);
        $this->assertStringNotContainsString('Pay now', $view);
        $this->assertStringNotContainsString("route('room.service.pay')", $view);
        $this->assertStringNotContainsString("route('room.service.settle')", $view);
        $this->assertStringNotContainsString('window.snap', $view);
    }

    public function test_room_service_charge_creates_a_real_pending_folio_entry(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/GuestServiceController.php'));

        $this->assertStringContainsString("'booking_id' => \$booking->id", $controller);
        $this->assertStringContainsString("'restaurant_order_id' => \$id", $controller);
        $this->assertStringContainsString("'payment_method' => 'cash'", $controller);
        $this->assertStringContainsString("'payment_status' => 'pending'", $controller);
        $this->assertStringContainsString('Room Service charged to room folio', $controller);
    }
}
