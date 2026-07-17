<x-guest-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[520px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel rooms" class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/75 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[520px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <nav class="flex items-center gap-2 text-xs font-medium text-slate-300">
                        <a href="{{ route('home') }}" class="hover:text-white">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-200">Rooms</span>
                    </nav>
                    <p class="mt-6 text-sm font-medium text-blue-300">Room catalog</p>
                    <h1 class="mt-2 text-5xl font-semibold leading-tight tracking-tight sm:text-6xl">Find a comfortable room for your trip</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">Compare room types, live availability, and nightly prices. Choose dates and guest details to narrow the results.</p>
                </div>
            </div>
        </header>

        <form id="room-filter-form" action="{{ route('rooms') }}" method="GET">
            <section id="booking-bar" class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-xl shadow-slate-900/10 md:grid-cols-2 xl:grid-cols-[1fr_1fr_1fr_1.25fr_auto] xl:items-end">
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Check-in</span>
                        <input type="date" name="check_in" value="{{ request('check_in', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full px-4 py-3 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Check-out</span>
                        <input type="date" name="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full px-4 py-3 text-sm">
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Guests</span>
                        <select name="guests" class="w-full px-4 py-3 text-sm">
                            @foreach(['1 Adult', '2 Adults, 1 Room', '4 Guests, 2 Rooms', '6 Guests, 3 Rooms'] as $guestOption)
                                <option value="{{ $guestOption }}" {{ request('guests', '2 Adults, 1 Room') === $guestOption ? 'selected' : '' }}>{{ $guestOption }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="block">
                        <span class="mb-2 block text-sm font-medium text-slate-700">Room type</span>
                        <select name="suite_type" class="w-full px-4 py-3 text-sm">
                            <option value="All Room Types">All room types</option>
                            @foreach($allCategories as $category)
                                <option value="{{ $category->name }}" {{ request('suite_type') === $category->name ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                        Search rooms <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </button>
                </div>
            </section>

            <main class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="mb-7 flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3 text-sm text-slate-500">
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 shadow-sm"><i class="fa-solid fa-bed text-blue-600"></i><strong class="text-slate-900">{{ $totalInventoryReady }}</strong> rooms currently available</span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-blue-700"><i class="fa-solid fa-shield-halved"></i>Live inventory</span>
                    </div>
                    <label class="flex items-center gap-3 text-sm font-medium text-slate-600">
                        Sort
                        <select name="sort" onchange="this.form.submit()" class="min-w-44 px-3 py-2 text-sm">
                            <option value="Recommended" {{ request('sort', 'Recommended') === 'Recommended' ? 'selected' : '' }}>Recommended</option>
                            <option value="Lowest Price" {{ request('sort') === 'Lowest Price' ? 'selected' : '' }}>Lowest price</option>
                            <option value="Highest Price" {{ request('sort') === 'Highest Price' ? 'selected' : '' }}>Highest price</option>
                        </select>
                    </label>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-[260px_minmax(0,1fr)] lg:items-start">
                    <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div><p class="text-xs font-medium text-slate-500">Refine results</p><h2 class="mt-1 text-base font-semibold text-slate-900">Room categories</h2></div>
                            <a href="{{ route('rooms') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">Clear</a>
                        </div>
                        <div class="mt-4 space-y-2">
                            @foreach($allCategories as $category)
                                <label class="flex cursor-pointer items-center gap-3 rounded-xl px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                                    <input type="checkbox" name="categories[]" value="{{ $category->name }}" onchange="this.form.submit()" {{ is_array(request('categories')) && in_array($category->name, request('categories'), true) ? 'checked' : '' }} class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-5 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500">
                            <i class="fa-solid fa-circle-info mr-2 text-blue-500"></i>Availability can change until a reservation is completed.
                        </div>
                    </aside>

                    <section id="room-results" class="min-w-0 scroll-mt-24">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @forelse($roomsLiveList as $room)
                                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                                    <div class="relative h-60 overflow-hidden bg-slate-100">
                                        <img src="{{ $room->foto_url }}" alt="{{ $room->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-xs font-semibold shadow-sm {{ $room->available_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                            {{ $room->available_count > 0 ? $room->available_count . ' available' : 'Fully booked' }}
                                        </span>
                                    </div>
                                    <div class="p-5 sm:p-6">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="min-w-0"><h2 class="text-xl font-semibold text-slate-900">{{ $room->name }}</h2><p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">{{ $room->description }}</p></div>
                                            <p class="shrink-0 text-right text-lg font-semibold text-blue-700">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}<span class="block text-xs font-normal text-slate-400">per night</span></p>
                                        </div>
                                        <div class="mt-5 grid grid-cols-2 gap-3">
                                            <a href="{{ route('rooms.show', $room->id) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">View details</a>
                                            @if($room->available_count > 0)
                                                <a href="{{ route('rooms.show', $room->id) }}?check_in={{ request('check_in', date('Y-m-d')) }}&check_out={{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}&guests={{ urlencode(request('guests', '2 Adults, 1 Room')) }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700">Book now</a>
                                            @else
                                                <button type="button" disabled class="cursor-not-allowed rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-400">Unavailable</button>
                                            @endif
                                        </div>
                                    </div>
                                </article>
                            @empty
                                <div class="md:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center">
                                    <i class="fa-solid fa-bed text-2xl text-slate-300"></i><p class="mt-3 text-sm font-semibold text-slate-700">No rooms match these filters</p><p class="mt-1 text-sm text-slate-500">Change the dates or room category and try again.</p>
                                </div>
                            @endforelse
                        </div>
                        <x-catalog-pagination :paginator="$roomsLiveList" label="rooms" />
                    </section>
                </div>
            </main>
        </form>

        <section class="border-y border-slate-200 bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div><p class="text-sm font-medium text-blue-600">Quick comparison</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Compare room prices and availability</h2></div>
                    <a href="#booking-bar" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">Change search <i class="fa-solid fa-arrow-up text-xs"></i></a>
                </div>
                <div class="mt-8 overflow-x-auto rounded-2xl border border-slate-200 bg-white">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-slate-50 text-slate-600"><tr><th class="px-5 py-4 font-semibold">Room type</th><th class="px-5 py-4 font-semibold">Nightly price</th><th class="px-5 py-4 font-semibold">Availability</th></tr></thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($roomsComparison as $room)
                                <tr class="hover:bg-slate-50"><td class="px-5 py-4 font-semibold text-slate-900">{{ $room->name }}</td><td class="px-5 py-4 font-semibold text-blue-700">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}</td><td class="px-5 py-4"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $room->available_count > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ $room->available_count }} available</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach([
                    ['Flexible planning', 'Review room availability and dates before completing a reservation.', 'fa-calendar-check'],
                    ['Guest portal access', 'Keep booking history, receipts, and stay services organized in your account.', 'fa-mobile-screen-button'],
                    ['Hotel assistance', 'Contact the hotel team when you need help choosing a room or planning a stay.', 'fa-headset'],
                ] as [$title, $description, $icon])
                    <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p></article>
                @endforeach
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white">
            <div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center sm:px-6 lg:px-8">
                <p class="text-sm font-medium text-blue-300">Ready to choose?</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Find the room that works for your stay</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Use the search above to check dates, compare options, and continue to reservation details.</p>
                <div class="mt-7 flex flex-col gap-3 sm:flex-row"><a href="#booking-bar" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">Search availability</a><a href="{{ route('contact') }}" class="rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20">Contact the hotel</a></div>
            </div>
        </section>

        @include('layouts.footer')
    </div>
</x-guest-layout>
