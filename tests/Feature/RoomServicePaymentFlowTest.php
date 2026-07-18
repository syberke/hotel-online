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

    public function test_checkout_payment_and_folio_share_the_same_room_service_ledger(): void
    {
        $service = file_get_contents(app_path('Services/BookingFolioService.php'));
        $checkout = file_get_contents(app_path('Http/Controllers/RoomLifecycleController.php'));
        $payments = file_get_contents(app_path('Http/Controllers/FrontOfficeFlowController.php'));
        $folio = file_get_contents(app_path('Http/Controllers/FolioController.php'));
        $checkoutView = file_get_contents(resource_path('views/receptionist/checkout.blade.php'));

        $this->assertStringContainsString("whereNotNull('payments.restaurant_order_id')", $service);
        $this->assertStringContainsString('Room Service #', $service);
        $this->assertStringContainsString("where('payments.payment_status', 'pending')", $service);
        $this->assertStringContainsString("'payment_status' => 'paid'", $service);
        $this->assertStringContainsString('BookingFolioService', $checkout);
        $this->assertStringContainsString('BookingFolioService', $payments);
        $this->assertStringContainsString('BookingFolioService', $folio);
        $this->assertStringContainsString('Settle folio first', $checkoutView);
        $this->assertStringContainsString('including Room Service', $checkoutView);
    }
}
