<?php

namespace Tests\Feature;

use App\Http\Controllers\GuestServiceController;
use App\Http\Controllers\RoomServicePaymentController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoomServicePaymentFlowTest extends TestCase
{
    public function test_room_service_payment_routes_use_real_controller_actions(): void
    {
        $this->assertSame(
            GuestServiceController::class . '@storeRoomServiceOrder',
            Route::getRoutes()->getByName('room.service.order')?->getActionName(),
        );
        $this->assertSame(
            RoomServicePaymentController::class . '@create',
            Route::getRoutes()->getByName('room.service.pay')?->getActionName(),
        );
        $this->assertSame(
            RoomServicePaymentController::class . '@settle',
            Route::getRoutes()->getByName('room.service.settle')?->getActionName(),
        );
    }

    public function test_room_service_page_exposes_pay_now_and_room_folio_choices(): void
    {
        $view = file_get_contents(resource_path('views/guest/roomservice.blade.php'));

        $this->assertStringContainsString('Pay now', $view);
        $this->assertStringContainsString('Charge to room folio', $view);
        $this->assertStringContainsString("route('room.service.pay')", $view);
        $this->assertStringContainsString("route('room.service.settle')", $view);
        $this->assertStringNotContainsString('>Delivered</span>', $view);
    }

    public function test_room_service_charge_creates_a_real_payment_ledger_entry(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/GuestServiceController.php'));

        $this->assertStringContainsString("'booking_id' => \$booking->id", $controller);
        $this->assertStringContainsString("'restaurant_order_id' => \$id", $controller);
        $this->assertStringContainsString("'payment_status' => 'pending'", $controller);
        $this->assertStringContainsString('Room Service charged to room folio', $controller);
    }

    public function test_midtrans_callback_distinguishes_room_and_restaurant_orders(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/PaymentGatewayController.php'));

        $this->assertStringContainsString("\$prefix === 'OA'", $controller);
        $this->assertStringContainsString("['RESTO', 'ROOMSERVICE']", $controller);
        $this->assertStringContainsString("where('restaurant_order_id', \$entityId)", $controller);
    }
}
