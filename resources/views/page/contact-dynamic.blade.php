<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

    @php
        $contact = config('hotel.contact');
        $hasCoordinates = (float) ($contact['latitude'] ?? 0) !== 0.0 || (float) ($contact['longitude'] ?? 0) !== 0.0;
        $whatsappNumber = preg_replace('/\D+/', '', $contact['whatsapp'] ?? '');
    @endphp

    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')

        <header class="relative h-[55vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" alt="{{ config('hotel.name') }}" class="w-full h-full object-cover opacity-45 scale-105">
            <div class="absolute inset-0 flex items-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Guest Assistance</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none">We're Here<br><span class="font-serif italic font-normal text-amber-100">To Assist You</span></h1>
                    <p class="text-neutral-300 text-sm max-w-xl mt-5">Contact information is loaded from the active property configuration, so the portal follows the deployed hotel node.</p>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-10 z-20">
            <div class="bg-white border border-neutral-200 p-6 shadow-xl grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:border-r border-neutral-100 pr-3">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-phone"></i> Phone Support</div>
                    <p class="text-xs font-bold text-neutral-800">{{ $contact['phone'] ?: 'Not configured' }}</p>
                </div>
                <div class="md:border-r border-neutral-100 pr-3">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-envelope"></i> Email</div>
                    @if($contact['email'])
                        <a href="mailto:{{ $contact['email'] }}" class="text-xs font-bold text-neutral-800 hover:underline">{{ $contact['email'] }}</a>
                    @else
                        <p class="text-xs font-bold text-neutral-400">Not configured</p>
                    @endif
                </div>
                <div class="md:border-r border-neutral-100 pr-3">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-brands fa-whatsapp"></i> WhatsApp</div>
                    <p class="text-xs font-bold text-neutral-800">{{ $contact['whatsapp'] ?: 'Not configured' }}</p>
                </div>
                <div>
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-location-dot"></i> Property Address</div>
                    <p class="text-xs font-bold text-neutral-800">{{ $contact['address'] ?: 'Not configured' }}</p>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
                <section class="bg-white border border-neutral-200 p-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-900 mb-1">Send Us A Message</h3>
                    <p class="text-neutral-400 text-xs mb-8">The form opens the configured WhatsApp concierge channel with your message prefilled.</p>

                    @if($whatsappNumber)
                        <form id="whatsapp-contact-form" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <input type="text" id="wa-name" required placeholder="Full name" class="w-full border border-neutral-300 text-xs px-4 py-3 focus:outline-none focus:border-neutral-900">
                                <input type="email" id="wa-email" required placeholder="Email address" class="w-full border border-neutral-300 text-xs px-4 py-3 focus:outline-none focus:border-neutral-900">
                            </div>
                            <select id="wa-subject" required class="w-full border border-neutral-300 text-xs px-4 py-3 focus:outline-none focus:border-neutral-900 bg-white">
                                <option value="" selected disabled>Choose inquiry subject</option>
                                <option>Reservation Inquiry</option>
                                <option>Booking Modification</option>
                                <option>Transportation Request</option>
                                <option>Restaurant Reservation</option>
                                <option>Event & Wedding Inquiry</option>
                                <option>General Support Feedback</option>
                            </select>
                            <textarea id="wa-message" rows="5" required placeholder="Write your message" class="w-full border border-neutral-300 text-xs px-4 py-3 focus:outline-none focus:border-neutral-900"></textarea>
                            <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 cursor-pointer">Dispatch Message via WhatsApp</button>
                        </form>
                    @else
                        <div class="border border-amber-200 bg-amber-50 p-5 text-xs text-amber-900">Set <code>HOTEL_WHATSAPP</code> in the server environment to activate direct WhatsApp messaging.</div>
                    @endif
                </section>

                <section class="bg-white border border-neutral-200 p-8">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-900 mb-1">Our Location</h3>
                    <p class="text-neutral-400 text-xs mb-6">Map position follows the deployed property latitude and longitude.</p>
                    @if($hasCoordinates)
                        <div id="contactOasisMap" class="w-full border border-neutral-200 shadow-sm" style="height: 390px;"></div>
                    @else
                        <div class="h-[390px] border border-neutral-200 bg-neutral-50 flex items-center justify-center text-center p-8 text-xs text-neutral-400">Set <code>HOTEL_LATITUDE</code> and <code>HOTEL_LONGITUDE</code> to display the property map.</div>
                    @endif
                </section>
            </div>

            <section class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach([
                    ['Reservations Department', $contact['reservation_email'] ?? ''],
                    ['Events & Wedding Galas', $contact['events_email'] ?? ''],
                    ['Media & Global Relations', $contact['press_email'] ?? ''],
                ] as [$department, $email])
                    <div class="p-6 border border-neutral-200 bg-white space-y-2">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900">{{ $department }}</h4>
                        @if($email)
                            <a href="mailto:{{ $email }}" class="text-[10px] font-bold text-amber-800 underline">{{ $email }}</a>
                        @else
                            <span class="text-[10px] text-neutral-400">Contact not configured</span>
                        @endif
                    </div>
                @endforeach
            </section>
        </main>

        @include('layouts.footer')
    </div>

    @if($hasCoordinates)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9coqIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            const hotelLatitude = @json((float) $contact['latitude']);
            const hotelLongitude = @json((float) $contact['longitude']);
            const hotelName = @json(config('hotel.name'));
            const hotelAddress = @json($contact['address']);
            const map = L.map('contactOasisMap', { scrollWheelZoom: false }).setView([hotelLatitude, hotelLongitude], 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors',
            }).addTo(map);
            L.marker([hotelLatitude, hotelLongitude]).addTo(map)
                .bindPopup(`<strong>${hotelName}</strong><br>${hotelAddress}`)
                .openPopup();
        </script>
    @endif

    @if($whatsappNumber)
        <script>
            document.getElementById('whatsapp-contact-form').addEventListener('submit', function (event) {
                event.preventDefault();
                const message = [
                    `Halo ${@json(config('hotel.name'))} Concierge Desk,`,
                    '',
                    `Nama: ${document.getElementById('wa-name').value}`,
                    `Email: ${document.getElementById('wa-email').value}`,
                    `Subjek: ${document.getElementById('wa-subject').value}`,
                    '',
                    document.getElementById('wa-message').value,
                ].join('\n');
                window.open(`https://api.whatsapp.com/send?phone={{ $whatsappNumber }}&text=${encodeURIComponent(message)}`, '_blank', 'noopener');
            });
        </script>
    @endif
</x-guest-layout>
