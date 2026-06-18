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

    // PERBAIKAN: Arahkan ke rute spesifik sesuai konfigurasi web.php kamu
    if ($user->role === 'guest') {
        return redirect()->route('dashboard'); // Mengarah ke rute bernama 'dashboard' (dashboard.guest)
    }

    if (in_array($user->role, ['admin', 'manager', 'receptionist'])) {
        // Jika kamu menggunakan rute seperti admin.dashboard, manager.dashboard, dll.
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
