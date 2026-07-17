<?php

namespace Tests\Feature;

use App\Http\Controllers\PaymentGatewayController;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RoomReceiptRouteTest extends TestCase
{
    public function test_room_receipt_route_targets_an_existing_controller_method(): void
    {
        $route = collect(Route::getRoutes()->getRoutes())
            ->first(fn ($route) => $route->getName() === 'room.invoice.details');

        $this->assertNotNull($route, 'The room receipt route is missing.');
        $this->assertTrue(
            method_exists(PaymentGatewayController::class, 'getRoomInvoiceDetails'),
            'The room receipt controller method is missing.'
        );
        $this->assertStringContainsString(
            PaymentGatewayController::class . '@getRoomInvoiceDetails',
            $route->getActionName()
        );
    }
}
