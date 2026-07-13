<x-guest-layout>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <div class="min-h-screen bg-[#fcfcfc] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <div class="fixed bottom-4 right-4 z-50 rounded-full border border-white/20 px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-white shadow-xl"
             style="background-color: {{ config('app.node.color') }}"
             title="Respons ini dilayani oleh node aplikasi {{ config('app.node.name') }}">
            <span class="mr-1 inline-block h-2 w-2 rounded-full bg-white animate-pulse"></span>
            Served by {{ config('app.node.name') }}
        </div>

        <header class="relative h-[85vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2070&auto=format&fit=crop" 
                 alt="Oasis Hero Premium Suite" 
                 class="w-full h-full object-cover opacity-60 scale-105 transform transition-transform duration-[10000ms]">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-4xl mx-auto w-full text-white">
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-4">A Paradigm of Refined Hospitality</p>
                    <h1 class="text-5xl md:text-8xl font-light tracking-tight leading-none mb-6">
                        Where Luxury<br><span class="font-serif italic font-normal text-amber-100">Meets Serenity</span>
                    </h1>
                    <p class="text-neutral-300 text-sm md:text-base max-w-xl font-medium leading-relaxed mb-8">
                        Experience exceptional comfort, world-class hospitality, and unforgettable stays. Designed as an architectural sanctuary for the discerning traveler.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('rooms') }}" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-4 px-8 transition-all text-center">
                            Explore Rooms
                        </a>
                    </div>
                </div>
            </div>
        </header>

        @if(session('success') || session('error') || session('info'))
        <div class="max-w-6xl mx-auto px-6 mt-6">
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider rounded-none">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 bg-red-50 border border-red-200 text-red-800 text-xs font-bold uppercase tracking-wider rounded-none">
                    <i class="fa-solid fa-circle-exclamation mr-2"></i> {{ session('error') }}
                </div>
            @endif
            @if(session('info'))
                <div class="p-4 bg-amber-50 border border-amber-200 text-amber-800 text-xs font-bold uppercase tracking-wider rounded-none flex justify-between items-center">
                    <div>
                        <i class="fa-solid fa-circle-info mr-2"></i> {{ session('info') }}
                    </div>
                    <a href="{{ route('login') }}" class="bg-amber-800 text-white px-3 py-1 text-[10px] uppercase font-bold tracking-widest">Login Now</a>
                </div>
            @endif
        </div>
        @endif

        <section class="max-w-7xl mx-auto px-6 pt-20 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 border-b border-neutral-100 pb-16">
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-star"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Premium Hospitality</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Personalized butler service configured to anticipate your explicit needs seamlessly.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-compass"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Ocean View Rooms</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Panoramic Floor-to-ceiling glass systems overlooking crystal clean marine horizons.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-spa"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Luxury Spa & Wellness</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Immersive clinical thermal suites and indigenous, organic custom therapy tracks.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="rooms" class="max-w-7xl mx-auto px-6 py-12">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Accommodations</p>
                <h2 class="text-3xl md:text-4xl font-serif text-neutral-900">Designed For Your Relaxation</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($roomsLiveList as $room)
                <div class="bg-white border border-neutral-200 group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
                    <div>
                        <div class="h-48 overflow-hidden relative bg-neutral-100">
                            <img src="{{ $room->foto_url }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                            <div class="absolute top-3 left-3">
                                <span class="text-[8px] tracking-widest font-bold uppercase px-2 py-0.5 text-white {{ $room->available_count > 0 ? 'bg-emerald-800' : 'bg-red-800' }}">
                                    {{ $room->available_count > 0 ? 'Vacant' : 'Sold Out' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-base font-bold text-neutral-900 mb-1 uppercase tracking-wide">{{ $room->name }}</h3>
                            <p class="text-amber-800 font-bold text-sm mb-3">Rp {{ number_format($room->price_per_night, 0, ',', '.') }} <span class="text-neutral-400 font-normal text-xs">/ night</span></p>
                            <p class="text-neutral-500 text-xs leading-relaxed mb-4 line-clamp-2">{{ $room->description }}</p>
                            
                          
                        </div>
                    </div>
                    
                    <div class="p-5 pt-0">
                        <div class="grid grid-cols-2 gap-2">
                            <a href="{{ route('rooms.show', $room->id) }}" class="border border-neutral-300 text-center py-2 text-neutral-800 text-[10px] font-bold uppercase tracking-wider hover:border-neutral-900 transition-colors">
                                Details
                            </a>
                            <a href="{{ route('rooms') }}" class="py-2 bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-wider transition-all text-center">
                                View Tariffs
                            </a>
                        </div>
                    </div>

                </div>
                @empty
                <div class="col-span-4 p-8 text-center bg-white border">
                    <p class="text-xs italic text-neutral-400">Belum ada konfigurasi tipe kamar riil di dalam database ledger.</p>
                </div>
                @endforelse
            </div>
        </section>

        <section id="facilities" class="bg-neutral-900 text-white py-24 px-6 border-y border-neutral-800">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
                    <div>
                        <p class="text-xs uppercase tracking-widest font-bold text-amber-400 mb-2">Resort Experience</p>
                        <h2 class="text-3xl md:text-5xl font-serif">Elevate Your Stay With Premium Facilities</h2>
                    </div>
                    <div>
                        <p class="text-neutral-400 text-sm leading-relaxed max-w-md">
                            Dari kolam renang tanpa batas yang menghadap langsung ke cakrawala hingga pusat kebugaran mutakhir, setiap sudut Oasis dirancang untuk memulihkan energi Anda.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300">
                        <div class="text-3xl text-amber-400 mb-6"><i class="fa-solid fa-person-swimming"></i></div>
                        <h3 class="text-lg font-bold uppercase tracking-wider mb-2">Swimming Pool</h3>
                        <p class="text-neutral-500 text-xs leading-relaxed">Kolam renang air hangat luar ruangan dengan pemandangan lanskap alam yang spektakuler.</p>
                    </div>
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300">
                        <div class="text-3xl text-amber-400 mb-6"><i class="fa-solid fa-spa"></i></div>
                        <h3 class="text-lg font-bold uppercase tracking-wider mb-2">Spa & Wellness</h3>
                        <p class="text-neutral-500 text-xs leading-relaxed">Perawatan tubuh dan pijat relaksasi tradisional yang ditangani langsung oleh terapis profesional.</p>
                    </div>
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300">
                        <div class="text-3xl text-amber-400 mb-6"><i class="fa-solid fa-dumbbell"></i></div>
                        <h3 class="text-lg font-bold uppercase tracking-wider mb-2">Fitness Center</h3>
                        <p class="text-neutral-500 text-xs leading-relaxed">Pusat kebugaran 24 jam dengan peralatan kardio dan beban berstandar internasional.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="restaurant" class="max-w-7xl mx-auto px-6 py-24">
            <div class="flex flex-col md:flex-row items-end justify-between mb-16 gap-4">
                <div>
                    <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Gastronomy Operations</p>
                    <h2 class="text-3xl md:text-4xl font-serif text-neutral-900">Fine Dining Atmosphere</h2>
                </div>
                <a href="{{ route('restaurant') }}" class="text-xs font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700 transition-colors">
                    View Full Menu &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($culinaryMenus as $menu)
                <div class="border border-neutral-200 bg-white group flex flex-col justify-between">
                    <div>
                        <div class="h-56 overflow-hidden bg-neutral-100">
                            <img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                        </div>
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">{{ $menu->name }}</h3>
                                <span class="text-xs font-bold text-amber-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-neutral-500 text-xs leading-relaxed">{{ $menu->description }}</p>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <button type="button" onclick="openOrderModal('{{ $menu->name }}', {{ $menu->price }})" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-3 transition-all cursor-pointer">
                            Order To Room
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <div id="culinaryOrderModal" class="fixed inset-0 z-50 overflow-y-auto hidden flex items-center justify-center p-4 bg-neutral-950/40 backdrop-blur-sm">
            <div class="bg-white max-w-sm w-full border border-neutral-200 p-8 shadow-2xl relative">
                <button type="button" onclick="closeOrderModal()" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-900"><i class="fa-solid fa-xmark"></i></button>
                <div class="mb-4">
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-700 block">Suite Gastronomy Service</span>
                    <h3 id="modal-food-title" class="text-lg font-serif text-neutral-900 mt-1">Item Name</h3>
                </div>
                <form id="gastronomy-ajax-form" action="{{ route('restaurant.order') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="final-invoice-price" name="total_price">
                    <div class="flex items-center justify-between border-y border-neutral-100 py-3">
                        <span class="text-xs font-bold text-neutral-700 uppercase tracking-wider">Portion Quantity</span>
                        <div class="flex items-center border border-neutral-300">
                            <button type="button" onclick="changeQty(-1)" class="px-3 py-1 text-xs font-bold hover:bg-neutral-100">-</button>
                            <input type="text" id="display-qty" value="1" readonly class="w-10 text-center border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800">
                            <button type="button" onclick="changeQty(1)" class="px-3 py-1 text-xs font-bold hover:bg-neutral-100">+</button>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider">
                        <span>Total Price</span>
                        <span id="display-total-cost" class="text-amber-800 font-mono text-sm">Rp 0</span>
                    </div>
                    <div id="modal-response-message" class="hidden p-3 text-[10px] font-bold uppercase tracking-wider"></div>
                    <button type="submit" id="modal-submit-btn" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-3.5 transition-all shadow-md cursor-pointer">
                        Confirm Order To Room
                    </button>
                </form>
            </div>
        </div>

        <section id="experiences" class="bg-neutral-50 py-24 px-6 border-t border-neutral-100">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Curated Journeys</p>
                    <h2 class="text-3xl md:text-4xl font-serif text-neutral-900">Unforgettable Local Experiences</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2073" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Sunset Dinner</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?q=80&w=2070" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Private Yacht Tour</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Couple Retreat</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1502082553048-f009c37129b9?q=80&w=2070" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Family Activities</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=2038" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Cultural Tours</h4>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-4xl mx-auto px-6 py-24 text-center">
            <div class="text-amber-700 text-2xl mb-4"><i class="fa-solid fa-quote-left"></i></div>
            <p class="font-serif italic text-xl md:text-2xl text-neutral-800 leading-relaxed mb-6">
                "Sebuah pengalaman menginap yang luar biasa. Desain arsitekturnya yang minimalis dipadukan dengan pelayanan kamar yang super cepat membuat liburan kami terasa sangat sempurna."
            </p>
            <div class="flex items-center justify-center space-x-1 text-amber-500 text-xs mb-2">
                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
            </div>
            <p class="text-xs uppercase tracking-widest font-bold text-neutral-800">Alexander V. <span class="text-neutral-400 font-normal">&mdash; Verified Ritz-Carlton Patron</span></p>
        </section>

        <section class="px-6 py-12 bg-white border-t border-neutral-100">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="space-y-4">
                        <img class="w-full object-cover h-40 border border-neutral-100" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=500" alt="Lobby Area">
                        <img class="w-full object-cover h-72 border border-neutral-100" src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=500" alt="Luxury Room View">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-80 border border-neutral-100" src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=500" alt="Restaurant Interior">
                        <img class="w-full object-cover h-36 border border-neutral-100" src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=500" alt="Executive Suite Bed">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-44 border border-neutral-100" src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=500" alt="Outdoor Lounge Area">
                        <img class="w-full object-cover h-64 border border-neutral-100" src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=500" alt="Poolside Deck">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-72 border border-neutral-100" src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=500" alt="Family Suite Living">
                        <img class="w-full object-cover h-40 border border-neutral-100" src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?q=80&w=500" alt="Yacht Deck Deck">
                    </div>
                </div>
            </div>
        </section>

        <section id="offers" class="max-w-7xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Exclusives</p>
                <h2 class="text-3xl md:text-4xl font-serif text-neutral-900">Seasonal Packages</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Honeymoon Package</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Complimentary organic champagne, customized flower configuration, and 1 private beachfront dinner track.</p>
                    </div>
                    
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Weekend Escape</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Guaranteed dynamic 3PM late check-out privileges and premium signature cocktail vouchers per guest.</p>
                    </div>
          
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Family Staycation</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Adjoining suite configurations, custom daily kids activity entry, and full premium breakfast inclusions.</p>
                    </div>
                
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Corporate Package</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Seamless boardroom allocations, executive visual setup access, and tailored private dining arrangements.</p>
                    </div>
            
                </div>
            </div>
        </section>

        <section id="contact" class="bg-neutral-50 border-t border-neutral-200 py-24 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Location</p>
                    <h2 class="text-3xl font-serif text-neutral-900 mb-6">Pristine Isolation, Effortless Access</h2>
                    <p class="text-neutral-600 text-sm leading-relaxed mb-6">
                        Located securely within the coastal enclave of Bali, Oasis offers full geological privacy away from urban intersections while maintaining rapid transit linkages.
                    </p>
                    <div class="space-y-4 text-xs font-medium text-neutral-700">
                        <div><i class="fa-solid fa-location-dot text-amber-700 w-5"></i> Jl. Pantai Indah No. 88, Nusa Dua, Bali, Indonesia</div>
                        <div><i class="fa-solid fa-plane text-amber-700 w-5"></i> Ngurah Rai International Airport &mdash; 25 Minutes Private Fleet Transit</div>
                        <div><i class="fa-solid fa-map-pin text-amber-700 w-5"></i> Proximity: Uluwatu Temple (20m), Pandawa Horizon Beach (5m)</div>
                    </div>
                </div>
                <div id="liveOasisMap" class="w-full h-80 bg-neutral-200 border border-neutral-300 shadow-md z-10"></div>
            </div>
        </section>

        <section class="max-w-4xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Information</p>
                <h2 class="text-3xl font-serif text-neutral-900">Frequently Asked Queries</h2>
            </div>
            <div class="space-y-4">
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-wide mb-1">What are the precise Check-in and Check-out configurations?</h4>
                    <p class="text-neutral-500 text-xs leading-relaxed">Standard arrival authorization commences at 3:00 PM. Room release and validation must conclude by 12:00 PM. Early arrival matrices can be requested through concierge channels.</p>
                </div>
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-wide mb-1">What is the structural Cancellation Directive?</h4>
                    <p class="text-neutral-500 text-xs leading-relaxed">Complimentary modification or cancellation parameters are fully valid up to 48 hours prior to your scheduled calendar interface without penalty fees.</p>
                </div>
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-sm font-bold text-neutral-800 uppercase tracking-wide mb-1">Are domestic animals permitted within suite enclosures?</h4>
                    <p class="text-neutral-500 text-xs leading-relaxed">Oasis operates dedicated pet-friendly villa wings. Advanced reservation notification is mandatory to install premium animal bedding and amenity packages.</p>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    var map = L.map('liveOasisMap').setView([-8.8034, 115.2126], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    var marker = L.marker([-8.8034, 115.2126]).addTo(map);
    marker.bindPopup("<b>Oasis Premium Resort</b><br>Nusa Dua, Bali.").openPopup();
</script>

<script>
    const orderModalBox = document.getElementById('culinaryOrderModal');
    const foodTitleLabel = document.getElementById('modal-food-title');
    const invoicePriceInput = document.getElementById('final-invoice-price');
    const qtyCountInput = document.getElementById('display-qty');
    const totalCostLabel = document.getElementById('display-total-cost');
    const resAlertBox = document.getElementById('modal-response-message');
    const culinaryForm = document.getElementById('gastronomy-ajax-form');
    const actionSubmitBtn = document.getElementById('modal-submit-btn');

    let rawFoodPrice = 0;

    function openOrderModal(itemName, unitPrice) {
        rawFoodPrice = unitPrice;
        foodTitleLabel.innerText = itemName;
        qtyCountInput.value = 1;
        recalculateInvoiceCost();
        resAlertBox.classList.add('hidden');
        orderModalBox.classList.remove('hidden');
    }

    function closeOrderModal() { 
        orderModalBox.classList.add('hidden'); 
    }

    function changeQty(delta) {
        let targetAmount = parseInt(qtyCountInput.value) + delta;
        if (targetAmount >= 1 && targetAmount <= 10) {
            qtyCountInput.value = targetAmount;
            recalculateInvoiceCost();
        }
    }

    function recalculateInvoiceCost() {
        let combinedSum = rawFoodPrice * parseInt(qtyCountInput.value);
        invoicePriceInput.value = combinedSum;
        totalCostLabel.innerText = 'Rp ' + combinedSum.toLocaleString('id-ID');
    }

    culinaryForm.addEventListener('submit', function (e) {
        e.preventDefault();
        actionSubmitBtn.disabled = true;
        actionSubmitBtn.innerText = "Transmitting Order Request...";

        fetch(culinaryForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(culinaryForm)
        })
        .then(async res => {
            const serverData = await res.json();
            actionSubmitBtn.disabled = false;
            actionSubmitBtn.innerText = "Confirm Order To Room";
            resAlertBox.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'border');

            if (res.ok && serverData.success) {
                resAlertBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                resAlertBox.innerText = serverData.message;
                setTimeout(() => { closeOrderModal(); }, 2000);
            } else {
                resAlertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                resAlertBox.innerText = serverData.message || "Pemesanan ditolak oleh server internal.";
            }
        })
        .catch(() => {
            actionSubmitBtn.disabled = false;
            actionSubmitBtn.innerText = "Confirm Order To Room";
            resAlertBox.classList.remove('hidden');
            resAlertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
            resAlertBox.innerText = "Terjadi gangguan transmisi jaringan lokal.";
        });
    });
</script>
