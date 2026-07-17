<x-auth-shell
    eyebrow="Account verification"
    title="Verify your email address"
    subtitle="Oasis now uses a secure email verification link instead of a manually entered OTP code."
    maxWidth="max-w-lg"
>
    <div class="space-y-5">
        <div class="flex items-start gap-4 rounded-2xl border border-blue-100 bg-blue-50 p-5">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm">
                <i class="fa-regular fa-envelope"></i>
            </span>
            <div>
                <p class="text-sm font-semibold text-slate-900">Open the verification email</p>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Click the secure verification link sent to your registered email address. The old six-digit OTP form is no longer used.
                </p>
            </div>
        </div>

        @auth
            @if (session('status') === 'verification-link-sent')
                <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <i class="fa-solid fa-circle-check mt-0.5"></i>
                    <span>A new verification link has been sent. Check your inbox and spam folder.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
                    <i class="fa-solid fa-paper-plane text-xs"></i>
                    Resend verification email
                </button>
            </form>

            <a href="{{ route('verification.notice') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                Open verification status
            </a>
        @else
            <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
                Return to sign in
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        @endauth
    </div>
</x-auth-shell>
