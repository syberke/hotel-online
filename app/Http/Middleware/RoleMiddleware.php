<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Mengecek apakah user sudah login DAN role-nya ada di dalam parameter rute
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}