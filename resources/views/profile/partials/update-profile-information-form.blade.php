<section class="space-y-6">
    <header>
        <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-900">
            Profile Information
        </h2>
        <p class="mt-1 text-[11px] uppercase tracking-wider text-neutral-400">
            Update your account's profile information and email communication anchor.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Full Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
            <x-input-error class="mt-1 text-xs text-red-600" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Email Address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
            <x-input-error class="mt-1 text-xs text-red-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 border border-amber-200">
                    <p class="text-xs text-amber-900">
                        Your email address is unverified.
                        <button form="send-verification" class="underline font-bold hover:text-neutral-900 block mt-1 uppercase tracking-wider text-[10px]">
                            Click here to re-send verification email.
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-bold text-[10px] uppercase tracking-wider text-emerald-700">
                            A new verification link has been sent to your email address.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3 px-6 rounded-none uppercase tracking-widest text-[10px] transition-all">
                Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" 
                   class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>