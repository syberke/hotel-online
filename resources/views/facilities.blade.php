<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[65vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2070&auto=format&fit=crop" 
                 alt="Oasis Infinity Pool Overlooking Ocean" 
                 class="w-full h-full object-cover opacity-60 scale-105">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 mb-4 font-bold">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Facilities</span>
                    </nav>
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Resort Amenities</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none mb-4">
                        World-Class Facilities<br><span class="font-serif italic font-normal text-amber-100">For An Exceptional Stay</span>
                    </h1>
                    <p class="text-neutral-300 text-xs md:text-sm max-w-xl font-medium leading-relaxed">
                        Every facility at Oasis Hotel is thoughtfully designed to elevate your comfort, relaxation, wellness, and lifestyle into an unforgettable experience.
                    </p>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xl grid grid-cols-2 md:grid-cols-6 gap-6 text-center">
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-bell-concierge"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">24/7 Concierge</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-car-side"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Airport Transfer</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-wifi"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Complimentary WiFi</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-spa"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Luxury Spa</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-dumbbell"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Fitness Center</h5>
                </div>
                <div class="last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-umbrella-beach"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Private Beach</h5>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center border-b border-neutral-200 pb-12">
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">50+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Premium Facilities</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">24/7</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Guest Services</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">98%</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Guest Satisfaction</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">10+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Luxury Experiences</div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <div class="text-2xl font-light text-amber-800 tracking-tight font-serif"><i class="fa-solid fa-star text-sm"></i> 5-Star</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Hospitality Rating</div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="flex flex-wrap justify-center gap-2 border-b border-neutral-200/60 pb-4 text-[10px] font-bold uppercase tracking-widest">
                <button class="px-5 py-2 bg-neutral-900 text-white rounded-none">All Facilities</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Wellness</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Dining</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Recreation</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Business</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Family</button>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Everything You Need</p>
                <h2 class="text-3xl font-serif text-neutral-900">Explore Our Premium Facilities</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                @php
                $facilitiesList = [
                    ['name' => 'Infinity Pool', 'img' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=600', 'desc' => 'Oceanfront architectural pool outfitted with private service cabanas and elite panoramic sunset viewpoints.'],
                    ['name' => 'Luxury Spa & Wellness', 'img' => 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600', 'desc' => 'Signature clinical anatomy rooms delivering custom aromatherapy massage cycles and advanced steam facilities.'],
                    ['name' => 'Elite Fitness Center', 'img' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?q=80&w=600', 'desc' => 'State-of-the-art technological athletic conditioning spaces with assigned personal training masters and yoga setups.'],
                    ['name' => 'Fine Dining Restaurant', 'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=600', 'desc' => 'Michelin-concept gastronomy modules combining curated local seasonal harvests with uninterrupted ocean vistas.'],
                    ['name' => 'Executive Lounge Access', 'img' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?q=80&w=600', 'desc' => 'VIP sanctuary configured for enterprise validation tracks, corporate assemblies, and fine premium micro-bars.'],
                    ['name' => 'Private Beach Access', 'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=600', 'desc' => 'Secured pristine sandy coastline paths entirely structuralized with premium sun loungers and water-sport equipment.']
                ];
                @endphp

                @foreach($facilitiesList as $item)
                <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
                    <div>
                        <div class="h-60 overflow-hidden relative bg-neutral-100">
                            <img src="{{ $item['img'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                            <div class="absolute top-4 left-4 bg-neutral-950/80 backdrop-blur-md px-3 py-1 text-white text-[8px] font-bold uppercase tracking-widest">
                                Premium Access
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900 mb-2 font-sans">{{ $item['name'] }}</h3>
                            <p class="text-neutral-500 text-xs leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-900 pb-0.5 hover:text-amber-700 hover:border-amber-700 transition-colors inline-block">
                            Explore Service &rarr;
                        </a>
                    </div>
                </div>
                @endforeach

            </div>
        </section>

        <section class="bg-white border-y border-neutral-200 py-24 px-6 space-y-24">
            <div class="max-w-7xl mx-auto">
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mb-24">
                    <div class="space-y-5">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-700 block">Sanctuary Framework</span>
                        <h3 class="text-3xl md:text-4xl font-serif text-neutral-900">The Integrative Wellness Journey</h3>
                        <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                            Oasis mengonfigurasi program penyembuhan holistik yang dipersonalisasi. Melalui kolaborasi meditasi zen fajar, terapi uap herbal aromatik, dan perawatan spa terukur, kami memulihkan sinergi energi tubuh dan pikiran Anda.
                        </p>
                        <ul class="grid grid-cols-2 gap-3 text-xs font-bold uppercase tracking-wider text-neutral-700 pt-2">
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Personalized Massage</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Dawn Yoga Guidance</li>
                            <li><li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Clinical Consultations</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Thermal Steam Suites</li>
                        </ul>
                    </div>
                    <div class="h-96 overflow-hidden bg-neutral-100 border border-neutral-100">
                        <img src="https://images.unsplash.com/photo-1519699047748-de8e457a634e?q=80&w=800" alt="Spa and Meditation Layout" class="w-full h-full object-cover">
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="h-96 overflow-hidden bg-neutral-100 border border-neutral-100 order-2 lg:order-1">
                        <img src="https://images.unsplash.com/photo-1431540015161-0bf868a2d407?q=80&w=800" alt="Executive Corporate Assembly Hall" class="w-full h-full object-cover">
                    </div>
                    <div class="space-y-5 order-1 lg:order-2">
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-amber-700 block">Enterprise Infrastructure</span>
                        <h3 class="text-3xl md:text-4xl font-serif text-neutral-900">Executive Assemblies & Events</h3>
                        <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                            Akomodasi korporat dirancang kedap suara secara akustik dengan peralatan presentasi mutakhir. Sempurna untuk rapat direksi tertutup maupun konferensi berskala besar yang membutuhkan privasi tingkat tinggi.
                        </p>
                        <ul class="grid grid-cols-2 gap-3 text-xs font-bold uppercase tracking-wider text-neutral-700 pt-2">
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Hybrid Matrix Boards</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> High-Speed Encryption</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> Curated Banquet Menus</li>
                            <li><i class="fa-solid fa-circle text-[6px] text-amber-700 mr-2 align-middle"></i> VIP Lounge Isolation</li>
                        </ul>
                    </div>
                </div>

            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Privileges</p>
                <h2 class="text-3xl font-serif text-neutral-900">Exclusive Guest Benefits</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6 text-center">
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-utensils"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Complimentary Breakfast</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-hourglass-start"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Early Check-In</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-hourglass-end"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Late Check-Out</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-headset"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Concierge Support</h4>
                </div>
                <div class="p-6 border border-neutral-200 bg-white">
                    <div class="text-xl text-amber-800 mb-2"><i class="fa-solid fa-calendar-check"></i></div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-1">Priority Booking</h4>
                </div>
            </div>
        </section>

        <section class="bg-neutral-900 text-white py-16 px-6 border-t border-neutral-800 text-center">
            <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-[11px] font-bold uppercase tracking-widest text-neutral-400">
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-trophy"></i></div>
                    <div>Best Luxury Hotel 2025</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-award"></i></div>
                    <div>Best Spa Resort Global</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-medal"></i></div>
                    <div>Traveler Choice Award</div>
                </div>
                <div class="space-y-2">
                    <div class="text-amber-400 text-xl"><i class="fa-solid fa-ribbon"></i></div>
                    <div>Hospitality Excellence</div>
                </div>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Faq</p>
                <h2 class="text-2xl md:text-3xl font-serif text-neutral-900">Operations & Directives</h2>
            </div>
            <div class="space-y-6">
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Berapakah jam operasional pusat kebugaran (Gym) & Kolam Renang?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Pusat kebugaran (Fitness Center) beroperasi penuh 24 jam menggunakan kartu akses kamar. Kolam renang utama (Infinity Pool) beroperasi mulai pukul 06:00 hingga 20:00 WITA.</p>
                </div>
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Apakah tamu luar non-menginap diperkenankan memesan perawatan Spa?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Ya. Tamu luar dapat memesan melalui sistem reservasi spa terpisah paling lambat 24 jam sebelum kedatangan, bergantung pada ketersediaan slot terapis kami.</p>
                </div>
            </div>
        </section>

        <section class="bg-neutral-950 text-white py-24 px-6 text-center border-t border-neutral-900">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Experience Luxury Beyond Accommodation</p>
                <h2 class="text-3xl md:text-4xl font-light tracking-tight mb-8">Discover facilities thoughtfully crafted to transform every stay.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('rooms') }}" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Book Your Stay
                    </a>
                    <a href="mailto:concierge@oasishotel.com" class="border border-neutral-800 hover:border-neutral-600 text-neutral-300 font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Contact Concierge
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>