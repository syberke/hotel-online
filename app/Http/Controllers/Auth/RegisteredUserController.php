<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];

        if (!app()->environment(['local', 'testing'])) {
            $rules['g-recaptcha-response'] = [
                'required',
                function ($attribute, $value, $fail) {
                    $response = Http::asForm()
                        ->timeout(10)
                        ->post('https://www.google.com/recaptcha/api/siteverify', [
                            'secret' => env('RECAPTCHA_SECRET_KEY'),
                            'response' => $value,
                            'remoteip' => request()->ip(),
                        ]);

                    if (!$response->successful() || !$response->json('success')) {
                        $fail('Verifikasi robot gagal, silakan centang ulang kotak reCAPTCHA.');
                    }
                },
            ];
        }

        $request->validate($rules, [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di Oasis, silakan gunakan email lain.',
            'password.required' => 'Password wajib ditentukan.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'g-recaptcha-response.required' => 'Silakan centang verifikasi robot reCAPTCHA.',
        ]);

        $user = DB::transaction(function () use ($request) {
            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'guest',
            ]);

            DB::table('guests')->updateOrInsert(
                ['email' => $request->email],
                [
                    'name' => $request->name,
                    'password' => $newUser->password,
                    'phone' => '-',
                    'identity_number' => '-',
                    'address' => '-',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            return $newUser;
        });

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
