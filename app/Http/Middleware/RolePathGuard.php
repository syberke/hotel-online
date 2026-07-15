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

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $prefix = strtolower((string) $request->segment(1));

        if (!$user || !array_key_exists($prefix, self::ROLE_PREFIXES)) {
            return $next($request);
        }

        $role = strtolower(trim((string) $user->role));
        $requiredRole = self::ROLE_PREFIXES[$prefix];

        if ($role === $requiredRole) {
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
}
