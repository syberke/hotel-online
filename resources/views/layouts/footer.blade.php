<footer id="contact" class="border-t border-slate-800 bg-slate-950 px-6 pb-8 pt-16 text-white">
    <div class="mx-auto max-w-7xl">
        <div class="mb-14 grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-5">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-600 text-white">
                        <i class="fa-solid fa-hotel text-sm"></i>
                    </span>
                    <span>
                        <span class="block text-lg font-semibold tracking-tight">Oasis Hotel</span>
                        <span class="block text-xs text-slate-400">Comfort made simple</span>
                    </span>
                </a>
                <p class="max-w-sm text-sm leading-6 text-slate-400">
                    A comfortable place to stay in Nusa Dua with convenient rooms, dining, facilities, and guest services in one connected experience.
                </p>
                <div class="flex gap-3">
                    @foreach(['instagram', 'facebook-f', 'x-twitter'] as $icon)
                        <a href="#" class="grid h-10 w-10 place-items-center rounded-xl border border-slate-800 text-slate-400 transition hover:border-blue-500 hover:bg-blue-500/10 hover:text-blue-300" aria-label="Social media">
                            <i class="fa-brands fa-{{ $icon }} text-sm"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="mb-5 text-sm font-semibold text-white">Explore</h4>
                <ul class="space-y-3 text-sm text-slate-400">
                    <li><a href="{{ route('home') }}" class="transition hover:text-white">Home</a></li>
                    <li><a href="{{ route('rooms') }}" class="transition hover:text-white">Rooms</a></li>
                    <li><a href="{{ route('facilities') }}" class="transition hover:text-white">Facilities</a></li>
                    <li><a href="{{ route('restaurant') }}" class="transition hover:text-white">Restaurant</a></li>
                    <li><a href="{{ route('contact') }}" class="transition hover:text-white">Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-5 text-sm font-semibold text-white">Contact</h4>
                <ul class="space-y-4 text-sm text-slate-400">
                    <li class="flex items-start gap-3 leading-6">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-blue-400"><i class="fa-solid fa-location-dot text-xs"></i></span>
                        <span>Jl. Pantai Indah No. 88, Nusa Dua, Bali 80363</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-blue-400"><i class="fa-solid fa-phone text-xs"></i></span>
                        <span>+62 361 770 888</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-blue-400"><i class="fa-solid fa-envelope text-xs"></i></span>
                        <a href="mailto:stay@oasishotel.com" class="transition hover:text-white">stay@oasishotel.com</a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="mb-3 text-sm font-semibold text-white">Stay updated</h4>
                <p class="mb-5 text-sm leading-6 text-slate-400">
                    Receive room availability, dining updates, and seasonal hotel information.
                </p>
                <form action="#" method="POST" class="space-y-3">
                    @csrf
                    <input type="email" required placeholder="Email address" class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-sm text-white placeholder-slate-500 focus:border-blue-500 focus:ring-blue-500/20">
                    <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                        Subscribe
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-800 pt-7 text-xs text-slate-500 md:flex-row">
            <p>&copy; {{ date('Y') }} Oasis Hotel. All rights reserved.</p>
            <div class="flex gap-5">
                <a href="#" class="transition hover:text-slate-300">Privacy</a>
                <a href="#" class="transition hover:text-slate-300">Terms</a>
            </div>
        </div>
    </div>
</footer>
