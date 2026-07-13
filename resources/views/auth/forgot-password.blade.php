<x-guest-layout>
    <div class="min-h-screen relative font-sans antialiased bg-neutral-900">

        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop"
             alt="Oasis Luxury Exterior"
             class="absolute inset-0 w-full h-full object-cover opacity-40 brightness-75">

        <div class="absolute inset-0 bg-neutral-950/30"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-16">
            
            <div class="w-full max-w-md bg-white border border-neutral-200 p-8 md:p-12 rounded-none shadow-none relative">

                <div class="absolute top-6 left-6 md:top-8 md:left-12">
                    <a href="{{ route('login') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-2 group">
                        <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-1"></i> Back to Login
                    </a>
                </div>

                <div class="text-center mb-8 mt-6 md:mt-4">
                    <h2 class="text-5xl font-serif tracking-wide text-neutral-900 mb-3 italic select-none">Oasis</h2>
                    <h1 class="text-xs font-bold uppercase tracking-[0.25em] text-neutral-800">Forgot Password</h1>
                    <p class="text-[11px] uppercase tracking-wider text-neutral-400 mt-3 leading-relaxed">
                        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
                    </p>
                </div>

                <x-auth-session-status class="mb-6 text-xs text-emerald-600 font-bold uppercase tracking-wider bg-emerald-50 p-3 border border-emerald-200 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-neutral-400 text-xs">
                                <i class="fa-regular fa-envelope"></i>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="block w-full pl-9 pr-4 py-3 bg-white border border-neutral-300 rounded-none text-xs tracking-wide text-neutral-800 placeholder-neutral-400 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 transition-all" 
                                   placeholder="Enter registered email">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-600" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3.5 px-4 rounded-none uppercase tracking-[0.2em] text-[10px] transition-all active:translate-y-[1px]">
                            Email Password Reset Link
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-guest-layout>