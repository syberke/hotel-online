<?php

namespace Tests\Unit;

use App\Http\Middleware\RolePathGuard;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RolePathGuardTest extends TestCase
{
    public function test_manager_can_read_shared_admin_detail_endpoints(): void
    {
        $sharedRoutes = [
            ['admin.room.json', '/admin/rooms/1/json-detail'],
            ['admin.reservations.json', '/admin/reservations/1/json-detail'],
            ['admin.restaurant.order.json', '/admin/restaurant-order/1/json-detail'],
            ['admin.users.json', '/admin/users/1/json-detail'],
            ['admin.facilities.booking.detail', '/admin/facilities/booking/1/detail'],
        ];

        foreach ($sharedRoutes as [$routeName, $uri]) {
            $request = Request::create($uri, 'GET');
            $route = (new Route(['GET'], $uri, fn () => null))->name($routeName);
            $manager = new User();
            $manager->role = 'manager';

            $request->setRouteResolver(fn () => $route);
            $request->setUserResolver(fn () => $manager);

            $response = (new RolePathGuard())->handle(
                $request,
                fn () => new Response('allowed', 200),
            );

            $this->assertSame(200, $response->getStatusCode(), $routeName . ' should be readable by Manager.');
            $this->assertSame('allowed', $response->getContent());
        }
    }

    public function test_manager_is_still_redirected_from_admin_mutations(): void
    {
        $request = Request::create('/admin/users/1/update', 'POST');
        $route = (new Route(['POST'], '/admin/users/{id}/update', fn () => null))
            ->name('admin.users.update');

        $manager = new User();
        $manager->role = 'manager';

        $request->setRouteResolver(fn () => $route);
        $request->setUserResolver(fn () => $manager);

        $response = (new RolePathGuard())->handle(
            $request,
            fn () => new Response('should not pass', 200),
        );

        $this->assertTrue($response->isRedirect());
        $this->assertStringContainsString('/manager/dashboard', $response->headers->get('Location'));
    }
}
