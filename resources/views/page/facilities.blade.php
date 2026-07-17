<x-guest-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900" x-data="{ activeFilter: 'all', bookingOpen: false, selectedFacility: '', submitting: false }">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[540px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel facilities" class="absolute inset-0 h-full w-full object-cover opacity-55">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/75 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[540px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <nav class="flex items-center gap-2 text-xs font-medium text-slate-300"><a href="{{ route('home') }}" class="hover:text-white">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-200">Facilities</span></nav>
                    <p class="mt-6 text-sm font-medium text-blue-300">Hotel facilities</p>
                    <h1 class="mt-2 text-5xl font-semibold leading-tight tracking-tight sm:text-6xl">Make more of your time at the hotel</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">Relax, exercise, spend time with family, or reserve a venue. Check opening hours and access information before your visit.</p>
                </div>
            </div>
        </header>

        <section class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/10 sm:grid-cols-3 lg:grid-cols-6">
                @foreach([
                    ['fa-headset', 'Guest support'],
                    ['fa-car-side', 'Airport transfer'],
                    ['fa-wifi', 'Hotel Wi-Fi'],
                    ['fa-spa', 'Wellness'],
                    ['fa-dumbbell', 'Fitness'],
                    ['fa-umbrella-beach', 'Pool & beach'],
                ] as [$icon, $label])
                    <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $icon }} text-sm"></i></span><p class="text-xs font-semibold text-slate-700">{{ $label }}</p></div>
                @endforeach
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div><p class="text-sm font-medium text-blue-600">Browse facilities</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Find an activity that fits your stay</h2><p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">Use the categories to quickly find wellness, dining, recreation, business, and family facilities.</p></div>
                <div class="flex max-w-full gap-2 overflow-x-auto rounded-xl bg-white p-1.5 shadow-sm">
                    @foreach([
                        ['all', 'All'],
                        ['wellness', 'Wellness'],
                        ['dining', 'Dining'],
                        ['recreation', 'Recreation'],
                        ['business', 'Business'],
                        ['family', 'Family'],
                    ] as [$key, $label])
                        <button type="button" @click="activeFilter = '{{ $key }}'" :class="activeFilter === '{{ $key }}' ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'" class="min-w-max rounded-lg px-3.5 py-2 text-sm font-semibold transition">{{ $label }}</button>
                    @endforeach
                </div>
            </div>

            <div class="mt-9 grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-3">
                @forelse($facilities as $item)
                    @php $facilityCategory = Str::lower($item->category ?? 'recreation'); @endphp
                    <article x-show="activeFilter === 'all' || @js($facilityCategory).includes(activeFilter)" x-transition.opacity class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="relative h-56 overflow-hidden bg-slate-100">
                            <img src="{{ $item->image_url ?? 'https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=900&auto=format&fit=crop' }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <span class="absolute left-3 top-3 rounded-full bg-white/90 px-2.5 py-1 text-xs font-semibold text-slate-700 shadow-sm backdrop-blur">{{ $item->category ?? 'Hotel facility' }}</span>
                            <span class="absolute right-3 top-3 rounded-full bg-slate-950/70 px-2.5 py-1 text-xs font-medium text-white backdrop-blur">{{ $item->access_type ?? 'Guest access' }}</span>
                        </div>
                        <div class="p-5 sm:p-6">
                            <h3 class="text-xl font-semibold text-slate-900">{{ $item->name }}</h3>
                            <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500">{{ $item->description }}</p>
                            <div class="mt-4 flex items-center gap-2 rounded-xl bg-slate-50 p-3 text-sm text-slate-600"><span class="grid h-8 w-8 place-items-center rounded-lg bg-white text-blue-600 shadow-sm"><i class="fa-regular fa-clock text-xs"></i></span><span><strong class="font-semibold text-slate-800">Opening hours:</strong> {{ $item->hours }}</span></div>

                            <div class="mt-5">
                                @if($item->requires_booking)
                                    @auth
                                        @if(auth()->user()->role === 'guest')
                                            <button type="button" @click="selectedFacility = @js($item->name); bookingOpen = true" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-calendar-plus text-xs"></i>Reserve a slot</button>
                                        @else
                                            <div class="rounded-xl bg-slate-50 p-3 text-center text-sm text-slate-500">Use a guest account to reserve this facility.</div>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-right-to-bracket text-xs"></i>Sign in to reserve</a>
                                    @endauth
                                @else
                                    <div class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700"><i class="fa-solid fa-person-walking"></i>Walk-in access</div>
                                @endif
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center"><i class="fa-solid fa-spa text-2xl text-slate-300"></i><p class="mt-3 text-sm font-semibold text-slate-700">No facilities are available</p><p class="mt-1 text-sm text-slate-500">Facility information will appear here when it is added.</p></div>
                @endforelse
            </div>
        </main>

        <section class="border-y border-slate-200 bg-white py-20">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
                <div class="overflow-hidden rounded-2xl bg-slate-100"><img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=1200&auto=format&fit=crop" alt="Wellness activities" class="h-[420px] w-full object-cover"></div>
                <div><p class="text-sm font-medium text-blue-600">Wellness & recreation</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Build a balanced day around your stay</h2><p class="mt-4 text-sm leading-7 text-slate-500">Combine pool time, fitness, spa appointments, and other activities based on your schedule. Facilities that require a booking can be managed from the guest portal.</p><div class="mt-6 grid grid-cols-2 gap-3">@foreach(['Spa appointments', 'Fitness access', 'Pool and beach', 'Family activities'] as $benefit)<div class="flex items-center gap-2 rounded-xl bg-slate-50 p-3 text-sm text-slate-700"><i class="fa-solid fa-check text-emerald-600"></i>{{ $benefit }}</div>@endforeach</div></div>
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['fa-clock', 'Check opening hours', 'Each facility has its own operating schedule.'],
                    ['fa-calendar-check', 'Reserve when required', 'Book a time slot for selected facilities.'],
                    ['fa-user-group', 'Plan for your group', 'Add guest count and special notes when booking.'],
                    ['fa-headset', 'Ask for assistance', 'Contact the hotel team for accessibility or event requests.'],
                ] as [$icon, $title, $description])
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h3 class="mt-4 text-base font-semibold text-slate-900">{{ $title }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p></article>
                @endforeach
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white">
            <div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center sm:px-6 lg:px-8"><p class="text-sm font-medium text-blue-300">Plan your stay</p><h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Choose a room and enjoy the hotel facilities</h2><p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Book a stay first, then use the guest portal to manage facility reservations and view your schedule.</p><div class="mt-7 flex flex-col gap-3 sm:flex-row"><a href="{{ route('rooms') }}" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">Browse rooms</a><a href="{{ route('contact') }}" class="rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white hover:bg-white/20">Contact the hotel</a></div></div>
        </section>

        @include('layouts.footer')

        @auth
            @if(auth()->user()->role === 'guest')
                <div x-show="bookingOpen" x-transition.opacity x-cloak class="fixed inset-0 z-[110] flex items-center justify-center overflow-y-auto p-4 sm:p-6" @keyup.escape.window="bookingOpen = false">
                    <div class="fixed inset-0 bg-slate-950/65 backdrop-blur-sm" @click="bookingOpen = false"></div>
                    <section class="relative my-auto w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-6 shadow-2xl sm:p-7">
                        <header class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-sm font-medium text-blue-600">Facility reservation</p><h3 class="mt-1 text-xl font-semibold text-slate-900" x-text="selectedFacility"></h3></div><button type="button" @click="bookingOpen = false" class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-slate-700"><i class="fa-solid fa-xmark"></i></button></header>

                        <form id="facility-public-booking-form" action="{{ route('facilities.book') }}" method="POST" class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2" @submit.prevent="
                            submitting = true;
                            fetch($el.action, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: new FormData($el) })
                                .then(async response => ({ response, payload: await response.json() }))
                                .then(({ response, payload }) => {
                                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Reservation could not be saved.');
                                    bookingOpen = false;
                                    window.OasisDialog?.success(payload.message || 'Facility reservation saved.').then(() => window.location.href = '{{ route('guest.facilities.booking') }}');
                                })
                                .catch(error => window.OasisDialog?.error(error.message || 'Reservation could not be saved.'))
                                .finally(() => submitting = false)
                        ">
                            @csrf
                            <input type="hidden" name="facility_name" :value="selectedFacility">
                            <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Date</span><input type="date" name="booking_date" required min="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 text-sm"></label>
                            <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Time</span><select name="booking_time" required class="w-full px-3 py-2.5 text-sm"><option value="09:00">09:00</option><option value="11:00">11:00</option><option value="14:00">14:00</option><option value="16:00">16:00</option><option value="19:00">19:00</option></select></label>
                            <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Guests</span><input type="number" name="guests_count" min="1" max="10" value="1" required class="w-full px-3 py-2.5 text-sm"></label>
                            <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Preference</span><select name="seating_preference" class="w-full px-3 py-2.5 text-sm"><option value="No Preference">No preference</option><option value="Ocean View Deck">Ocean view</option><option value="VIP Private Corner">Private area</option></select></label>
                            <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Notes</span><textarea name="notes" rows="3" placeholder="Accessibility or special request" class="w-full px-3 py-2.5 text-sm"></textarea></label>
                            <button type="submit" :disabled="submitting" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-wait disabled:opacity-60 sm:col-span-2"><i class="fa-solid" :class="submitting ? 'fa-circle-notch animate-spin' : 'fa-calendar-check'"></i><span x-text="submitting ? 'Saving reservation...' : 'Confirm reservation'"></span></button>
                        </form>
                    </section>
                </div>
            @endif
        @endauth
    </div>
</x-guest-layout>
