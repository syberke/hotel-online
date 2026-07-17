<x-auth-shell
    eyebrow="Account recovery"
    title="Reset your password"
    subtitle="Enter the email registered to your Oasis account. We will send a secure password reset link."
    image="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=1600&auto=format&fit=crop"
    maxWidth="max-w-lg"
>
    <x-auth-session-status class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email address</label>
            <div class="relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    <i class="fa-regular fa-envelope text-sm"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                       class="block w-full pl-10 pr-4 text-sm text-slate-900 placeholder-slate-400"
                       placeholder="name@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
        </div>

        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
            <i class="fa-solid fa-circle-info mr-2 text-blue-500"></i>
            The reset link is time-limited. Also check your spam folder if the email is not visible after a few minutes.
        </div>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
            Send password reset link
            <i class="fa-solid fa-paper-plane text-xs"></i>
        </button>

        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Back to sign in
        </a>
    </form>
</x-auth-shell>
