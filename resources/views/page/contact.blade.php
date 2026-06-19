<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[65vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?q=80&w=2070&auto=format&fit=crop" 
                 alt="Oasis Hotel Exterior at Sunset" 
                 class="w-full h-full object-cover opacity-50 scale-105">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 mb-4 font-bold">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Contact</span>
                    </nav>
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Guest Assistance</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none mb-4">
                        We're Here<br><span class="font-serif italic font-normal text-amber-100">To Assist You</span>
                    </h1>
                    <p class="text-neutral-300 text-xs md:text-sm max-w-xl font-medium leading-relaxed">
                        Our dedicated hospitality specialists are available to help with reservations, special requests, transportation logistics, private events, and every personalized detail of your upcoming stay.
                    </p>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xl grid grid-cols-1 md:grid-cols-4 gap-6 text-left">
                <div class="border-b md:border-b-0 md:border-r border-neutral-100 pb-4 md:pb-0 pr-2">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-phone"></i> Phone Support</div>
                    <p class="text-xs font-bold text-neutral-800 tracking-wide">+62 361 1234 567</p>
                    <p class="text-[10px] text-neutral-400 mt-1 uppercase tracking-wider font-semibold">24/7 Global Hotline</p>
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-100 pb-4 md:pb-0 pr-2">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-envelope"></i> Email Matrix</div>
                    <a href="mailto:stay@oasishotel.com" class="text-xs font-bold text-neutral-800 tracking-wide block hover:underline">stay@oasishotel.com</a>
                    <p class="text-[10px] text-neutral-400 mt-1 uppercase tracking-wider font-semibold">Response &lt; 15 Mins</p>
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-100 pb-4 md:pb-0 pr-2">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-brands fa-whatsapp"></i> WhatsApp Chat</div>
                    <p class="text-xs font-bold text-neutral-800 tracking-wide">+62 812 3456 7890</p>
                    <p class="text-[10px] text-neutral-400 mt-1 uppercase tracking-wider font-semibold">Instant Concierge Link</p>
                </div>
                <div class="pr-2">
                    <div class="text-amber-800 text-base mb-2"><i class="fa-solid fa-location-dot"></i> Resort Address</div>
                    <p class="text-xs font-bold text-neutral-800 tracking-wide line-clamp-1">Nusa Dua, Bali, Indonesia</p>
                    <a href="#map-block" class="text-[10px] text-amber-700 mt-1 uppercase tracking-wider font-bold block hover:text-neutral-900 transition-colors">View Map Location →</a>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center border-b border-neutral-200 pb-12">
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">24/7</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Concierge Infrastructure</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">&lt; 15 Mins</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Average Response Speed</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">98%</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Verified Guest Satisfaction</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-amber-800 tracking-tight font-serif">Multilingual</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Global Guest Support Matrix</div>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <section class="w-full lg:w-7/12 bg-white border border-neutral-200 p-8 rounded-none">
                    <div class="mb-8">
                        <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-900 mb-1">Send Us A Message</h3>
                        <p class="text-neutral-400 text-xs">Fill out the secure communication framework below, and our respective managers will route it immediately.</p>
                    </div>

                    <form action="#" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Full Name</label>
                                <input type="text" required placeholder="Enter your full name" class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 bg-transparent">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Email Address</label>
                                <input type="email" required placeholder="Enter your email address" class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 bg-transparent">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Phone Number</label>
                                <input type="tel" placeholder="Enter your phone number" class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 bg-transparent">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Subject Classification</label>
                                <select required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                                    <option selected disabled>Choose inquiry subject</option>
                                    <option>Reservation Inquiry</option>
                                    <option>Booking Modification</option>
                                    <option>Transportation Request</option>
                                    <option>Restaurant Reservation</option>
                                    <option>Event & Wedding Inquiry</option>
                                    <option>General Support Feedback</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Department Route</label>
                                <select required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                                    <option>Reservations Department</option>
                                    <option>Concierge Desk & Experiences</option>
                                    <option>Culinary & Restaurant Relations</option>
                                    <option>Events, Weddings & Galas</option>
                                    <option>Enterprise Partnerships</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Inquiry Priority Level</label>
                                <div class="flex items-center space-x-4 h-full pt-1">
                                    <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer">
                                        <input type="radio" name="priority" checked class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2">
                                        General
                                    </label>
                                    <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer">
                                        <input type="radio" name="priority" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2">
                                        Important
                                    </label>
                                    <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer">
                                        <input type="radio" name="priority" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2">
                                        Urgent
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Message Content</label>
                            <textarea rows="4" required placeholder="Write your personalized specifications or questions here..." class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 placeholder-neutral-300 bg-transparent"></textarea>
                        </div>

                        <div class="flex items-start text-xs text-neutral-500 py-1">
                            <input type="checkbox" required class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-950 focus:ring-offset-0 w-3.5 h-3.5 mt-0.5">
                            <span class="ms-2.5 text-[10px] leading-normal font-medium text-neutral-400 uppercase tracking-wider">
                                I provide strict consent to process this security payload under the data criteria defined within the hotel's <a href="#" class="font-bold text-neutral-900 underline">Privacy matrix</a>.
                            </span>
                        </div>

                        <div>
                            <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all">
                                Dispatch Security Message →
                            </button>
                        </div>
                    </form>
                </section>

                <section id="map-block" class="w-full lg:w-5/12 bg-white border border-neutral-200 p-8 rounded-none flex flex-col justify-between self-stretch">
                    <div>
                        <div class="mb-6">
                            <h3 class="text-sm font-bold uppercase tracking-widest text-neutral-900 mb-1">Our Location</h3>
                            <p class="text-neutral-400 text-xs">Uninterrupted beachfront positioning inside the exclusive security enclave of Nusa Dua, Bali.</p>
                        </div>

                        <div id="contactOasisMap" class="w-full border border-neutral-200 mb-6 shadow-sm z-10" style="height: 280px;"></div>

                        <div class="space-y-4 border-t border-neutral-100 pt-6">
                            <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-2"><i class="fa-solid fa-plane-arrival text-amber-800 mr-1.5"></i> Transit Vectors & Logistics</h4>
                            <div class="grid grid-cols-1 gap-3 text-[11px] font-medium text-neutral-500">
                                <div class="flex justify-between items-baseline border-b border-dashed border-neutral-200 pb-2">
                                    <span class="text-neutral-800 font-bold uppercase tracking-wide text-[10px]">Ngurah Rai Airport (DPS)</span>
                                    <span>12.4 km / 20 Mins Drive</span>
                                </div>
                                <div class="flex justify-between items-baseline border-b border-dashed border-neutral-200 pb-2">
                                    <span class="text-neutral-800 font-bold uppercase tracking-wide text-[10px]">Benoa Luxury Cruise Port</span>
                                    <span>15.1 km / 25 Mins Drive</span>
                                </div>
                                <div class="flex justify-between items-baseline pb-1">
                                    <span class="text-neutral-800 font-bold uppercase tracking-wide text-[10px]">Private Transit Helicopter Pad</span>
                                    <span>In-Resort Access (North Field)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-neutral-100 pt-6 mt-6 grid grid-cols-2 gap-3 text-center">
                        <a href="https://maps.google.com/?q=-8.8034,115.2126" target="_blank" rel="noopener noreferrer" class="border border-neutral-300 hover:border-neutral-900 hover:bg-neutral-50 text-neutral-800 text-[9px] font-bold uppercase tracking-widest py-3 rounded-none transition-colors flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-map-location-dot text-amber-800"></i> Open Google Maps
                        </a>
                        <a href="https://maps.apple.com/?q=Nusa+Dua+Bali" target="_blank" rel="noopener noreferrer" class="border border-neutral-300 hover:border-neutral-900 hover:bg-neutral-50 text-neutral-800 text-[9px] font-bold uppercase tracking-widest py-3 rounded-none transition-colors flex items-center justify-center gap-1.5">
                            <i class="fa-brands fa-apple text-neutral-900"></i> Open Apple Maps
                        </a>
                    </div>
                </section>

            </div>
        </main>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-neutral-200">
            <div class="text-center mb-12">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Corporate Directory</p>
                <h2 class="text-3xl font-serif text-neutral-900">Direct Department Contacts</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-6 border border-neutral-200 bg-white space-y-2 rounded-none">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Reservations Department</h4>
                    <p class="text-neutral-400 text-[11px] leading-relaxed">For room bookings, luxury suite modification matrix alignments, or premium packaging inquires.</p>
                    <div class="text-[10px] font-bold text-amber-800 uppercase tracking-widest pt-2">Email: <a href="mailto:book@oasishotel.com" class="underline text-neutral-800">book@oasishotel.com</a></div>
                </div>
                <div class="p-6 border border-neutral-200 bg-white space-y-2 rounded-none">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Events & Wedding Galas</h4>
                    <p class="text-neutral-400 text-[11px] leading-relaxed">Dedicated bespoke coordination handling wedding setups, grand ballrooms, and luxury corporate events.</p>
                    <div class="text-[10px] font-bold text-amber-800 uppercase tracking-widest pt-2">Email: <a href="mailto:events@oasishotel.com" class="underline text-neutral-800">events@oasishotel.com</a></div>
                </div>
                <div class="p-6 border border-neutral-200 bg-white space-y-2 rounded-none">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Media & Global Relations</h4>
                    <p class="text-neutral-400 text-[11px] leading-relaxed">Press materials, influencer hosting inquiries, filming licensing requests, and asset partnership alignments.</p>
                    <div class="text-[10px] font-bold text-amber-800 uppercase tracking-widest pt-2">Email: <a href="mailto:press@oasishotel.com" class="underline text-neutral-800">press@oasishotel.com</a></div>
                </div>
            </div>
        </section>

        <section class="bg-neutral-50 border-t border-neutral-200 py-16 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-[11px] font-bold uppercase tracking-widest text-neutral-400">
                    <div class="space-y-1">
                        <div class="text-neutral-900 font-serif normal-case italic text-lg tracking-normal">Front Desk</div>
                        <div class="text-neutral-800 font-bold">Open 24 Hours / 7 Days</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-neutral-900 font-serif normal-case italic text-lg tracking-normal">Signature Dining</div>
                        <div class="text-neutral-800 font-bold">06:30 AM — 11:00 PM</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-neutral-900 font-serif normal-case italic text-lg tracking-normal">Spa & Wellness</div>
                        <div class="text-neutral-800 font-bold">09:00 AM — 09:00 PM</div>
                    </div>
                    <div class="space-y-1">
                        <div class="text-neutral-900 font-serif normal-case italic text-lg tracking-normal">Executive Lounge</div>
                        <div class="text-neutral-800 font-bold">07:00 AM — 11:00 PM</div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 py-12 text-center border-t border-neutral-200/60 text-xs">
            <h4 class="font-bold uppercase tracking-widest text-red-800 mb-1"><i class="fa-solid fa-triangle-exclamation"></i> 24/7 Security & Medical Hotline</h4>
            <p class="text-neutral-400 text-[11px] leading-relaxed max-w-lg mx-auto">
                For instant physical crisis coordination or medical support deployments inside the resort, dial extension <span class="text-neutral-900 font-bold">#911</span> from any in-room phone console or call <span class="text-neutral-900 font-bold">+62 361 1234 911</span> directly.
            </p>
        </section>

        <section class="bg-neutral-900 text-white py-24 px-6 text-center">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Let's Plan Your Perfect Stay</p>
                <h2 class="text-3xl md:text-4xl font-light tracking-tight mb-8">Our hospitality specialists are ready to assist you with every single detail of your Oasis experience.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('rooms') }}" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Book Your Stay Now
                    </a>
                    <a href="mailto:concierge@oasishotel.com" class="border border-neutral-700 hover:border-neutral-500 text-neutral-300 font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Inquire Group Events
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        // Inisialisasi peta Leaflet interaktif pada div #contactOasisMap dengan koordinat Nusa Dua Bali
        var contactMap = L.map('contactOasisMap', {
            scrollWheelZoom: false // Mencegah interupsi scroll halaman yang tidak sengaja
        }).setView([-8.8034, 115.2126], 14);

        // Memuat tile layer bergaya clean openstreetmap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(contactMap);

        // Menambahkan penanda pin (Marker) lokasi resor
        var resortMarker = L.marker([-8.8034, 115.2126]).addTo(contactMap);
        
        // Memasang struktur popup teks kaya otomatis ketika peta termuat sempurna
        resortMarker.bindPopup(`
            <div style="font-family: sans-serif; padding: 2px;">
                <b style="color: #111; font-size: 12px; text-transform: uppercase; tracking: 0.5px;">Oasis Premium Resort</b>
                <p style="color: #666; font-size: 10px; margin: 4px 0 0 0; line-height: 1.4;">Kawasan Eksklusif ITDC Lot 8,<br>Nusa Dua, Bali, Indonesia.</p>
            </div>
        `).openPopup();
    </script>
</x-guest-layout>