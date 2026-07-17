<x-auth-shell
    eyebrow="Security check"
    title="Confirm your password"
    subtitle="This area contains sensitive account information. Enter your password again to continue."
    maxWidth="max-w-lg"
>
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="mb-2 block text-sm font-medium text-slate-700">Current password</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <i class="fa-solid fa-lock text-sm"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="current-password" autofocus
                       class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                       placeholder="Enter your current password">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
            Confirm and continue
            <i class="fa-solid fa-arrow-right text-xs"></i>
        </button>
    </form>
</x-auth-shell>
