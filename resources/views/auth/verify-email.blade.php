<x-guest-layout>
    <div class="min-h-screen relative font-sans antialiased bg-neutral-900">
        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop"
             alt="Oasis Hotel Security" class="absolute inset-0 w-full h-full object-cover opacity-30 brightness-50">
        <div class="absolute inset-0 bg-neutral-950/40"></div>

        <div class="relative min-h-screen flex items-center justify-center px-4 py-16">
            <div class="w-full max-w-md bg-white border border-neutral-200 p-8 md:p-12 rounded-none shadow-none text-center">
                
                <h2 class="text-4xl font-serif tracking-wide text-neutral-900 mb-2 italic select-none">Oasis</h2>
                <h1 class="text-xs font-bold uppercase tracking-[0.25em] text-neutral-800 mb-6">Verify Your Email</h1>
                
                <p class="text-xs text-neutral-600 mb-6 leading-relaxed">
                    Terima kasih telah bergabung dengan Oasis Hotel & Resort. Sebelum memulai, silakan verifikasi akun Anda dengan mengklik tautan (link) yang baru saja kami kirimkan ke alamat email Anda.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-3 bg-neutral-900 text-white text-[11px] uppercase tracking-wider font-bold">
                        Link konfirmasi baru telah dikirimkan ke email Anda!
                    </div>
                @endif

                <div class="space-y-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-3 px-4 rounded-none uppercase tracking-[0.2em] text-[10px] transition-all">
                            Kirim Ulang Email Konfirmasi
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-neutral-400 hover:text-neutral-900 text-[11px] font-bold uppercase tracking-wider underline transition-colors">
                            Log Out / Keluar Akun
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>