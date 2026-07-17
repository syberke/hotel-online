<footer id="contact" class="border-t border-slate-800 bg-slate-950 px-6 pb-8 pt-16 text-white">
    <div class="mx-auto max-w-7xl">
        <div class="mb-14 grid grid-cols-1 gap-10 md:grid-cols-2 lg:grid-cols-4">
            <div class="space-y-5">
                <a href="{{ route('home') }}" class="oasis-logo-transparent inline-flex" aria-label="Oasis Hotel home">
                    <x-brand-logo class="h-12 w-auto brightness-0 invert" />
                </a>
                <p class="max-w-sm text-sm leading-6 text-slate-400">
                    A comfortable place to stay in Nusa Dua with rooms, dining venues, facilities, contact support, and guest services connected to the same hotel system.
                </p>
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
                        <span>{{ config('hotel.address') }}</span>
                    </li>
                    @if(config('hotel.phone'))
                        <li class="flex items-center gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-blue-400"><i class="fa-solid fa-phone text-xs"></i></span>
                            <a href="tel:{{ preg_replace('/\s+/', '', config('hotel.phone')) }}" class="transition hover:text-white">{{ config('hotel.phone') }}</a>
                        </li>
                    @endif
                    @if(config('hotel.email'))
                        <li class="flex items-center gap-3">
                            <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-blue-400"><i class="fa-solid fa-envelope text-xs"></i></span>
                            <a href="mailto:{{ config('hotel.email') }}" class="break-all transition hover:text-white">{{ config('hotel.email') }}</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="mb-3 text-sm font-semibold text-white">Need help?</h4>
                <p class="mb-5 text-sm leading-6 text-slate-400">
                    Use the real Contact form to create a message in the hotel inbox. Admin can process it and Manager can review it.
                </p>
                <a href="{{ route('contact') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                    Contact hotel team
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>
        </div>

        <div class="flex flex-col items-center justify-between gap-4 border-t border-slate-800 pt-7 text-xs text-slate-500 md:flex-row">
            <p>&copy; {{ date('Y') }} Oasis Hotel & Resort. All rights reserved.</p>
            <p>Hotel information and operational data are managed from the Oasis system.</p>
        </div>
    </div>
</footer>
