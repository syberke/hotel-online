<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')

        <header class="relative isolate min-h-[520px] overflow-hidden bg-slate-950">
            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" alt="Oasis Hotel" class="absolute inset-0 h-full w-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/78 to-blue-950/30"></div>
            <div class="relative mx-auto flex min-h-[520px] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
                <div class="max-w-3xl text-white">
                    <nav class="flex items-center gap-2 text-xs font-medium text-slate-300"><a href="{{ route('home') }}" class="hover:text-white">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-200">Contact</span></nav>
                    <p class="mt-6 text-sm font-medium text-blue-300">Guest assistance</p>
                    <h1 class="mt-2 text-5xl font-semibold leading-tight tracking-tight sm:text-6xl">Talk to the hotel team</h1>
                    <p class="mt-5 max-w-2xl text-base leading-7 text-slate-200">Get help with room reservations, transportation, dining, facilities, events, or questions before and during your stay.</p>
                </div>
            </div>
        </header>

        <section class="relative z-10 mx-auto -mt-10 max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-xl shadow-slate-900/10 sm:grid-cols-2 lg:grid-cols-4">
                @foreach([
                    ['fa-phone', 'Phone', '+62 361 1234 567', 'tel:+623611234567'],
                    ['fa-envelope', 'Email', 'stay@oasishotel.com', 'mailto:stay@oasishotel.com'],
                    ['fa-brands fa-whatsapp', 'WhatsApp', '+62 812 3456 7890', 'https://wa.me/6281234567890'],
                    ['fa-location-dot', 'Location', 'Nusa Dua, Bali', '#map-block'],
                ] as [$icon, $label, $value, $href])
                    <a href="{{ $href }}" class="flex items-center gap-4 rounded-xl bg-slate-50 p-4 transition hover:bg-blue-50">
                        <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="{{ str_starts_with($icon, 'fa-brands') ? $icon : 'fa-solid ' . $icon }}"></i></span>
                        <span class="min-w-0"><span class="block text-xs font-medium text-slate-500">{{ $label }}</span><span class="mt-1 block truncate text-sm font-semibold text-slate-900">{{ $value }}</span></span>
                    </a>
                @endforeach
            </div>
        </section>

        <main class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)] lg:items-start">
                <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    <p class="text-sm font-medium text-blue-600">Send a message</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Start a WhatsApp conversation</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Complete the form and we will open WhatsApp with a prepared message. You can review it before sending.</p>

                    <form id="whatsapp-contact-form" class="mt-7 grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Full name</span><input type="text" id="wa-name" required placeholder="Your name" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Email address</span><input type="email" id="wa-email" required placeholder="name@example.com" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Phone number</span><input type="tel" id="wa-phone" placeholder="Your phone number" class="w-full px-4 py-3 text-sm"></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-slate-700">Subject</span><select id="wa-subject" required class="w-full px-4 py-3 text-sm"><option value="">Choose a subject</option><option>Reservation inquiry</option><option>Booking modification</option><option>Transportation request</option><option>Restaurant reservation</option><option>Event or wedding inquiry</option><option>General support</option></select></label>
                        <label class="block sm:col-span-2"><span class="mb-2 block text-sm font-medium text-slate-700">Message</span><textarea id="wa-message" rows="5" required placeholder="Tell us how we can help" class="w-full px-4 py-3 text-sm"></textarea></label>
                        <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-600 sm:col-span-2"><input type="checkbox" required class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"><span>I agree that the entered information will be used to prepare this WhatsApp inquiry.</span></label>
                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700 sm:col-span-2"><i class="fa-brands fa-whatsapp"></i>Open WhatsApp message</button>
                    </form>
                </section>

                <section id="map-block" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm scroll-mt-24">
                    <div id="contactOasisMap" class="h-[360px] w-full bg-slate-100"></div>
                    <div class="p-6">
                        <p class="text-xs font-medium text-slate-500">Hotel address</p>
                        <h2 class="mt-1 text-lg font-semibold text-slate-900">Oasis Hotel & Resort</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">Jl. Pantai Indah No. 88, Nusa Dua, Bali 80363, Indonesia</p>
                        <div class="mt-5 space-y-3 rounded-xl bg-slate-50 p-4 text-sm text-slate-600">
                            <div class="flex items-center justify-between gap-4"><span>Ngurah Rai Airport</span><strong class="text-slate-900">About 25 minutes</strong></div>
                            <div class="flex items-center justify-between gap-4"><span>Benoa Harbor</span><strong class="text-slate-900">About 30 minutes</strong></div>
                            <div class="flex items-center justify-between gap-4"><span>Nusa Dua Beach</span><strong class="text-slate-900">Nearby</strong></div>
                        </div>
                        <div class="mt-5 grid grid-cols-2 gap-3"><a href="https://maps.google.com/?q=-8.8034,115.2126" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-map-location-dot text-blue-600"></i>Google Maps</a><a href="https://maps.apple.com/?q=Nusa+Dua+Bali" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-3 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-brands fa-apple"></i>Apple Maps</a></div>
                    </div>
                </section>
            </div>
        </main>

        <section class="border-y border-slate-200 bg-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center"><p class="text-sm font-medium text-blue-600">Contact by department</p><h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Reach the right hotel team</h2></div>
                <div class="mt-8 grid grid-cols-1 gap-5 md:grid-cols-3">
                    @foreach([
                        ['Reservations', 'Rooms, booking changes, arrival questions, and stay planning.', 'book@oasishotel.com', 'fa-calendar-check'],
                        ['Events & weddings', 'Meetings, celebrations, venue arrangements, and group requirements.', 'events@oasishotel.com', 'fa-champagne-glasses'],
                        ['Media & partnerships', 'Press, content production, filming, and partnership inquiries.', 'press@oasishotel.com', 'fa-camera'],
                    ] as [$department, $description, $email, $icon])
                        <article class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><span class="grid h-11 w-11 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h3 class="mt-5 text-lg font-semibold text-slate-900">{{ $department }}</h3><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p><a href="mailto:{{ $email }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">{{ $email }} <i class="fa-solid fa-arrow-right text-xs"></i></a></article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-5xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-5 rounded-2xl border border-rose-200 bg-rose-50 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-4"><span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-white text-rose-600 shadow-sm"><i class="fa-solid fa-kit-medical"></i></span><div><h2 class="text-base font-semibold text-rose-900">Urgent medical or security assistance</h2><p class="mt-1 text-sm leading-6 text-rose-700">During a stay, dial extension 911 from the room phone or call +62 361 1234 911.</p></div></div><a href="tel:+623611234911" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700"><i class="fa-solid fa-phone"></i>Call urgent support</a>
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white"><div class="mx-auto flex max-w-4xl flex-col items-center px-4 text-center sm:px-6 lg:px-8"><p class="text-sm font-medium text-blue-300">Plan your stay</p><h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Ready to choose a room?</h2><p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300">Browse current room options or contact the reservations team for help with dates and guest requirements.</p><a href="{{ route('rooms') }}" class="mt-7 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-500">Browse rooms</a></div></section>

        @include('layouts.footer')
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const contactForm = document.getElementById('whatsapp-contact-form');
            contactForm?.addEventListener('submit', (event) => {
                event.preventDefault();
                const name = document.getElementById('wa-name').value.trim();
                const email = document.getElementById('wa-email').value.trim();
                const phone = document.getElementById('wa-phone').value.trim();
                const subject = document.getElementById('wa-subject').value;
                const message = document.getElementById('wa-message').value.trim();
                const text = [
                    'Hello Oasis Hotel team,',
                    '',
                    `Name: ${name}`,
                    `Email: ${email}`,
                    `Phone: ${phone || '-'}`,
                    `Subject: ${subject}`,
                    '',
                    message
                ].join('\n');
                window.open(`https://api.whatsapp.com/send?phone=6281234567890&text=${encodeURIComponent(text)}`, '_blank', 'noopener');
            });

            const mapTarget = document.getElementById('contactOasisMap');
            if (!mapTarget || typeof L === 'undefined') return;
            const map = L.map(mapTarget, { scrollWheelZoom: false }).setView([-8.8034, 115.2126], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap contributors' }).addTo(map);
            L.marker([-8.8034, 115.2126]).addTo(map).bindPopup('<strong>Oasis Hotel & Resort</strong><br>Nusa Dua, Bali.').openPopup();
        });
    </script>
</x-guest-layout>
