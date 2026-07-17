<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[640px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel in Nusa Dua" class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/75 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[640px] max-w-7xl items-center px-4 py-24 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium text-blue-100 backdrop-blur"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Comfortable stays in Nusa Dua, Bali</span>
                    <h1 class="mt-6 text-5xl font-semibold leading-[1.02] tracking-tight sm:text-6xl lg:text-7xl">A simpler way to enjoy your hotel stay.</h1>
                    <p class="mt-6 max-w-2xl text-base leading-7 text-slate-200 sm:text-lg">Explore rooms, restaurant venues, facilities, and connected guest services without an unnecessary availability form on the home page.</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row"><a href="{{ route('rooms') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-950/30 transition hover:bg-blue-500">Browse rooms <i class="fa-solid fa-arrow-right text-xs"></i></a><a href="{{ route('restaurant') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-5 py-3.5 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/20">Explore dining <i class="fa-solid fa-utensils text-xs"></i></a></div>
                </div>
            </div>
        </header>

        @if(session('success') || session('error') || session('info'))
            <section class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8">
                @foreach(['success' => 'emerald', 'error' => 'rose', 'info' => 'blue'] as $flash => $tone)
                    @if(session($flash))<div class="rounded-2xl border border-{{ $tone }}-200 bg-{{ $tone }}-50 p-4 text-sm text-{{ $tone }}-800">{{ session($flash) }}</div>@endif
                @endforeach
            </section>
        @endif

        <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach([
                    ['fa-headset', 'Helpful service', 'Guest support is available for reservations, dining, facilities, and in-house requests.'],
                    ['fa-location-dot', 'Convenient location', 'Stay in Nusa Dua with practical access to beaches, attractions, and the airport.'],
                    ['fa-mobile-screen-button', 'Connected guest portal', 'Manage bookings, receipts, restaurant orders, and facilities from your account.'],
                ] as [$icon, $title, $description])
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h2 class="mt-5 text-lg font-semibold text-slate-900">{{ $title }}</h2><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p></article>
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 pb-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-medium text-blue-600">Rooms</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Choose the stay that fits your trip</h2><p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">Room inventory and prices below are loaded from the database.</p></div><a href="{{ route('rooms') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600">View all rooms <i class="fa-solid fa-arrow-right text-xs"></i></a></div>
            <div class="mt-8 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4">
                @forelse($roomsLiveList->take(4) as $room)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md"><div class="relative h-52 overflow-hidden bg-slate-100"><img src="{{ $room->foto_url }}" alt="{{ $room->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"><span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm {{ $room->available_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ $room->available_count > 0 ? $room->available_count . ' available' : 'Fully booked' }}</span></div><div class="p-5"><h3 class="text-lg font-semibold text-slate-900">{{ $room->name }}</h3><p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $room->description }}</p><div class="mt-5 flex items-end justify-between gap-4"><div><p class="text-xs text-slate-500">From</p><p class="mt-1 text-lg font-semibold text-blue-700">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}<span class="text-xs font-normal text-slate-400"> / night</span></p></div><a href="{{ route('rooms.show', $room->id) }}" class="inline-flex h-11 items-center justify-center rounded-xl bg-blue-600 px-4 text-sm font-semibold text-white">Details</a></div></div></article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Room information is not available yet.</div>
                @endforelse
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"><div class="grid grid-cols-1 gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-center"><div><p class="text-sm font-medium text-blue-300">Hotel services</p><h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Dining and facilities connected to the same guest account</h2><p class="mt-4 max-w-xl text-sm leading-7 text-slate-300">Restaurant orders, table reservations, facility bookings, and receipts are available through the hotel and guest portals.</p><div class="mt-7 flex flex-wrap gap-3"><a href="{{ route('restaurant') }}" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white">Restaurant</a><a href="{{ route('facilities') }}" class="rounded-xl border border-white/15 px-5 py-3 text-sm font-semibold text-white">Facilities</a></div></div><div class="grid grid-cols-1 gap-4 sm:grid-cols-3">@foreach([['fa-utensils','Restaurant','Database-backed venues and menus.'],['fa-spa','Facilities','Bookable hotel activities.'],['fa-receipt','Receipts','Access completed stay and order records.']] as [$icon,$title,$description])<article class="rounded-2xl border border-white/10 bg-white/5 p-5"><i class="fa-solid {{ $icon }} text-blue-300"></i><h3 class="mt-4 font-semibold">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-400">{{ $description }}</p></article>@endforeach</div></div></div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm font-medium text-blue-600">Dining</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Popular menu items</h2><p class="mt-3 text-sm text-slate-500">These items are loaded from <code>restaurant_menus</code>.</p></div><a href="{{ route('restaurant') }}" class="text-sm font-semibold text-blue-600">Browse full menu</a></div>
            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @forelse($culinaryMenus->take(3) as $menu)
                    <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="h-56 bg-slate-100"><img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="h-full w-full object-cover"></div><div class="p-5"><div class="flex items-start justify-between gap-4"><h3 class="font-semibold text-slate-900">{{ $menu->name }}</h3><p class="text-sm font-semibold text-blue-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</p></div><p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $menu->description }}</p><a href="{{ route('restaurant.detail', $menu->id) }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-600">View details <i class="fa-solid fa-arrow-right text-xs"></i></a></div></article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center text-sm text-slate-500">Restaurant menu is not available yet.</div>
                @endforelse
            </div>
        </section>

        <section class="border-y border-slate-200 bg-white py-20"><div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8"><div><p class="text-sm font-medium text-blue-600">Location</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Find the hotel and contact the team</h2><p class="mt-4 text-sm leading-7 text-slate-500">{{ config('hotel.address') }}</p><a href="{{ route('contact') }}" class="mt-7 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 shadow-sm">Contact the hotel <i class="fa-solid fa-arrow-right text-xs"></i></a></div><div id="liveOasisMap" class="h-96 w-full overflow-hidden rounded-2xl border border-slate-200 bg-slate-100 shadow-sm"></div></div></section>

        @include('layouts.footer')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const target = document.getElementById('liveOasisMap');
            if (!target || typeof L === 'undefined') return;
            const latitude = @json(config('hotel.latitude'));
            const longitude = @json(config('hotel.longitude'));
            const map = L.map(target, { scrollWheelZoom: false }).setView([latitude, longitude], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
            L.marker([latitude, longitude]).addTo(map).bindPopup('<strong>Oasis Hotel & Resort</strong><br>{{ e(config('hotel.address')) }}');
        });
    </script>
</x-guest-layout>
