<footer id="contact" class="bg-neutral-950 text-white pt-20 pb-8 px-6 border-t border-neutral-900 font-sans antialiased">
    <div class="max-w-7xl mx-auto">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            
            <div class="space-y-4">
                <h3 class="text-3xl font-serif italic tracking-wide text-white select-none">Oasis</h3>
                <p class="text-neutral-400 text-xs leading-relaxed max-w-sm">
                    Menghadirkan akomodasi premium berstandar internasional dengan kehangatan pelayanan bintang lima. Terinspirasi oleh kemewahan arsitektur minimalis yang menyatu dengan keindahan alam.
                </p>
                <div class="flex space-x-5 pt-4 text-neutral-400 text-sm">
                    <a href="#" class="hover:text-amber-400 transition-colors"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="hover:text-amber-400 transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-amber-400 transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" class="hover:text-amber-400 transition-colors"><i class="fa-brands fa-pinterest-p"></i></a>
                </div>
            </div>

        <div>
    <h4 class="text-xs uppercase tracking-[0.2em] font-bold mb-6 text-amber-400">Jelajah Oasis</h4>
    <ul class="space-y-3 text-xs text-neutral-400 font-medium">
        <li><a href="{{ route('home') }}" class="hover:text-white hover:underline underline-offset-4 transition-all">Home</a></li>
        <li><a href="{{ route('rooms') }}" class="hover:text-white hover:underline underline-offset-4 transition-all">Rooms & Suites</a></li>
        <li><a href="{{ route('facilities') }}" class="hover:text-white hover:underline underline-offset-4 transition-all">Resort Facilities</a></li>
        <li><a href="{{ route('restaurant') }}" class="hover:text-white hover:underline underline-offset-4 transition-all">Fine Dining Restaurant</a></li>
        <li><a href="#" class="hover:text-white hover:underline underline-offset-4 transition-all">Curated Experiences</a></li>
    </ul>
</div>

            <div>
                <h4 class="text-xs uppercase tracking-[0.2em] font-bold mb-6 text-amber-400">Hubungi Kami</h4>
                <ul class="space-y-4 text-xs text-neutral-400 font-medium">
                    <li class="flex items-start gap-3 leading-relaxed">
                        <i class="fa-solid fa-location-dot text-amber-400 mt-0.5 w-4 shrink-0"></i>
                        <span>Jl. Pantai Indah No. 88, Nusa Dua, Bali, 80363, Indonesia</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-amber-400 w-4 shrink-0"></i>
                        <span>+62 361 770 888</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-amber-400 w-4 shrink-0"></i>
                        <a href="mailto:stay@oasishotel.com" class="hover:text-white underline">stay@oasishotel.com</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs uppercase tracking-[0.2em] font-bold mb-4 text-amber-400">Newsletter</h4>
                <p class="text-neutral-400 text-xs leading-relaxed mb-4">
                    Berlangganan untuk menerima pembaruan musiman, penawaran eksklusif, dan info jurnal perjalanan Oasis.
                </p>
                
                <form action="#" method="POST" class="space-y-2">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-0">
                        <input type="email" required placeholder="Your email address" 
                               class="w-full bg-neutral-900 border border-neutral-800 text-white text-xs tracking-wide px-4 py-3 rounded-none placeholder-neutral-500 focus:outline-none focus:border-neutral-700 focus:ring-0 transition-all">
                        <button type="submit" 
                                class="bg-white hover:bg-neutral-200 text-neutral-950 font-bold text-[10px] uppercase tracking-widest px-5 py-3 rounded-none transition-colors whitespace-nowrap">
                            Subscribe
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <div class="border-t border-neutral-900 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] text-neutral-500 uppercase tracking-widest font-medium">
            <div class="text-center md:text-left">
                &copy; {{ date('Y') }} Oasis Hotel Management Group. All rights reserved.
            </div>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="#" class="hover:text-neutral-300 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-neutral-300 transition-colors">Terms of Service</a>
                <a href="#" class="hover:text-neutral-300 transition-colors">Legal Matrix</a>
            </div>
        </div>

    </div>
</footer>