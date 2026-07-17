<x-auth-shell
    eyebrow="One last step"
    title="Verify your email address"
    subtitle="We sent a secure verification link to your registered email. Open the message and click the link before entering the guest dashboard."
    maxWidth="max-w-lg"
>
    <div class="space-y-5">
        <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">
            <div class="flex items-start gap-4">
                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm">
                    <i class="fa-regular fa-envelope"></i>
                </span>
                <div>
                    <p class="text-sm font-semibold text-slate-900">Check your inbox</p>
                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        The verification email was sent to <span class="font-semibold text-slate-900">{{ auth()->user()->email }}</span>.
                    </p>
                </div>
            </div>
        </div>

        @if (session('status') === 'verification-link-sent')
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <i class="fa-solid fa-circle-check mt-0.5"></i>
                <span>A new verification link has been sent. Check your inbox and spam folder.</span>
            </div>
        @endif

        <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
            <p class="font-semibold text-slate-800">Did not receive the email?</p>
            <p class="mt-1">Confirm the address above, check the spam folder, or request a new verification link.</p>
        </div>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/70 transition hover:bg-blue-700">
                <i class="fa-solid fa-paper-plane text-xs"></i>
                Resend verification email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                Sign out and use another account
            </button>
        </form>
    </div>
</x-auth-shell>
