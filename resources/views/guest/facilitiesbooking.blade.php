<style>
    /* Menyembunyikan scrollbar bawaan untuk sub-kategori fasilitas horizontal */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Scrollbar minimalis untuk area konten utama dan sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f5f5f3; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d4d4d4; 
    }
</style>

<x-app-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col lg:flex-row">
        
        <aside class="w-full lg:w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between p-6 shrink-0 border-r border-neutral-800">
            <div class="space-y-8">
                <div>
                    <h1 class="text-xl font-serif tracking-[0.3em] text-amber-100 font-bold uppercase">Oasis</h1>
                    <span class="text-[8px] tracking-widest text-amber-600 uppercase font-bold block mt-0.5">Guest Portal</span>
                </div>

                <nav class="space-y-1 text-xs uppercase tracking-wider font-bold">
                    <span class="text-[9px] text-neutral-600 tracking-widest uppercase block mb-3 font-mono">Main Ledger</span>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-chart-pie w-4"></i> Dashboard
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-calendar-check w-4"></i> My Bookings
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-hotel w-4"></i> My Stay
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-bell-concierge w-4"></i> Room Service
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 text-neutral-400 hover:text-white transition-colors">
                        <i class="fa-solid fa-utensils w-4"></i> Restaurant Orders
                    </a>
                    <a href="#" class="flex items-center gap-3 px-3 py-3 bg-neutral-900 text-amber-500 border-l-2 border-amber-500 transition-colors">
                        <i class="fa-solid fa-spa w-4"></i> Facilities
                    </a>
                </nav>
            </div>

            <div class="border-t border-neutral-800 pt-4 mt-8 flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-neutral-800 flex items-center justify-center text-amber-100 font-serif font-bold text-xs">G1</div>
                <div>
                    <p class="text-xs font-bold text-white tracking-wide">guest1</p>
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-widest">Patron Member</span>
                </div>
            </div>
        </aside>

        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            
            <div class="pb-4 border-b border-neutral-200">
                <h2 class="text-3xl font-serif text-neutral-900">Facilities</h2>
                <p class="text-xs text-neutral-400 mt-0.5">Explore our world-class facilities and create unforgettable moments during your stay.</p>
            </div>

            <div class="relative h-44 overflow-hidden bg-neutral-950 text-white border border-neutral-200 shadow-sm">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200" class="w-full h-full object-cover opacity-40" alt="Resort Luxury Facilities">
                <div class="absolute inset-0 p-6 flex flex-col justify-center max-w-xl space-y-1">
                    <span class="text-[8px] tracking-widest font-bold uppercase text-amber-400">Experience Luxury</span>
                    <h3 class="text-xl md:text-2xl font-serif tracking-wide">Elevate Your Stay</h3>
                    <p class="text-neutral-300 text-[11px] leading-relaxed max-w-md">Discover exceptional facilities designed for relaxation, wellness, and entertainment. Most venues are complimentary for active hotel patrons.</p>
                </div>
            </div>

            <div class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-amber-600 bg-white text-amber-800 min-w-[85px] shadow-sm">
                    <i class="fa-solid fa-border-all text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">All Facilities</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[85px] shadow-sm transition-colors">
                    <i class="fa-solid fa-heart-pulse text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Wellness</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[85px] shadow-sm transition-colors">
                    <i class="fa-solid fa-dumbbell text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Sports & Fitness</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[85px] shadow-sm transition-colors">
                    <i class="fa-solid fa-umbrella-beach text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Pools & Beach</span>
                </button>
                <button type="button" class="flex flex-col items-center justify-center p-3 border border-neutral-200 bg-white text-neutral-500 hover:border-neutral-400 min-w-[85px] shadow-sm transition-colors">
                    <i class="fa-solid fa-children text-xs mb-1"></i>
                    <span class="text-[9px] font-bold uppercase tracking-wider">Kids & Family</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($facilities as $f)
                <div class="bg-white border border-neutral-200 flex flex-col justify-between hover:border-neutral-400 transition-all duration-300 shadow-sm">
                    <div>
                        <div class="h-44 overflow-hidden relative bg-neutral-100">
                            <img src="{{ $f->image_url }}" class="w-full h-full object-cover" alt="{{ $f->name }}">
                        </div>
                        <div class="p-5 space-y-2">
                            <div class="flex justify-between items-start">
                                <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900">{{ $f->name }}</h4>
                                <span class="inline-flex items-center gap-1 text-[9px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5">
                                    <span class="h-1 w-1 rounded-full bg-emerald-500"></span> Open Now
                                </span>
                            </div>
                            <p class="text-neutral-400 text-[11px] leading-relaxed line-clamp-2">{{ $f->description }}</p>
                            
                            <div class="text-[10px] text-neutral-500 font-semibold flex gap-4 pt-2 border-t border-neutral-100">
                                <span><i class="fa-solid fa-clock text-amber-700 mr-1"></i> {{ $f->hours }}</span>
                                <span><i class="fa-solid fa-circle-info text-amber-700 mr-1"></i> {{ $f->access_type ?? 'Complimentary' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-5 pt-0">
                        <div class="grid grid-cols-4 gap-2">
                            <button type="button" class="col-span-3 bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-2.5 transition-colors text-center cursor-pointer">
                                {{ $f->requires_booking ? 'Reserve Slot' : 'View Activities' }}
                            </button>
                            <button type="button" class="border border-neutral-200 hover:border-neutral-900 text-neutral-700 hover:text-neutral-900 flex items-center justify-center py-2.5 transition-colors cursor-pointer">
                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="bg-amber-50/40 border border-amber-200/60 p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex gap-3 items-center">
                    <div class="text-amber-800 text-base"><i class="fa-solid fa-calendar-minus"></i></div>
                    <div class="text-xs">
                        <h5 class="font-bold text-neutral-900">Planning a special occasion?</h5>
                        <p class="text-neutral-500 text-[11px] mt-0.5">Let us help you create an unforgettable private dining, wellness retreat, or custom celebration layout.</p>
                    </div>
                </div>
                <button type="button" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-[10px] uppercase tracking-widest px-4 py-2.5 shrink-0 transition-colors shadow-sm">
                    Contact Concierge
                </button>
            </div>
        </main>

        <aside class="w-full lg:w-96 bg-white border-t lg:border-t-0 lg:border-l border-neutral-200 p-6 flex flex-col justify-between shrink-0 space-y-6 custom-scrollbar overflow-y-auto max-h-screen">
            
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-2 border-b border-neutral-100">
                    <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">My Reservations</h3>
                    <a href="#" class="text-[9px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View All</a>
                </div>
                
                <div class="space-y-2">
                    <div class="bg-[#fafafa] border border-neutral-200 p-3 flex justify-between items-start">
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wide">Infinity Pool Cabana</h4>
                            <p class="text-[10px] text-neutral-500 font-medium"><i class="fa-solid fa-calendar text-[9px] mr-1"></i> 17 Jun 2026, 14:00</p>
                            <p class="text-[10px] text-neutral-400 font-medium">Cabana #3 &bull; 2 Adults</p>
                        </div>
                        <span class="text-[8px] font-bold font-mono tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5">CONFIRMED</span>
                    </div>

                    <div class="bg-[#fafafa] border border-neutral-200 p-3 flex justify-between items-start">
                        <div class="space-y-1">
                            <h4 class="text-xs font-bold text-neutral-900 uppercase tracking-wide">Spa Treatment</h4>
                            <p class="text-[10px] text-neutral-500 font-medium"><i class="fa-solid fa-calendar text-[9px] mr-1"></i> 18 Jun 2026, 16:00</p>
                            <p class="text-[10px] text-neutral-400 font-medium">Therapy Room 2 &bull; 1 Person</p>
                        </div>
                        <span class="text-[8px] font-bold font-mono tracking-wider text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5">CONFIRMED</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-neutral-100">
                <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Operating Hours</h3>
                <div class="space-y-2 text-xs font-medium text-neutral-600">
                    <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Infinity Pool</span><span class="font-mono text-neutral-800 font-bold">06:00 AM - 10:00 PM</span></div>
                    <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Fitness Center</span><span class="font-mono text-neutral-800 font-bold">24 Hours Open</span></div>
                    <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Oasis Luxury Spa</span><span class="font-mono text-neutral-800 font-bold">08:00 AM - 09:00 PM</span></div>
                    <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Kids Sanctuary Club</span><span class="font-mono text-neutral-800 font-bold">09:00 AM - 08:00 PM</span></div>
                    <div class="flex justify-between"><span>Private Horizon Beach</span><span class="font-mono text-neutral-800 font-bold">06:00 AM - 07:00 PM</span></div>
                </div>
            </div>

            <div class="space-y-3 pt-4 border-t border-neutral-100 flex-1 flex flex-col justify-end">
                <h3 class="text-xs uppercase tracking-widest font-bold text-neutral-400">Facilities Map</h3>
                <div class="relative h-28 border border-neutral-200 bg-neutral-100 overflow-hidden shadow-inner flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1524661135339-9140b0078ee0?q=80&w=400" class="w-full h-full object-cover blur-[1px] opacity-70" alt="Resort layout schematic">
                    <div class="absolute inset-0 bg-neutral-900/10 flex items-center justify-center">
                        <button type="button" class="bg-white border border-neutral-300 text-neutral-800 text-[9px] font-bold uppercase tracking-widest px-3 py-1.5 hover:border-neutral-900 shadow-md transition-colors">
                            <i class="fa-solid fa-map-location-dot text-amber-700 mr-1"></i> View Resort Map
                        </button>
                    </div>
                </div>

                <div class="pt-4 space-y-2 text-center border-t border-neutral-100">
                    <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Need Assistance?</p>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="tel:+62361123456" class="border border-neutral-200 text-neutral-700 font-bold text-[10px] uppercase tracking-wider py-2 hover:border-neutral-900 transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-phone text-amber-800"></i> Call Ext. 500
                        </a>
                        <a href="#" class="border border-neutral-200 text-neutral-700 font-bold text-[10px] uppercase tracking-wider py-2 hover:border-neutral-900 transition-colors flex items-center justify-center gap-1">
                            <i class="fa-solid fa-comments text-amber-800"></i> Live Chat 24/7
                        </a>
                    </div>
                </div>
            </div>

        </aside>
    </div>
</x-app-layout>