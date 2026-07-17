<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[480px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel" class="absolute inset-0 h-full w-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/78 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[480px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white"><nav class="flex items-center gap-2 text-xs font-medium text-slate-300"><a href="{{ route('home') }}" class="hover:text-white">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-200">Contact</span></nav><p class="mt-6 text-sm font-medium text-blue-300">Guest assistance</p><h1 class="mt-2 text-5xl font-semibold leading-tight tracking-tight sm:text-6xl">Send a real message to the hotel team</h1><p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">Every submission is saved in the hotel Contact Inbox for Admin and Manager review.</p></div>
            </div>
        </header>

        @if(session('success'))
            <section class="mx-auto max-w-7xl px-4 pt-6 sm:px-6 lg:px-8"><div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800"><i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}</div></section>
        @endif

        <section class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/10 sm:grid-cols-2 lg:grid-cols-4">
                @if(config('hotel.phone'))<a href="tel:{{ preg_replace('/\s+/', '', config('hotel.phone')) }}" class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 transition hover:bg-blue-50"><span class="grid h-11 w-11 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-phone"></i></span><span><span class="block text-xs text-slate-500">Phone</span><strong class="mt-1 block text-sm text-slate-900">{{ config('hotel.phone') }}</strong></span></a>@endif
                @if(config('hotel.email'))<a href="mailto:{{ config('hotel.email') }}" class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 transition hover:bg-blue-50"><span class="grid h-11 w-11 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-envelope"></i></span><span class="min-w-0"><span class="block text-xs text-slate-500">Email</span><strong class="mt-1 block truncate text-sm text-slate-900">{{ config('hotel.email') }}</strong></span></a>@endif
                @if(config('hotel.whatsapp'))<a href="https://wa.me/{{ preg_replace('/\D+/', '', config('hotel.whatsapp')) }}" target="_blank" rel="noopener" class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 transition hover:bg-blue-50"><span class="grid h-11 w-11 place-items-center rounded-xl bg-white text-emerald-600 shadow-sm"><i class="fa-brands fa-whatsapp"></i></span><span><span class="block text-xs text-slate-500">WhatsApp</span><strong class="mt-1 block text-sm text-slate-900">{{ config('hotel.whatsapp') }}</strong></span></a>@endif
                <a href="#map-block" class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 transition hover:bg-blue-50"><span class="grid h-11 w-11 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-location-dot"></i></span><span><span class="block text-xs text-slate-500">Location</span><strong class="mt-1 block text-sm text-slate-900">{{ config('hotel.address') }}</strong></span></a>
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)] lg:items-start">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <p class="text-sm font-medium text-blue-600">Contact inbox</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Tell us how we can help</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">This form writes to the <code>contact_messages</code> table. It is not a dummy WhatsApp redirect.</p>

                    <form action="{{ route('contact.store') }}" method="POST" class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        @csrf
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Full name</span><input type="text" name="name" required value="{{ old('name', auth()->user()?->name) }}" placeholder="Your name" class="w-full px-4 py-3 text-sm"><x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-600" /></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Email address</span><input type="email" name="email" required value="{{ old('email', auth()->user()?->email) }}" placeholder="name@example.com" class="w-full px-4 py-3 text-sm"><x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" /></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Phone number</span><input type="tel" name="phone" value="{{ old('phone') }}" placeholder="Active phone number" class="w-full px-4 py-3 text-sm"><x-input-error :messages="$errors->get('phone')" class="mt-2 text-sm text-rose-600" /></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Subject</span><select name="subject" required class="w-full px-4 py-3 text-sm"><option value="">Choose a subject</option>@foreach(['Reservation inquiry', 'Booking modification', 'Transportation request', 'Restaurant reservation', 'Event or wedding inquiry', 'General support'] as $subject)<option value="{{ $subject }}" {{ old('subject') === $subject ? 'selected' : '' }}>{{ $subject }}</option>@endforeach</select><x-input-error :messages="$errors->get('subject')" class="mt-2 text-sm text-rose-600" /></label>
                        <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Message</span><textarea name="message" rows="6" required placeholder="Tell us how we can help" class="w-full px-4 py-3 text-sm">{{ old('message') }}</textarea><x-input-error :messages="$errors->get('message')" class="mt-2 text-sm text-rose-600" /></label>
                        <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600 sm:col-span-2"><input type="checkbox" required><span>I agree that the hotel may use this information to respond to my inquiry.</span></label>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 sm:col-span-2"><i class="fa-solid fa-paper-plane"></i>Send to hotel inbox</button>
                    </form>
                </section>

                <section id="map-block" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm scroll-mt-24">
                    <div id="contactOasisMap" class="h-[380px] w-full bg-slate-100"></div>
                    <div class="p-6"><p class="text-xs font-medium text-slate-500">Hotel address</p><h2 class="mt-1 text-lg font-semibold text-slate-900">Oasis Hotel & Resort</h2><p class="mt-2 text-sm leading-6 text-slate-500">{{ config('hotel.address') }}</p><a href="https://maps.google.com/?q={{ config('hotel.latitude') }},{{ config('hotel.longitude') }}" target="_blank" rel="noopener" class="mt-5 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-map-location-dot text-blue-600"></i>Open Google Maps</a></div>
                </section>
            </div>
        </main>

        @include('layouts.footer')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const target = document.getElementById('contactOasisMap');
            if (!target || typeof L === 'undefined') return;
            const latitude = @json(config('hotel.latitude'));
            const longitude = @json(config('hotel.longitude'));
            const map = L.map(target, { scrollWheelZoom: false }).setView([latitude, longitude], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
            L.marker([latitude, longitude]).addTo(map).bindPopup('<strong>Oasis Hotel & Resort</strong><br>{{ e(config('hotel.address')) }}').openPopup();
        });
    </script>
</x-guest-layout>
