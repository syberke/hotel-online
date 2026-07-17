<x-auth-shell
    eyebrow="Secure password reset"
    title="Create a new password"
    subtitle="Choose a strong password for your Oasis account, then complete the security verification."
    image="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1600&auto=format&fit=crop"
    maxWidth="max-w-2xl"
>
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-600">
            Resetting password for <span class="font-semibold text-slate-900">{{ old('email', $request->email) }}</span>
        </div>

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
            <div>
                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">New password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input id="password" type="password" name="password" required autocomplete="new-password" autofocus
                           class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                           placeholder="Create a new password">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">Confirm new password</label>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <i class="fa-solid fa-shield-halved text-sm"></i>
                    </span>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                           placeholder="Repeat the new password">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
            </div>
        </div>

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
        @endif

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
            Update password
            <i class="fa-solid fa-check text-xs"></i>
        </button>
    </form>

    @if(config('services.recaptcha.site_key'))
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
</x-auth-shell>
