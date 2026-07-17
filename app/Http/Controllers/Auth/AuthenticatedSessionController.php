<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $captchaEnabled = filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));

        if ($captchaEnabled) {
            $request->validate([
                'g-recaptcha-response' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        try {
                            $response = Http::asForm()
                                ->timeout(10)
                                ->retry(2, 250, throw: false)
                                ->post('https://www.google.com/recaptcha/api/siteverify', [
                                    'secret' => config('services.recaptcha.secret_key'),
                                    'response' => $value,
                                    'remoteip' => request()->ip(),
                                ]);
                        } catch (ConnectionException $exception) {
                            report($exception);
                            $fail('Layanan reCAPTCHA tidak dapat dihubungi. Periksa koneksi internet atau sertifikat PHP, lalu coba lagi.');
                            return;
                        }

                        if (! $response->successful() || ! $response->json('success')) {
                            $fail('Verifikasi reCAPTCHA gagal. Silakan centang ulang kotak keamanan.');
                        }
                    },
                ],
            ], [
                'g-recaptcha-response.required' => 'Silakan centang verifikasi reCAPTCHA.',
            ]);
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'account_status' => 'active',
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('auth.failed')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->forget('url.intended');

        $user = $request->user()->fresh();
        $role = strtolower(trim((string) $user->role));

        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        $dashboardRoute = match ($role) {
            'admin' => 'admin.dashboard',
            'manager' => 'manager.dashboard',
            'receptionist' => 'receptionist.dashboard',
            'guest' => 'guest.dashboard',
            default => null,
        };

        if ($dashboardRoute === null) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Role akun tidak dikenali. Hubungi administrator.',
            ]);
        }

        return redirect()->route($dashboardRoute);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
