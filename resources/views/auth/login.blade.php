<x-auth-shell
    eyebrow="Welcome back"
    title="Sign in to your account"
    subtitle="Use your registered email and password to access the Oasis guest portal."
>
    <x-auth-session-status class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email address</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                       placeholder="name@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between gap-4">
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 transition hover:text-blue-700">Forgot password?</a>
                @endif
            </div>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                       placeholder="Enter your password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
        </div>

        <label for="remember_me" class="flex cursor-pointer items-center gap-3 rounded-xl bg-slate-50 px-4 py-3">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
            <span class="text-sm text-slate-600">Keep me signed in on this device</span>
        </label>

        @if(config('services.recaptcha.site_key'))
            <div class="recaptcha-shell">
                <p class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500">
                    <i class="fa-solid fa-shield-halved text-blue-500"></i>
                    Security verification
                </p>
                <div class="flex justify-center sm:justify-start">
                    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                </div>
                <x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2 text-sm text-rose-600" />
            </div>
        @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                reCAPTCHA belum dikonfigurasi. Isi <code>RECAPTCHA_SITE_KEY</code> dan <code>RECAPTCHA_SECRET_KEY</code> di file <code>.env</code>.
            </div>
        @endif

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
            Sign in
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>

        <p class="text-center text-sm text-slate-500">
            New to Oasis?
            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700">Create an account</a>
        </p>
    </form>

    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</x-auth-shell>
