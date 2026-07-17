<x-auth-shell
    eyebrow="Create your account"
    title="Start your Oasis experience"
    subtitle="Register once to manage bookings, dining, facilities, and your stay from one guest portal."
    image="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1600&auto=format&fit=crop"
    maxWidth="max-w-2xl"
>
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Full name</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"><i class="fa-regular fa-user text-sm"></i></span><input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400" placeholder="Your full name"></div>
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div>
                <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email address</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"><i class="fa-regular fa-envelope text-sm"></i></span><input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400" placeholder="name@example.com"></div>
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Password</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"><i class="fa-solid fa-lock text-sm"></i></span><input id="password" type="password" name="password" required autocomplete="new-password" class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400" placeholder="Create a password"></div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirm password</label>
                <div class="relative"><span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"><i class="fa-solid fa-shield-halved text-sm"></i></span><input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400" placeholder="Repeat your password"></div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
            </div>
        </div>

        <label class="flex cursor-pointer items-start gap-3 rounded-xl bg-slate-50 p-4">
            <input type="checkbox" name="terms" value="1" required {{ old('terms') ? 'checked' : '' }}>
            <span class="text-sm leading-6 text-slate-600">
                I agree to the <a href="{{ route('terms') }}" target="_blank" class="font-semibold text-blue-600 hover:text-blue-700">Terms of Service</a> and <a href="{{ route('privacy') }}" target="_blank" class="font-semibold text-blue-600 hover:text-blue-700">Privacy Policy</a>.
            </span>
        </label>
        <x-input-error :messages="$errors->get('terms')" class="text-sm text-rose-600" />

        @if(config('services.recaptcha.site_key'))
            <div class="recaptcha-shell"><p class="mb-3 flex items-center gap-2 text-xs font-medium text-slate-500"><i class="fa-solid fa-shield-halved text-blue-500"></i>Security verification</p><div class="flex justify-center sm:justify-start"><div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div></div><x-input-error :messages="$errors->get('g-recaptcha-response')" class="mt-2 text-sm text-rose-600" /></div>
        @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">reCAPTCHA belum dikonfigurasi. Isi <code>RECAPTCHA_SITE_KEY</code> dan <code>RECAPTCHA_SECRET_KEY</code> di file <code>.env</code>.</div>
        @endif

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">Create account<i class="fa-solid fa-arrow-right text-xs"></i></button>

        <p class="text-center text-sm text-slate-500">Already registered? <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Sign in</a></p>
    </form>

    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</x-auth-shell>
