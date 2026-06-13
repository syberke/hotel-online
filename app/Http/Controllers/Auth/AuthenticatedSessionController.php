<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
/**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Mengambil data user yang baru saja login
        $user = Auth::user();

        // Mengarahkan ke dashboard dinamis sesuai nama rute (role.dashboard)
        // Contoh: jika role adalah 'manager', akan mengarah ke route('manager.dashboard')
        if (in_array($user->role, ['admin', 'manager', 'receptionist', 'guest'])) {
            return redirect()->route($user->role . '.dashboard');
        }

        // Fallback default jika role tidak dikenali
        return redirect()->route('home');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
