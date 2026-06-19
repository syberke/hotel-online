<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika ada session 'locale', terapkan bahasanya ke sistem Laravel
        if (Session::has('locale')) {
            App::setLocale(Session::get('locale'));
        } else {
            // Default bahasa jika belum memilih (misal: 'en')
            App::setLocale('en'); 
        }

        return $next($request);
    }
}