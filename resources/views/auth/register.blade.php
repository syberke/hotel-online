<x-guest-layout>
    <div class="min-h-screen relative font-sans antialiased bg-neutral-900">

        <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop"
             alt="Oasis Luxury Room"
             class="absolute inset-0 w-full h-full object-cover opacity-40 brightness-75">

        <div class="absolute inset-0 bg-neutral-950/30"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-16">
            
            <div class="w-full max-w-2xl bg-white border border-neutral-200 p-8 md:p-12 rounded-none shadow-none relative">

                <div class="absolute top-6 left-6 md:top-8 md:left-12">
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Home
                    </a>
                </div>

                <div class="text-center mb-10 mt-6 md:mt-4">
                    <h2 class="text-5xl font-serif tracking-wide text-neutral-900 mb-3 italic select-none">Oasis</h2>
                    <h1 class="text-xl font-bold uppercase tracking-widest text-neutral-800">Create Account</h1>
                    <p class="text-[11px] uppercase tracking-wider text-neutral-400 mt-2">Join Oasis and start your luxury experience</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div>
                            <label for="name" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Full Name</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Enter your full name">
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1.5 text-xs text-red-600" />
                        </div>

                        <div>
                            <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Email Address</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Enter your email">
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        
                        <div>
                            <label for="password" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password" type="password" name="password" required autocomplete="new-password"
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Create password">
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-600" />
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                       class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                       placeholder="Confirm password">
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-600" />
                        </div>

                    </div>

                    <div class="flex items-start text-xs text-neutral-500 py-2 border-t border-neutral-100">
                        <input type="checkbox" required 
                               class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-950 focus:ring-offset-0 w-3.5 h-3.5 mt-0.5">
                        <span class="ms-2 text-[11px] leading-normal font-medium text-neutral-500 uppercase tracking-wider">
                            I agree to the <a href="#" class="font-bold text-neutral-900 underline hover:text-neutral-700">Terms of Service</a> and <a href="#" class="font-bold text-neutral-900 underline hover:text-neutral-700">Privacy Policy</a>
                        </span>
                    </div>

                    <div class="pt-4 border-t border-neutral-100 space-y-2 flex flex-col items-center justify-center">
                        <div class="inline-block transform scale-95 origin-center select-none">
                          <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        </div>
                        <x-input-error :messages="$errors->get('g-recaptcha-response')" class="text-xs text-red-600 font-medium text-center w-full mt-1" />
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3.5 px-4 rounded-none uppercase tracking-[0.2em] text-[10px] transition-all active:translate-y-[1px]">
                            Create Account
                        </button>
                    </div>

                    <div class="text-center pt-4 border-t border-neutral-100">
                        <p class="text-[11px] font-medium text-neutral-400 uppercase tracking-wider">
                            Already have an account? 
                            <a href="{{ route('login') }}" class="font-bold text-neutral-900 underline ms-1 hover:text-neutral-700">Sign In</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</x-guest-layout>