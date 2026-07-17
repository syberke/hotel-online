<x-guest-layout>
    <div
        class="min-h-screen bg-slate-50 text-slate-900"
        x-data="{
            cart: JSON.parse(localStorage.getItem('oasis_restaurant_cart') || '[]'),
            showToast: false,
            toastMessage: '',
            init() { this.$watch('cart', value => localStorage.setItem('oasis_restaurant_cart', JSON.stringify(value))); },
            addItem(id, name, price, imageUrl) {
                const found = this.cart.find(item => item.id === id);
                if (found) found.quantity++;
                else this.cart.push({ id, title: name, price, image_url: imageUrl, quantity: 1, venue: 'Oasis Fine Dining' });
                this.cart = [...this.cart];
                this.toastMessage = name + ' added to your restaurant cart.';
                this.showToast = true;
                setTimeout(() => this.showToast = false, 2200);
            }
        }"
    >
        @include('layouts.navigation')

        <header class="relative isolate min-h-[540px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop" alt="Oasis Hotel restaurant" class="absolute inset-0 h-full w-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/78 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[540px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <nav class="flex items-center gap-2 text-xs font-medium text-slate-300"><a href="{{ route('home') }}" class="hover:text-white">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-200">Restaurant</span></nav>
                    <p class="mt-6 text-sm font-medium text-blue-300">Hotel dining</p>
                    <h1 class="mt-2 text-5xl font-semibold leading-tight tracking-tight sm:text-6xl">Good food, convenient ordering, and relaxed dining</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">Explore hotel venues and menu items, reserve a table, or add dishes to your guest-portal cart for an active stay.</p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="#menu-browsing-anchor" class="rounded-xl bg-blue-600 px-5 py-3.5 text-center text-sm font-semibold text-white hover:bg-blue-500">Browse menu</a>
                        <a href="#reservation-block" class="rounded-xl border border-white/15 bg-white/10 px-5 py-3.5 text-center text-sm font-semibold text-white backdrop-blur hover:bg-white/20">Reserve a table</a>
                    </div>
                </div>
            </div>
        </header>

        <section class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/10 sm:grid-cols-3 lg:grid-cols-6">
                @foreach([
                    ['fa-seedling', 'Fresh ingredients'],
                    ['fa-utensils', 'All-day dining'],
                    ['fa-umbrella-beach', 'Outdoor seating'],
                    ['fa-mug-hot', 'Breakfast options'],
                    ['fa-bell-concierge', 'Room delivery'],
                    ['fa-user-group', 'Group dining'],
                ] as [$icon, $label])
                    <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $icon }} text-sm"></i></span><p class="text-xs font-semibold text-slate-700">{{ $label }}</p></div>
                @endforeach
            </div>
        </section>

        <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div><p class="text-sm font-medium text-blue-600">Dining venues</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Choose the atmosphere that suits your plans</h2><p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500">From breakfast and casual poolside dining to dinner and private events.</p></div>
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-sm font-semibold text-blue-700"><i class="fa-solid fa-list"></i>{{ $totalMenuItems }} menu items</span>
            </div>

            @php
                $venues = [
                    ['Oasis Restaurant', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=900', '06:30 – 23:00', 'Main building', 'Breakfast, lunch, and dinner with international and local selections.'],
                    ['Rooftop Lounge', 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?q=80&w=900', '16:00 – 01:00', 'Level 8', 'Drinks, light bites, and evening views in an open-air setting.'],
                    ['Beach Grill', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=900', '11:00 – 22:00', 'Beachfront', 'Grilled seafood, meat, and relaxed outdoor dining near the beach.'],
                ];
            @endphp
            <div class="mt-8 grid grid-cols-1 gap-6 md:grid-cols-3">
                @foreach($venues as [$venueName, $venueImage, $venueHours, $venueLocation, $venueDescription])
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                        <div class="h-56 overflow-hidden bg-slate-100"><img src="{{ $venueImage }}" alt="{{ $venueName }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></div>
                        <div class="p-5"><h3 class="text-lg font-semibold text-slate-900">{{ $venueName }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $venueDescription }}</p><div class="mt-4 grid grid-cols-2 gap-3 rounded-xl bg-slate-50 p-3 text-xs text-slate-500"><div><p>Hours</p><p class="mt-1 font-semibold text-slate-800">{{ $venueHours }}</p></div><div><p>Location</p><p class="mt-1 font-semibold text-slate-800">{{ $venueLocation }}</p></div></div><a href="#reservation-block" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Reserve a table <i class="fa-solid fa-arrow-right text-xs"></i></a></div>
                    </article>
                @endforeach
            </div>
        </section>

        <section id="menu-browsing-anchor" class="border-y border-slate-200 bg-white py-20 scroll-mt-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div><p class="text-sm font-medium text-blue-600">Menu</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Browse dishes and drinks</h2></div>
                    <div class="flex max-w-full gap-2 overflow-x-auto rounded-xl bg-slate-50 p-1.5">
                        @foreach(['All Menu', 'Appetizers', 'Main Courses', 'Seafood', 'Steak Selection', 'Desserts'] as $category)
                            <a href="{{ route('restaurant', ['category' => $category, 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}#menu-browsing-anchor" class="min-w-max rounded-lg px-3.5 py-2 text-sm font-semibold transition {{ request('category', 'All Menu') === $category ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">{{ $category === 'All Menu' ? 'All' : $category }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 gap-8 lg:grid-cols-[260px_minmax(0,1fr)] lg:items-start">
                    <form action="{{ route('restaurant') }}" method="GET" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
                        <input type="hidden" name="category" value="{{ request('category', 'All Menu') }}">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4"><div><p class="text-xs text-slate-500">Refine menu</p><h3 class="mt-1 text-base font-semibold text-slate-900">Search & price</h3></div><a href="{{ route('restaurant') }}#menu-browsing-anchor" class="text-xs font-semibold text-blue-600">Reset</a></div>
                        <label class="mt-4 block"><span class="mb-2 block text-sm font-medium text-slate-700">Dish name</span><input type="search" name="search" value="{{ request('search') }}" placeholder="Search menu" class="w-full px-3 py-2.5 text-sm"></label>
                        <div class="mt-4 grid grid-cols-2 gap-3"><label class="block"><span class="mb-2 block text-xs font-medium text-slate-600">Minimum price</span><input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full px-3 py-2.5 text-sm"></label><label class="block"><span class="mb-2 block text-xs font-medium text-slate-600">Maximum price</span><input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="500000" class="w-full px-3 py-2.5 text-sm"></label></div>
                        <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-filter text-xs"></i>Apply filters</button>
                        <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500"><i class="fa-solid fa-bell-concierge mr-2 text-blue-500"></i>Guests with an active stay can order through the restaurant section in the guest portal.</div>
                    </form>

                    <section class="min-w-0">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            @forelse($culinaryMenus as $menu)
                                <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                                    <a href="{{ route('restaurant.detail', $menu->id) }}" class="block h-52 overflow-hidden bg-slate-100"><img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105"></a>
                                    <div class="p-5"><div class="flex items-start justify-between gap-4"><div class="min-w-0"><a href="{{ route('restaurant.detail', $menu->id) }}" class="text-lg font-semibold text-slate-900 hover:text-blue-700">{{ $menu->name }}</a><p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-500">{{ $menu->description }}</p></div><p class="shrink-0 text-sm font-semibold text-blue-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</p></div><div class="mt-5 grid grid-cols-2 gap-3"><a href="{{ route('restaurant.detail', $menu->id) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Details</a><button type="button" @click="addItem({{ $menu->id }}, @js($menu->name), {{ $menu->price }}, @js($menu->foto_url))" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-plus text-xs"></i>Add</button></div></div>
                                </article>
                            @empty
                                <div class="md:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-12 text-center"><i class="fa-solid fa-utensils text-2xl text-slate-300"></i><p class="mt-3 text-sm font-semibold text-slate-700">No menu items match these filters</p><p class="mt-1 text-sm text-slate-500">Change the search or price range.</p></div>
                            @endforelse
                        </div>
                        <x-catalog-pagination :paginator="$culinaryMenus" label="menu items" />
                    </section>
                </div>
            </div>
        </section>

        <section id="reservation-block" class="mx-auto max-w-5xl px-4 py-20 sm:px-6 lg:px-8 scroll-mt-20">
            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-[0.85fr_1.15fr]">
                    <div class="relative min-h-72 bg-slate-900"><img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?q=80&w=1000&auto=format&fit=crop" alt="Restaurant table" class="absolute inset-0 h-full w-full object-cover opacity-55"><div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/45 to-transparent"></div><div class="relative flex h-full flex-col justify-end p-7 text-white"><p class="text-sm font-medium text-blue-300">Table reservations</p><h2 class="mt-2 text-3xl font-semibold tracking-tight">Plan lunch or dinner before you arrive</h2><p class="mt-3 text-sm leading-6 text-slate-300">For groups, dietary requirements, or special occasions, include a note with the reservation.</p></div></div>
                    <div class="p-6 sm:p-8">
                        @auth
                            @if(auth()->user()->role === 'guest')
                                <form action="{{ route('facilities.book') }}" method="POST" id="restaurant-table-form" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    @csrf
                                    <input type="hidden" name="facility_name" value="Restaurant Table">
                                    <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Date</span><input type="date" name="booking_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 text-sm"></label>
                                    <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Time</span><select name="booking_time" required class="w-full px-3 py-2.5 text-sm"><option value="">Choose time</option><option value="12:00:00">12:00</option><option value="13:30:00">13:30</option><option value="18:00:00">18:00</option><option value="19:30:00">19:30</option><option value="21:00:00">21:00</option></select></label>
                                    <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Guests</span><input type="number" name="guests_count" min="1" max="20" value="2" required class="w-full px-3 py-2.5 text-sm"></label>
                                    <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Seating preference</span><select name="seating_preference" class="w-full px-3 py-2.5 text-sm"><option value="No Preference">No preference</option><option value="Full Ocean Window">Window table</option><option value="Outdoor Open Air">Outdoor table</option><option value="Isolated Intimate">Quiet area</option></select></label>
                                    <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Notes</span><textarea name="notes" rows="3" placeholder="Dietary requirement or special occasion" class="w-full px-3 py-2.5 text-sm"></textarea></label>
                                    <div id="table-alert-box" class="hidden rounded-xl p-4 text-sm sm:col-span-2"></div>
                                    <button type="submit" id="table-submit-btn" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 sm:col-span-2"><i class="fa-solid fa-calendar-check"></i>Reserve table</button>
                                </form>
                            @else
                                <div class="rounded-2xl bg-slate-50 p-8 text-center"><i class="fa-solid fa-user text-2xl text-slate-300"></i><p class="mt-3 text-sm font-semibold text-slate-800">Use a guest account to reserve a table</p><a href="{{ route('home') }}" class="mt-5 inline-flex rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">Back to hotel</a></div>
                            @endif
                        @else
                            <div class="rounded-2xl bg-slate-50 p-8 text-center"><i class="fa-solid fa-right-to-bracket text-2xl text-blue-500"></i><p class="mt-3 text-lg font-semibold text-slate-900">Sign in to reserve a table</p><p class="mt-2 text-sm text-slate-500">A guest account keeps your reservation and facility schedule together.</p><div class="mt-5 flex justify-center gap-3"><a href="{{ route('login') }}" class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white">Sign in</a><a href="{{ route('register') }}" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700">Register</a></div></div>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white"><div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center sm:px-6 lg:px-8"><p class="text-sm font-medium text-blue-300">Dining during your stay</p><h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Order through the guest portal when your room is active</h2><p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Your saved cart can be opened from Restaurant in the guest portal, where payment and order history are available.</p>@auth @if(auth()->user()->role === 'guest' && Route::has('guest.restaurant.orders'))<a href="{{ route('guest.restaurant.orders') }}" class="mt-7 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">Open restaurant portal</a>@endif @endauth</div></section>

        <div x-show="showToast" x-cloak x-transition class="fixed bottom-5 right-5 z-[100] flex max-w-sm items-center gap-3 rounded-xl bg-slate-900 px-4 py-3 text-sm font-medium text-white shadow-2xl"><span class="grid h-8 w-8 place-items-center rounded-lg bg-emerald-500/15 text-emerald-300"><i class="fa-solid fa-check text-xs"></i></span><span x-text="toastMessage"></span></div>

        @include('layouts.footer')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('restaurant-table-form');
            const button = document.getElementById('table-submit-btn');
            const alertBox = document.getElementById('table-alert-box');
            if (!form || !button || !alertBox) return;

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                button.disabled = true;
                button.innerHTML = '<i class="fa-solid fa-circle-notch animate-spin"></i>Saving reservation...';
                alertBox.className = 'hidden rounded-xl p-4 text-sm sm:col-span-2';

                try {
                    const response = await fetch(form.action, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: new FormData(form) });
                    const payload = await response.json();
                    if (!response.ok || !payload.success) throw new Error(payload.message || 'Table reservation could not be saved.');
                    alertBox.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 sm:col-span-2';
                    alertBox.textContent = payload.message || 'Table reservation saved.';
                    form.reset();
                } catch (error) {
                    alertBox.className = 'rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 sm:col-span-2';
                    alertBox.textContent = error.message || 'Table reservation could not be saved.';
                } finally {
                    button.disabled = false;
                    button.innerHTML = '<i class="fa-solid fa-calendar-check"></i>Reserve table';
                }
            });
        });
    </script>
</x-guest-layout>
