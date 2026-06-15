<x-guest-layout>
    <div class="min-h-screen bg-[#fcfcfc] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[90vh] bg-neutral-950 overflow-hidden">
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
                        <a href="#booking-bar" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-4 px-8 transition-all text-center">
                            Book Your Stay
                        </a>
                        <a href="#rooms" class="border border-white/40 hover:border-white hover:bg-white/10 text-white font-bold text-xs uppercase tracking-widest py-4 px-8 transition-all text-center">
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

        <section id="booking-bar" class="max-w-6xl mx-auto px-6 relative -mt-16 z-20">
            <form action="{{ route('rooms.check') }}" method="POST" class="bg-white border border-neutral-200/80 p-6 rounded-none shadow-xl grid grid-cols-1 md:grid-cols-5 gap-6 items-center">
                @csrf
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-4 md:pb-0 md:pr-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check In</label>
                    <input type="date" name="check_in" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer text-neutral-800 bg-transparent">
                </div>

                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-4 md:pb-0 md:pr-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check Out</label>
                    <input type="date" name="check_out" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer text-neutral-800 bg-transparent">
                </div>

                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-4 md:pb-0 md:pr-4">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Guests</label>
                    <select name="guests" class="w-full border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer text-neutral-800 appearance-none bg-transparent">
                        <option value="1 Adult">1 Adult</option>
                        <option value="2 Adults, 1 Room" selected>2 Adults</option>
                        <option value="3 Adults, 1 Room">3 Adults</option>
                        <option value="4 Guests, 2 Rooms">4 Adults</option>
                    </select>
                </div>

                <div class="pb-4 md:pb-0">
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Suite Type</label>
                    <select name="suite_type" class="w-full border-none p-0 text-sm font-bold focus:ring-0 cursor-pointer text-neutral-800 appearance-none bg-transparent">
                        @foreach($roomsLiveList as $rl)
                            <option value="{{ $rl->name }}">{{ $rl->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 transition-all rounded-none">
                        Search Availability
                    </button>
                </div>
            </form>
        </section>

        <section class="max-w-7xl mx-auto px-6 pt-24 pb-12">
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
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-utensils"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Fine Dining Gastronomy</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Michelin-starred culinary development featuring local raw seasonal execution.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-car-side"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Airport Transfer</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Complimentary executive private fleet collection directly from the tarmac console.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="text-amber-700 text-xl"><i class="fa-solid fa-clock"></i></div>
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">24/7 Elite Concierge</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed">Instant secure deployment for private island charters, global event booking, and transport.</p>
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
                            
                            <div class="text-[10px] text-neutral-400 font-medium space-y-1 border-t pt-3">
                                <div><i class="fa-solid fa-wifi mr-1 text-amber-800"></i> Free High-Speed Wi-Fi</div>
                                <div><i class="fa-solid fa-circle-check mr-1 text-amber-800"></i> In-House Premium Amenity Kit</div>
                            </div>
                        </div>
                    </div>
                 <div class="p-5 pt-0">
    <form action="{{ route('rooms.check') }}" method="POST">
        @csrf
        <input type="hidden" name="check_in" value="{{ date('Y-m-d') }}">
        
        <input type="hidden" name="check_out" value="{{ date('Y-m-d', strtotime('+1 day')) }}">
        
        <input type="hidden" name="guests" value="2 Adults, 1 Room">
        <input type="hidden" name="suite_type" value="{{ $room->name }}">
        
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('rooms.show', $room->id) }}" class="border border-neutral-300 text-center py-2 text-neutral-800 text-[10px] font-bold uppercase tracking-wider hover:border-neutral-900 transition-colors">
                Details
            </a>
            <button type="submit" {{ $room->available_count == 0 ? 'disabled' : '' }} class="py-2 bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-wider disabled:bg-neutral-200 disabled:cursor-not-allowed transition-all">
                Book Now
            </button>
        </div>
    </form>
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
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300 group">
                        <div class="text-3xl text-amber-400 mb-6"><i class="fa-solid fa-person-swimming"></i></div>
                        <h3 class="text-lg font-bold uppercase tracking-wider mb-2">Swimming Pool</h3>
                        <p class="text-neutral-500 text-xs leading-relaxed">Kolam renang air hangat luar ruangan dengan pemandangan lanskap alam yang spektakuler.</p>
                    </div>
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300 group">
                        <div class="text-3xl text-amber-400 mb-6"><i class="fa-solid fa-spa"></i></div>
                        <h3 class="text-lg font-bold uppercase tracking-wider mb-2">Spa & Wellness</h3>
                        <p class="text-neutral-500 text-xs leading-relaxed">Perawatan tubuh dan pijat relaksasi tradisional yang ditangani langsung oleh terapis profesional.</p>
                    </div>
                    <div class="border border-neutral-800 p-8 rounded-none bg-neutral-950 hover:border-amber-400 transition-all duration-300 group">
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
                <a href="#" class="text-xs font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700 transition-colors">
                    View Full Menu &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="border border-neutral-200 bg-white group">
                    <div class="h-56 overflow-hidden bg-neutral-100">
                        <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop" 
                             alt="Wagyu Ribeye Steak" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">Wagyu Ribeye Steak</h3>
                            <span class="text-xs font-bold text-amber-700">Rp 375.000</span>
                        </div>
                        <p class="text-neutral-500 text-xs leading-relaxed">Daging wagyu pilihan panggang dengan saus jamur khas dan kentang tumbuk lembut.</p>
                    </div>
                </div>

                <div class="border border-neutral-200 bg-white group">
                    <div class="h-56 overflow-hidden bg-neutral-100">
                        <img src="https://images.unsplash.com/photo-1603133872878-6967b646c03b?q=80&w=2070&auto=format&fit=crop" 
                             alt="Oasis Fried Rice" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">Oasis Fried Rice</h3>
                            <span class="text-xs font-bold text-amber-700">Rp 95.000</span>
                        </div>
                        <p class="text-neutral-500 text-xs leading-relaxed">Nasi goreng tradisional kaya rempah disajikan dengan sate ayam, telur mata sapi, dan kerupuk udang.</p>
                    </div>
                </div>

                <div class="border border-neutral-200 bg-white group">
                    <div class="h-56 overflow-hidden bg-neutral-100">
                        <img src="https://images.unsplash.com/photo-1536935338788-846bb9981813?q=80&w=2072&auto=format&fit=crop" 
                             alt="Fresh Avocado Juice" class="w-full h-full object-cover group-hover:scale-103 transition-transform duration-500">
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">Fresh Avocado Juice</h3>
                            <span class="text-xs font-bold text-amber-700">Rp 45.000</span>
                        </div>
                        <p class="text-neutral-500 text-xs leading-relaxed">Jus alpukat mentega segar pilihan yang disajikan dingin dengan siraman susu cokelat premium.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="experiences" class="bg-neutral-50 py-24 px-6 border-t border-neutral-100">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Curated Journeys</p>
                    <h2 class="text-3xl md:text-4xl font-serif text-neutral-900">Unforgettable Local Experiences</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=2073&auto=format&fit=crop" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Sunset Dinner</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Private Yacht Tour</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Couple Retreat</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1502082553048-f009c37129b9?q=80&w=2070&auto=format&fit=crop" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                            <h4 class="text-white text-sm font-bold uppercase tracking-wider">Family Activities</h4>
                        </div>
                    </div>
                    <div class="relative h-80 group overflow-hidden bg-black">
                        <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?q=80&w=2038&auto=format&fit=crop" class="w-full h-full object-cover opacity-70 group-hover:scale-105 transition-all duration-500">
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
                        <img class="w-full object-cover h-40 border border-neutral-100" src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=500&auto=format&fit=crop" alt="Lobby Area">
                        <img class="w-full object-cover h-72 border border-neutral-100" src="https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=500&auto=format&fit=crop" alt="Luxury Room View">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-80 border border-neutral-100" src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=500&auto=format&fit=crop" alt="Restaurant Interior">
                        <img class="w-full object-cover h-36 border border-neutral-100" src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=500&auto=format&fit=crop" alt="Executive Suite Bed">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-44 border border-neutral-100" src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=500&auto=format&fit=crop" alt="Outdoor Lounge Area">
                        <img class="w-full object-cover h-64 border border-neutral-100" src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=500&auto=format&fit=crop" alt="Poolside Deck">
                    </div>
                    <div class="space-y-4">
                        <img class="w-full object-cover h-72 border border-neutral-100" src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=500&auto=format&fit=crop" alt="Family Suite Living">
                        <img class="w-full object-cover h-40 border border-neutral-100" src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?q=80&w=500&auto=format&fit=crop" alt="Yacht Deck Deck">
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
                    <a href="#" class="text-xs font-bold text-neutral-900 uppercase tracking-wider underline">Learn More</a>
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Weekend Escape</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Guaranteed dynamic 3PM late check-out privileges and premium signature cocktail vouchers per guest.</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-neutral-900 uppercase tracking-wider underline">Learn More</a>
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Family Staycation</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Adjoining suite configurations, custom daily kids activity entry, and full premium breakfast inclusions.</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-neutral-900 uppercase tracking-wider underline">Learn More</a>
                </div>
                <div class="border border-neutral-200 p-6 bg-neutral-50/50 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs uppercase tracking-widest font-bold text-neutral-800 mb-2">Corporate Package</h4>
                        <p class="text-neutral-500 text-xs leading-relaxed mb-4">Seamless boardroom allocations, executive visual setup access, and tailored private dining arrangements.</p>
                    </div>
                    <a href="#" class="text-xs font-bold text-neutral-900 uppercase tracking-wider underline">Learn More</a>
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
                <div class="w-full h-80 bg-neutral-200 border border-neutral-300 relative">
                    <div class="absolute inset-0 bg-cover bg-center opacity-70" style="background-image: url('https://api.mapbox.com/styles/v1/mapbox/light-v10/static/115.2126,-8.8034,12,0/600x400?access_token=mock')"></div>
                    <div class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center bg-white/40 backdrop-blur-sm">
                        <div class="w-10 h-10 bg-neutral-900 text-white rounded-full flex items-center justify-center shadow-lg mb-2 animate-bounce">
                            <i class="fa-solid fa-location-crosshairs"></i>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-neutral-800">Interactive Map Interface</span>
                    </div>
                </div>
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

        <section class="bg-neutral-900 text-white py-24 px-6 text-center border-t border-neutral-800">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Your Perfect Stay Awaits</p>
                <h2 class="text-3xl md:text-5xl font-light tracking-tight mb-8">Secure Your Absolute Sanctuary</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#booking-bar" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-4 px-8 transition-all rounded-none">
                        Reserve Now
                    </a>
                    <a href="mailto:concierge@oasis.com" class="border border-neutral-700 hover:border-neutral-500 text-neutral-300 font-bold text-xs uppercase tracking-widest py-4 px-8 transition-all rounded-none">
                        Contact Us
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>