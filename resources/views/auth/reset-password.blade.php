<x-guest-layout>
    <div class="min-h-screen relative font-sans antialiased bg-neutral-900">

        <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=2070&auto=format&fit=crop"
             alt="Oasis Luxury Suite Living"
             class="absolute inset-0 w-full h-full object-cover opacity-40 brightness-75">

        <div class="absolute inset-0 bg-neutral-950/30"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-16">
            
            <div class="w-full max-w-2xl bg-white border border-neutral-200 p-8 md:p-12 rounded-none shadow-none relative">

                <div class="absolute top-6 left-6 md:top-8 md:left-12">
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Return to Login
                    </a>
                </div>

                <div class="text-center mb-10 mt-6 md:mt-4">
                    <h2 class="text-5xl font-serif tracking-wide text-neutral-900 mb-3 italic select-none">Oasis</h2>
                    <h1 class="text-xs font-bold uppercase tracking-[0.25em] text-neutral-800">Update Credentials</h1>
                    <p class="text-[11px] uppercase tracking-wider text-neutral-400 mt-2">Configure your secure new entry password matrix</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div>
                            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Choose New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password" type="password" name="password" required autocomplete="new-password" autofocus
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Create secret password">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600 font-medium" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Confirm New Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </span>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Repeat secret password">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-600 font-medium" />
                        </div>

                    </div>

                    <div class="pt-4 border-t border-neutral-100 space-y-2 flex flex-col items-center justify-center">
                        <div class="inline-block transform scale-95 origin-center select-none">
                           <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="text-xs text-red-600 font-medium text-center w-full mt-1" />
                    </div>

                    <div class="pt-2 border-t border-neutral-100">
                        <button type="submit" 
                                class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3.5 px-4 rounded-none uppercase tracking-[0.2em] text-[10px] transition-all active:translate-y-[1px] shadow-sm cursor-pointer">
                            Reset Password & Lock Account
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</x-guest-layout>