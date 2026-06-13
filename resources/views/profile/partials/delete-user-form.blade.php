<section class="space-y-6">
    <header>
        <h2 class="text-xs font-bold uppercase tracking-widest text-red-800">
            Delete Account Terminal
        </h2>
        <p class="mt-1 text-[11px] uppercase tracking-wider text-neutral-400">
            Once your account is deleted, all of its resources, booking histories, and transaction data will be permanently purged.
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" 
            class="bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-6 rounded-none uppercase tracking-widest text-[10px] transition-colors">
        Delete Account Permanently
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white border border-neutral-200 rounded-none">
            @csrf
            @method('delete')

            <h2 class="text-sm font-bold uppercase tracking-widest text-neutral-900 mb-2">
                Are you absolutely sure you want to delete your account?
            </h2>

            <p class="text-xs text-neutral-400 leading-relaxed mb-6">
                Please enter your security account password to authorize the final deletion sequences.
            </p>

            <div class="max-w-md mb-6">
                <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Confirm Password</label>
                <input id="password" name="password" type="password" placeholder="Enter password to authorize purge"
                       class="block w-full px-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-red-600" />
            </div>

            <div class="flex justify-end gap-3 border-t border-neutral-100 pt-4">
                <button type="button" x-on:click="$dispatch('close')" class="border border-neutral-300 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-3 px-6 rounded-none transition-colors">
                    Cancel Operation
                </button>
                <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold text-[10px] uppercase tracking-widest py-3 px-6 rounded-none transition-colors">
                    Authorize Purge
                </button>
            </div>
        </form>
    </x-modal>
</section>