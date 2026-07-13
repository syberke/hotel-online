<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DenyManagerModification
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role === 'manager') {
            return redirect()->back()->with('error', 'Akses ditolak: Akun Manager hanya diizinkan membaca data.');
        }
        return $next($request);
    }
}
