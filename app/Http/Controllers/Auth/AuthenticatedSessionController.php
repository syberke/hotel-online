<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi Google reCAPTCHA v2 secara native
        $request->validate([
            'g-recaptcha-response' => [
                'required',
                function ($attribute, $value, $fail) {
                    $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                        'secret'   => env('RECAPTCHA_SECRET_KEY'),
                        'response' => $value,
                        'remoteip' => request()->ip(),
                    ]);

                    if (!$response->json('success')) {
                        $fail('Verifikasi Captcha gagal, silakan centang ulang kotak reCAPTCHA.');
                    }
                },
            ],
        ], [
            'g-recaptcha-response.required' => 'Silakan centang verifikasi Captcha keamanan.',
        ]);

        $request->authenticate();

        $request->session()->regenerate();

        // Mengambil entitas data user yang berhasil terotentikasi
        $user = Auth::user();

        // LOGIKA REDIRECT STRATEGIS BERDASARKAN LEVEL AKSES (ROLE)
        if ($user->role === 'guest') {
            return redirect()->route('guest.dashboard');
        }

        if (in_array($user->role, ['admin', 'manager', 'receptionist'])) {
            return redirect()->route($user->role . '.dashboard');
        }

        // Fallback default jika status user tidak memiliki role spesifik
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