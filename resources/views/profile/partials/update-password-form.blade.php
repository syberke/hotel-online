<section class="space-y-6">
    <header>
        <h2 class="text-xs font-bold uppercase tracking-widest text-neutral-900">
            Update Password
        </h2>
        <p class="mt-1 text-[11px] uppercase tracking-wider text-neutral-400">
            Ensure your account is using a long, random password to maintain tactical security.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Current Password</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1 text-xs text-red-600" />
        </div>

        <div>
            <label for="update_password_password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">New Password</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1 text-xs text-red-600" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Confirm New Password</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1 text-xs text-red-600" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3 px-6 rounded-none uppercase tracking-widest text-[10px] transition-all">
                Update Security
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" 
                   class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">
                    Saved successfully.
                </p>
            @endif
        </div>
    </form>
</section>