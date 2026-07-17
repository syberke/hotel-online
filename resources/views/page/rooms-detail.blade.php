<x-guest-layout>
    @php
        $allImages = $room->foto_url ? array_filter(array_map('trim', explode(',', $room->foto_url))) : [];
        $images = array_values(array_slice($allImages, 0, 5));
        $mainImage = $images[0] ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop';
        $amenities = filled($room->amenities ?? null)
            ? array_filter(array_map('trim', explode(',', $room->amenities)))
            : ['High-speed Wi-Fi', 'Air conditioning', 'Smart TV', 'Daily housekeeping', 'In-room safe', 'Coffee and tea'];
    @endphp

    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                    <a href="{{ route('home') }}" class="hover:text-slate-900">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i>
                    <a href="{{ route('rooms') }}" class="hover:text-slate-900">Rooms</a><i class="fa-solid fa-chevron-right text-[9px]"></i>
                    <span class="text-blue-600">{{ $room->name }}</span>
                </nav>
                <a href="{{ route('rooms') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-900"><i class="fa-solid fa-arrow-left text-xs"></i>Back to rooms</a>
            </div>

            <section class="grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1fr)_390px] lg:items-start">
                <div class="min-w-0 space-y-8">
                    <div>
                        <div class="relative h-[420px] overflow-hidden rounded-2xl bg-slate-900 shadow-sm sm:h-[520px]">
                            <img id="room-main-display" src="{{ $mainImage }}" alt="{{ $room->name }}" class="h-full w-full object-cover transition-opacity duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/50 via-transparent to-transparent"></div>
                            <div class="absolute bottom-5 left-5 right-5 flex items-end justify-between gap-4 text-white">
                                <div><span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-medium backdrop-blur">Oasis Hotel room</span><h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">{{ $room->name }}</h1></div>
                                <span class="hidden rounded-full px-3 py-1.5 text-xs font-semibold shadow-sm sm:inline-flex {{ ($room->available_count ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ ($room->available_count ?? 0) > 0 ? ($room->available_count . ' available') : 'Fully booked' }}</span>
                            </div>
                        </div>

                        @if(count($images) > 1)
                            <div class="mt-4 flex gap-3 overflow-x-auto pb-1">
                                @foreach($images as $index => $imageUrl)
                                    <button type="button" onclick="switchMainImage(@js($imageUrl), this)" class="thumbnail-container h-24 w-36 shrink-0 overflow-hidden rounded-xl border-2 {{ $index === 0 ? 'border-blue-500' : 'border-transparent' }} bg-slate-100 transition hover:border-blue-300" aria-label="Show room image {{ $index + 1 }}">
                                        <img src="{{ $imageUrl }}" alt="{{ $room->name }} image {{ $index + 1 }}" class="h-full w-full object-cover">
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <p class="text-sm font-medium text-blue-600">Room overview</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">About this room</h2>
                        <p class="mt-4 text-sm leading-7 text-slate-500">{{ $room->description ?? 'A comfortable hotel room with practical amenities and space for a relaxing stay.' }}</p>

                        <div class="mt-7 grid grid-cols-2 gap-4 border-t border-slate-100 pt-6 sm:grid-cols-4">
                            @foreach([
                                ['fa-expand', 'Room size', $room->room_size ?? '45 m²'],
                                ['fa-bed', 'Bed', $room->bed_configuration ?? '1 King Bed'],
                                ['fa-mountain-sun', 'View', $room->view_perspective ?? 'Hotel view'],
                                ['fa-users', 'Capacity', ($room->max_capacity ?? 2) . ' guests'],
                            ] as [$icon, $label, $value])
                                <div class="rounded-xl bg-slate-50 p-4"><span class="grid h-9 w-9 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid {{ $icon }} text-sm"></i></span><p class="mt-3 text-xs text-slate-500">{{ $label }}</p><p class="mt-1 text-sm font-semibold text-slate-900">{{ $value }}</p></div>
                            @endforeach
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <p class="text-sm font-medium text-blue-600">Included with the room</p>
                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900">Amenities</h2>
                        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach($amenities as $amenity)
                                <div class="flex items-center gap-3 rounded-xl bg-slate-50 p-3 text-sm text-slate-700"><span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white text-emerald-600 shadow-sm"><i class="fa-solid fa-check text-xs"></i></span>{{ $amenity }}</div>
                            @endforeach
                        </div>
                    </section>

                    <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        @foreach([
                            ['fa-clock', 'Check-in', 'From 3:00 PM'],
                            ['fa-door-open', 'Check-out', 'By 12:00 PM'],
                            ['fa-headset', 'Need help?', 'Contact hotel support'],
                        ] as [$icon, $title, $description])
                            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h3 class="mt-4 text-sm font-semibold text-slate-900">{{ $title }}</h3><p class="mt-1 text-sm text-slate-500">{{ $description }}</p></article>
                        @endforeach
                    </section>
                </div>

                <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 lg:sticky lg:top-24">
                    <div class="flex items-end justify-between gap-4 border-b border-slate-100 pb-5">
                        <div><p class="text-xs text-slate-500">Nightly rate</p><p class="mt-1 text-2xl font-semibold text-blue-700">Rp {{ number_format($room->price, 0, ',', '.') }}</p></div>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ ($room->available_count ?? 0) > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">{{ ($room->available_count ?? 0) > 0 ? $room->available_count . ' available' : 'Unavailable' }}</span>
                    </div>

                    <form id="instant-booking-form" action="{{ route('rooms.check') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <input type="hidden" name="suite_type" value="{{ $room->name }}">
                        <input type="hidden" id="selected-room-id-input" name="room_id">

                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Check-in</span><input type="date" id="check_in" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ request('check_in', date('Y-m-d')) }}" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Check-out</span><input type="date" id="check_out" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Guests</span><select id="guests-select" name="guests" class="w-full px-4 py-3 text-sm">@for($i = 1; $i <= ($room->max_capacity ?? 2); $i++)<option value="{{ $i }} {{ $i > 1 ? 'Adults' : 'Adult' }}" {{ $i === min(2, ($room->max_capacity ?? 2)) ? 'selected' : '' }}>{{ $i }} guest{{ $i > 1 ? 's' : '' }}</option>@endfor</select></label>

                        @auth
                            @if(auth()->user()->role === 'guest' && !$isProfileComplete)
                                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">Complete your identity number, phone, and address in <a href="{{ route('profile.edit') }}" class="font-semibold underline">Profile Settings</a> before reserving.</div>
                            @elseif(auth()->user()->role !== 'guest')
                                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-900">Online reservations are available through a guest account.</div>
                            @endif
                        @else
                            <div class="rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500">You can check availability now. Sign in or create a guest account to complete the reservation.</div>
                        @endauth

                        <div id="validation-message-box" class="hidden rounded-xl p-4 text-sm"></div>

                        <button type="button" id="submit-booking-btn" data-state="check" {{ ($room->available_count ?? 0) <= 0 ? 'disabled' : '' }} class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i><span>{{ ($room->available_count ?? 0) > 0 ? 'Check availability' : 'Currently unavailable' }}</span>
                        </button>
                    </form>

                    <div class="mt-5 border-t border-slate-100 pt-5 text-sm text-slate-500"><p class="flex items-start gap-2"><i class="fa-solid fa-shield-halved mt-1 text-blue-500"></i><span>Availability is checked against live booking data before the room is reserved.</span></p></div>
                </aside>
            </section>
        </main>

        @include('layouts.footer')
    </div>

    <script>
        function switchMainImage(imageUrl, thumbnailElement) {
            const mainDisplay = document.getElementById('room-main-display');
            if (!mainDisplay) return;
            mainDisplay.style.opacity = '0.35';
            window.setTimeout(() => {
                mainDisplay.src = imageUrl;
                mainDisplay.style.opacity = '1';
            }, 160);
            document.querySelectorAll('.thumbnail-container').forEach((element) => {
                element.classList.remove('border-blue-500');
                element.classList.add('border-transparent');
            });
            thumbnailElement.classList.remove('border-transparent');
            thumbnailElement.classList.add('border-blue-500');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('instant-booking-form');
            const button = document.getElementById('submit-booking-btn');
            const messageBox = document.getElementById('validation-message-box');
            const checkInInput = document.getElementById('check_in');
            const checkOutInput = document.getElementById('check_out');
            const guestsSelect = document.getElementById('guests-select');
            const roomIdInput = document.getElementById('selected-room-id-input');

            if (!form || !button || button.disabled) return;

            const resetButton = () => {
                button.disabled = false;
                button.dataset.state = 'check';
                button.className = 'inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60';
                button.innerHTML = '<i class="fa-solid fa-magnifying-glass text-xs"></i><span>Check availability</span>';
                messageBox.className = 'hidden rounded-xl p-4 text-sm';
                if (roomIdInput) roomIdInput.value = '';
            };

            [checkInInput, checkOutInput, guestsSelect].forEach((input) => input?.addEventListener('change', resetButton));

            const requestJson = async (formData) => {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                    body: formData
                });
                const payload = await response.json();
                return { response, payload };
            };

            button.addEventListener('click', async () => {
                const state = button.dataset.state;
                button.disabled = true;
                button.innerHTML = `<i class="fa-solid fa-circle-notch animate-spin"></i><span>${state === 'check' ? 'Checking availability...' : 'Creating reservation...'}</span>`;

                try {
                    const formData = new FormData(form);
                    if (state === 'check') formData.append('mode_check_only', '1');
                    const { response, payload } = await requestJson(formData);

                    if (payload.redirect) {
                        if (payload.message && window.OasisDialog) {
                            await window.OasisDialog.info(payload.message);
                        }
                        window.location.href = payload.redirect;
                        return;
                    }

                    if (state === 'check') {
                        button.disabled = false;
                        messageBox.classList.remove('hidden');
                        if (payload.available) {
                            button.dataset.state = 'book';
                            button.className = 'inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3.5 text-sm font-semibold text-white transition hover:bg-emerald-700';
                            button.innerHTML = '<i class="fa-solid fa-calendar-check"></i><span>Reserve this room</span>';
                            messageBox.className = 'rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800';
                            messageBox.textContent = payload.message || 'The room is available for these dates.';
                            if (roomIdInput && payload.room_id) roomIdInput.value = payload.room_id;
                        } else {
                            resetButton();
                            messageBox.className = 'rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800';
                            messageBox.textContent = payload.message || 'No room is available for these dates.';
                        }
                        return;
                    }

                    if (!response.ok || !payload.success) {
                        throw new Error(payload.message || 'The reservation could not be completed.');
                    }
                    window.location.href = payload.redirect || '{{ route('guest.bookings.my') }}';
                } catch (error) {
                    resetButton();
                    messageBox.className = 'rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800';
                    messageBox.textContent = error.message || 'The request could not be processed.';
                }
            });
        });
    </script>
</x-guest-layout>
