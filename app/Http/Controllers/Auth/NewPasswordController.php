<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        $captchaEnabled = filled(config('services.recaptcha.site_key'))
            && filled(config('services.recaptcha.secret_key'));

        if ($captchaEnabled) {
            $rules['g-recaptcha-response'] = [
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
            ];
        }

        $request->validate($rules, [
            'g-recaptcha-response.required' => 'Silakan centang verifikasi reCAPTCHA.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}
