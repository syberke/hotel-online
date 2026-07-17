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
    public function test_manager_can_read_shared_admin_room_json_endpoint(): void
    {
        $request = Request::create('/admin/rooms/1/json-detail', 'GET');
        $route = (new Route(['GET'], '/admin/rooms/{id}/json-detail', fn () => null))
            ->name('admin.room.json');

        $manager = new User();
        $manager->role = 'manager';

        $request->setRouteResolver(fn () => $route);
        $request->setUserResolver(fn () => $manager);

        $response = (new RolePathGuard())->handle(
            $request,
            fn () => new Response('allowed', 200),
        );

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('allowed', $response->getContent());
    }

    public function test_manager_is_still_redirected_from_other_admin_routes(): void
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
