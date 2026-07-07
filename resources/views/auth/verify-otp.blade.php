<x-guest-layout>
    <div class="min-h-screen relative font-sans antialiased bg-neutral-900">

        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop"
             alt="Oasis Hotel Security"
             class="absolute inset-0 w-full h-full object-cover opacity-30 brightness-50">

        <div class="absolute inset-0 bg-neutral-950/40"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-16">
            <div class="w-full max-w-md bg-white border border-neutral-200 p-8 md:p-12 rounded-none shadow-none relative">
                
                <div class="text-center mb-8">
                    <h2 class="text-4xl font-serif tracking-wide text-neutral-900 mb-2 italic select-none">Oasis</h2>
                    <h1 class="text-xs font-bold uppercase tracking-[0.25em] text-neutral-800">Two-Factor Email Verification</h1>
                    <p class="text-[11px] uppercase tracking-wider text-neutral-400 mt-2 leading-relaxed">
                        Buka kotak masuk <span class="text-neutral-800 font-bold">Email Gmail</span> kamu, kami telah mengirimkan 6 digit kode sandi keamanan OTP rahasia ke akun Anda.
                    </p>
                </div>

                <form method="POST" action="{{ route('auth.otp.verify') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="otp_code" class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 text-center mb-3">Kode Verifikasi OTP</label>
                        <div class="relative max-w-xs mx-auto">
                            <input id="otp_code" type="text" name="otp_code" maxlength="6" required autofocus autocomplete="off"
                                   class="block w-full text-center py-3 bg-neutral-50 border border-neutral-300 rounded-none text-base font-mono tracking-[0.5em] font-bold text-neutral-900 focus:outline-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 placeholder-neutral-300 transition-all" 
                                   placeholder="000000">
                        </div>
                        <x-input-error :messages="$errors->get('otp_code')" class="mt-2 text-xs text-red-600 text-center font-medium" />
                    </div>

                    <div class="pt-2">
                        <button type="submit" 
                                class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3.5 px-4 rounded-none uppercase tracking-[0.2em] text-[10px] transition-all active:translate-y-[1px]">
                            Verifikasi Kode Keamanan
                        </button>
                    </div>

                    <div class="text-center pt-4 border-t border-neutral-100">
                        <p class="text-[10px] font-medium text-neutral-400 uppercase tracking-wider">
                            Salah akun? 
                            <a href="{{ route('login') }}" class="font-bold text-neutral-900 underline ms-1 hover:text-neutral-700">Kembali ke Login</a>
                        </p>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>