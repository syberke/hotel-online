<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[680px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel in Nusa Dua" class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/75 to-blue-950/30"></div>
            <div class="absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-slate-950/70 to-transparent"></div>

            <div class="relative mx-auto flex min-h-[680px] max-w-7xl items-center px-4 py-24 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                        Comfortable stays in Nusa Dua, Bali
                    </span>
                    <h1 class="mt-6 text-5xl font-semibold leading-[1.02] tracking-tight sm:text-6xl lg:text-7xl">
                        A simpler way to enjoy your hotel stay.
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-slate-200 sm:text-lg">
                        Choose a comfortable room, manage reservations online, order dining, book facilities, and access guest services from one connected hotel experience.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('rooms') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500">
                            Find a room
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                        <a href="{{ route('facilities') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">
                            Explore facilities
                            <i class="fa-solid fa-spa text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success') || session('error') || session('info'))
            <section class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                        <i class="fa-solid fa-circle-check mt-0.5"></i><span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                        <i class="fa-solid fa-circle-exclamation mt-0.5"></i><span>{{ session('error') }}</span>
                    </div>
                @endif
                @if(session('info'))
                    <div class="flex flex-col gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3"><i class="fa-solid fa-circle-info mt-0.5"></i><span>{{ session('info') }}</span></div>
                        @guest
                            <a href="{{ route('login') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Sign in</a>
                        @endguest
                    </div>
                @endif
            </section>
        @endif

        <section class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('rooms') }}" method="GET" class="grid grid-cols-1 gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/10 md:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_auto] lg:items-end">
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Check-in</span>
                    <input type="date" name="check_in" min="{{ date('Y-m-d') }}" value="{{ request('check_in', date('Y-m-d')) }}" class="w-full px-4 py-3 text-sm">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Check-out</span>
                    <input type="date" name="check_out" min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full px-4 py-3 text-sm">
                </label>
                <label class="block">
                    <span class="mb-2 block text-sm font-medium text-slate-700">Guests</span>
                    <select name="guests" class="w-full px-4 py-3 text-sm">
                        @foreach([1, 2, 3, 4, 5, 6] as $guestCount)
                            <option value="{{ $guestCount }} Guests">{{ $guestCount }} guest{{ $guestCount > 1 ? 's' : '' }}</option>
                        @endforeach
                    </select>
                </label>
                <button type="submit" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Check rooms
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </button>
            </form>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach([
                    ['fa-headset', 'Helpful service', 'Guest support is available for reservations, dining, facilities, and in-house requests.'],
                    ['fa-location-dot', 'Convenient location', 'Stay in Nusa Dua with practical access to beaches, local attractions, and the airport.'],
                    ['fa-mobile-screen-button', 'Connected guest portal', 'Manage bookings, receipts, restaurant orders, and facilities from your account.'],
                ] as [$icon, $title, $description])
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span>
                        <h2 class="mt-5 text-lg font-semibold text-slate-900">{{ $title }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Rooms</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Choose the stay that fits your trip</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">Compare room types, current availability, and nightly prices before making a reservation.</p>
                </div>
                <a href="{{ route('rooms') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">View all rooms <i class="fa-solid fa-arrow-right text-xs"></i></a>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($roomsLiveList->take(4) as $room)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="relative h-52 overflow-hidden bg-slate-100">
                            <img src="{{ $room->foto_url }}" alt="{{ $room->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm {{ $room->available_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                {{ $room->available_count > 0 ? $room->available_count . ' available' : 'Fully booked' }}
                            </span>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-slate-900">{{ $room->name }}</h3>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $room->description }}</p>
                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div><p class="text-xs text-slate-500">From</p><p class="mt-1 text-lg font-semibold text-blue-700">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400"> / night</span></p></div>
                                <a href="{{ route('rooms.show', $room->id) }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white hover:bg-blue-700">Details</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Room information is not available yet.</div>
                @endforelse
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                    <div>
                        <p class="text-sm font-medium text-blue-300">Facilities</p>
                        <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Relax, stay active, or spend time together</h2>
                        <p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">Hotel facilities are organized for wellness, fitness, pool time, beach activities, and family experiences.</p>
                        <a href="{{ route('facilities') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">Explore facilities <i class="fa-solid fa-arrow-right text-xs"></i></a>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        @foreach([
                            ['fa-person-swimming', 'Swimming pool', 'Outdoor space for swimming and relaxing.'],
                            ['fa-spa', 'Spa & wellness', 'Body treatments and relaxing sessions.'],
                            ['fa-dumbbell', 'Fitness center', 'Cardio and strength equipment for hotel guests.'],
                        ] as [$icon, $title, $description])
                            <article class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                                <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/10 text-blue-300"><i class="fa-solid {{ $icon }}"></i></span>
                                <h3 class="mt-5 text-base font-semibold">{{ $title }}</h3>
                                <p class="mt-2 text-sm leading-6 text-slate-400">{{ $description }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">Dining</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Hotel dining for every part of the day</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">Browse available dishes and continue to the restaurant page or guest portal to place an order.</p>
                </div>
                <a href="{{ route('restaurant') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">Browse full menu <i class="fa-solid fa-arrow-right text-xs"></i></a>
            </div>

            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @forelse($culinaryMenus->take(3) as $menu)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="h-56 overflow-hidden bg-slate-100"><img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4"><h3 class="text-base font-semibold text-slate-900">{{ $menu->name }}</h3><p class="shrink-0 text-sm font-semibold text-blue-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</p></div>
                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $menu->description }}</p>
                            @auth
                                @if(auth()->user()->role === 'guest' && Route::has('guest.restaurant.orders'))
                                    <a href="{{ route('guest.restaurant.orders') }}" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Order from guest portal <i class="fa-solid fa-arrow-right text-xs"></i></a>
                                @else
                                    <a href="{{ route('restaurant') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">View menu</a>
                                @endif
                            @else
                                <a href="{{ route('restaurant') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">View menu</a>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Restaurant menu is not available yet.</div>
                @endforelse
            </div>
        </section>

        <section class="border-y border-slate-200 bg-white py-20">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
                <div>
                    <p class="text-sm font-medium text-blue-600">Location</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Easy access to Nusa Dua and nearby attractions</h2>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-500">Oasis Hotel is located in the Nusa Dua area, with convenient access to beaches, local destinations, and Ngurah Rai International Airport.</p>
                    <div class="mt-6 space-y-3 text-sm text-slate-600">
                        <p class="flex items-start gap-3"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600"><i class="fa-solid fa-location-dot text-xs"></i></span><span>Jl. Pantai Indah No. 88, Nusa Dua, Bali 80363</span></p>
                        <p class="flex items-start gap-3"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-blue-50 text-blue-600"><i class="fa-solid fa-plane text-xs"></i></span><span>Approximately 25 minutes from Ngurah Rai International Airport</span></p>
                    </div>
                    <a href="{{ route('contact') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">Contact the hotel <i class="fa-solid fa-arrow-right text-xs"></i></a>
                </div>
                <div id="liveOasisMap" class="h-96 w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm"></div>
            </div>
        </section>

        <section class="mx-auto max-w-5xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-sm font-medium text-blue-600">Frequently asked questions</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Helpful information before your stay</h2>
            </div>
            <div class="mt-8 space-y-3">
                @foreach([
                    ['What time are check-in and check-out?', 'Standard check-in starts at 3:00 PM and check-out is at 12:00 PM. Contact the hotel for early arrival or late departure requests.'],
                    ['Can I change or cancel a booking?', 'Pending reservations can be cancelled from My Bookings. Other changes depend on the booking status and should be discussed with the hotel team.'],
                    ['How do I use guest services?', 'After signing in, open the guest portal to view bookings, receipts, restaurant orders, facilities, and services available during an active stay.'],
                ] as [$question, $answer])
                    <details class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-sm font-semibold text-slate-900"><span>{{ $question }}</span><span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-slate-50 text-slate-500 transition group-open:rotate-45"><i class="fa-solid fa-plus text-xs"></i></span></summary>
                        <p class="mt-4 border-t border-slate-100 pt-4 text-sm leading-7 text-slate-500">{{ $answer }}</p>
                    </details>
                @endforeach
            </div>
        </section>

        @include('layouts.footer')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const target = document.getElementById('liveOasisMap');
            if (!target || typeof L === 'undefined') return;

            const map = L.map(target, { scrollWheelZoom: false }).setView([-8.8034, 115.2126], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);
            L.marker([-8.8034, 115.2126]).addTo(map).bindPopup('<strong>Oasis Hotel & Resort</strong><br>Nusa Dua, Bali.');
        });
    </script>
</x-guest-layout>
