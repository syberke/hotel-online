<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePathGuard
{
    private const ROLE_PREFIXES = [
        'guest' => 'guest',
        'receptionist' => 'receptionist',
        'manager' => 'manager',
        'admin' => 'admin',
    ];

    /**
     * Read-only staff endpoints that are shared by the Admin and Manager views.
     *
     * The Rooms & Inventory template is reused by both roles. Its eye action
     * requests the existing admin.room.json endpoint, so Manager must be able
     * to read this one JSON route without receiving access to Admin mutations.
     */
    private const SHARED_ADMIN_MANAGER_READ_ROUTES = [
        'admin.room.json',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $prefix = strtolower((string) $request->segment(1));

        if (!$user || !array_key_exists($prefix, self::ROLE_PREFIXES)) {
            return $next($request);
        }

        $role = strtolower(trim((string) $user->role));
        $requiredRole = self::ROLE_PREFIXES[$prefix];

        if (
            $role === $requiredRole
            || $this->isSharedAdminManagerReadRequest($request, $role)
        ) {
            return $next($request);
        }

        $dashboardRoute = match ($role) {
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'receptionist' => 'receptionist.dashboard',
            'guest' => 'guest.dashboard',
            default => null,
        };

        if ($dashboardRoute === null) {
            abort(403, 'Role akun tidak dikenali.');
        }

        return redirect()->route($dashboardRoute)
            ->with('error', 'Akses portal dialihkan sesuai role akun Anda.');
    }

    private function isSharedAdminManagerReadRequest(Request $request, string $role): bool
    {
        if (!in_array($role, ['admin', 'manager'], true) || !$request->isMethod('GET')) {
            return false;
        }

        $routeName = $request->route()?->getName();

        return is_string($routeName)
            && in_array($routeName, self::SHARED_ADMIN_MANAGER_READ_ROUTES, true);
    }
}
