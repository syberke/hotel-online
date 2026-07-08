<style>
    /* Mengubah scrollbar bawaan browser menjadi minimalis tipis sewarna tema Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #faf9f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #d4d4d4; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #737373; 
    }
</style>

<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[65vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=2070&auto=format&fit=crop" 
                 alt="Oasis Luxury Suites" 
                 class="w-full h-full object-cover opacity-60 scale-105">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 mb-4 font-bold">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Rooms & Suites</span>
                    </nav>
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Our Sanctuaries</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none mb-4">
                        Rooms Designed<br><span class="font-serif italic font-normal text-amber-100">For Your Comfort</span>
                    </h1>
                    <p class="text-neutral-300 text-xs md:text-sm max-w-xl font-medium leading-relaxed">
                        Discover elegant accommodations crafted to provide exceptional comfort, luxury, and unforgettable experiences.
                    </p>
                </div>
            </div>
        </header>

        <section id="booking-bar" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <form action="{{ route('rooms') }}" method="GET" class="bg-white border border-neutral-200 p-5 rounded-none shadow-xl grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check In</label>
                    <input type="date" name="check_in" value="{{ request('check_in', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check Out</label>
                    <input type="date" name="check_out" value="{{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Guests</label>
                    <select name="guests" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                        <option {{ request('guests') == '1 Adult' ? 'selected' : '' }}>1 Adult</option>
                        <option {{ request('guests') == '2 Adults, 1 Room' || !request('guests') ? 'selected' : '' }}>2 Adults, 1 Room</option>
                        <option {{ request('guests') == '4 Guests, 2 Rooms' ? 'selected' : '' }}>4 Guests, 2 Rooms</option>
                    </select>
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Room Type</label>
                    <select name="suite_type" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                        <option value="All Room Types">All Room Types</option>
                        @foreach($allCategories as $cat)
                            <option value="{{ $cat->name }}" {{ request('suite_type') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="border-b md:border-b-0 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Promo Code</label>
                    <input type="text" placeholder="Optional" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 placeholder-neutral-300 bg-transparent">
                </div>
                <div>
                    <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-3.5 rounded-none transition-colors">
                        Search & Filter
                    </button>
                </div>
            </form>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <form action="{{ route('rooms') }}" method="GET">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-neutral-200 pb-4 mb-8 text-xs font-medium text-neutral-500 gap-4">
                    <div class="flex flex-wrap gap-6 items-center uppercase tracking-wider text-[11px]">
                        <div>Available Inventory: <span class="font-bold text-neutral-900">{{ $totalInventoryReady }} Rooms</span></div>
                      
                        <div class="text-amber-800 font-bold"><i class="fa-solid fa-shield-halved"></i> Best Price Guarantee</div>
                    </div>
                    <div class="flex items-center space-x-2 w-full md:w-auto justify-between md:justify-end">
                        <label class="uppercase tracking-widest text-[10px] font-bold text-neutral-400">Sort By:</label>
                        <select name="sort" onchange="this.form.submit()" class="border border-neutral-300 bg-white text-[11px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer">
                            <option {{ request('sort') == 'Recommended' ? 'selected' : '' }}>Recommended</option>
                            <option {{ request('sort') == 'Lowest Price' ? 'selected' : '' }}>Lowest Price</option>
                            <option {{ request('sort') == 'Highest Price' ? 'selected' : '' }}>Highest Price</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    <aside class="w-full lg:w-1/4 bg-white border border-neutral-200 p-6 rounded-none space-y-6 sticky top-28">
                        <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Filter Rooms</h3>
                            <a href="{{ route('rooms') }}" class="text-[10px] uppercase tracking-wider font-bold text-neutral-400 hover:text-neutral-900 underline">Clear All</a>
                        </div>

                        <div>
                            <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-3">Price Range (IDR)</h4>
                            <div class="h-1 bg-neutral-200 relative mb-4">
                                <div class="absolute h-full bg-neutral-900 left-0 right-0"></div>
                            </div>
                            <div class="flex justify-between text-[10px] font-bold text-neutral-500">
                                <span>Rp 600.000</span>
                                <span>Rp 5.000.000+</span>
                            </div>
                        </div>

                        <div class="space-y-2 border-t border-neutral-100 pt-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-2">Room Category</h4>
                            @foreach($allCategories as $cat)
                                <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer hover:text-neutral-900 mb-1.5">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->name }}" onchange="this.form.submit()" 
                                        {{ is_array(request('categories')) && in_array($cat->name, request('categories')) ? 'checked' : '' }}
                                        class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2.5">
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                    </aside>
            </form>

           <section class="w-full lg:w-3/4">
    
    <div class="max-h-[850px] overflow-y-auto pr-2 custom-scrollbar">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            @forelse($roomsLiveList as $room)
            <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
                <div>
                    <div class="h-56 overflow-hidden relative bg-neutral-100">
                        <img src="{{ $room->foto_url }}" alt="{{ $room->name }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                   
                        <div class="absolute bottom-4 left-4 flex flex-col gap-1.5 items-start">
                            <span class="bg-neutral-900/90 backdrop-blur-md text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-none">Oasis Exclusive</span>
                            <span class="text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none {{ $room->available_count > 0 ? 'bg-emerald-800' : 'bg-red-800' }}">
                                {{ $room->available_count > 0 ? 'Ready Room In-House' : 'Fully Booked Today' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between items-baseline mb-2">
                            <h3 class="text-base font-bold tracking-tight text-neutral-900 uppercase font-sans">{{ $room->name }}</h3>
                          
                        </div>
                        <p class="text-neutral-400 text-[11px] leading-relaxed mb-4 line-clamp-2">{{ $room->description }}</p>
                    </div>
                </div>

                <div class="p-6 pt-0">
                    <div class="flex justify-between items-end mb-4">
                        <div class="text-[10px] uppercase tracking-widest text-neutral-400 font-bold">Price Per Night:</div>
                        <div class="text-right">
                            <div class="text-base font-bold text-amber-800">Rp {{ number_format($room->price_per_night, 0, ',', '.') }} <span class="text-neutral-400 font-normal text-[10px] tracking-normal">/ night</span></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 items-stretch">
                        <a href="{{ route('rooms.show', $room->id) }}" class="flex items-center justify-center border border-neutral-300 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-3 rounded-none text-center transition-colors h-full">
                            View Details
                        </a>
                       <div class="h-full">
    @if($room->available_count > 0)
        <a href="{{ route('rooms.show', $room->id) }}?check_in={{ request('check_in', date('Y-m-d')) }}&check_out={{ request('check_out', date('Y-m-d', strtotime('+1 day'))) }}&guests={{ request('guests', '2 Adults, 1 Room') }}" 
           class="flex items-center justify-center w-full bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-3 rounded-none transition-all text-center h-full">
            Book Now
        </a>
    @else
        <button type="button" disabled class="w-full bg-neutral-200 text-neutral-400 text-[10px] font-bold uppercase tracking-widest py-3 rounded-none cursor-not-allowed h-full">
            Sold Out
        </button>
    @endif
</div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-2 p-12 text-center bg-white border border-neutral-200">
                <p class="text-xs text-neutral-400 italic font-medium">No live accommodations match your chosen filters.</p>
            </div>
            @endforelse

        </div>
    </div>
</section>
        </div>
        </main>

        <section class="bg-white border-y border-neutral-200 py-24 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="h-[500px] overflow-hidden bg-neutral-100">
                    <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=1000" alt="Presidential Showcase" class="w-full h-full object-cover">
                </div>
                <div class="space-y-6">
                    <p class="text-xs uppercase tracking-[0.3em] font-bold text-amber-700">Signature Haven</p>
                    <h2 class="text-3xl md:text-5xl font-serif leading-tight">The Presidential Sanctuary</h2>
                    <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                        Mewakili puncak tertinggi dari kemewahan Oasis. Suite ini mengintegrasikan kolam renang tanpa batas pribadi seluas 12 meter, dua kamar tidur utama berlapis marmer Carrara, serta asisten pelayan (butler) pribadi yang bersertifikasi internasional siap melayani Anda 24 jam penuh.
                    </p>
                    <div class="grid grid-cols-2 gap-4 border-t border-neutral-100 pt-6 text-xs font-bold uppercase tracking-wider text-neutral-700">
                        <div><i class="fa-solid fa-circle-check text-amber-700 mr-2"></i> Private Helicopter Pad Access</div>
                        <div><i class="fa-solid fa-circle-check text-amber-700 mr-2"></i> Private Chef Allocation</div>
                        <div><i class="fa-solid fa-circle-check text-amber-700 mr-2"></i> Premium Wine Vault Entry</div>
                        <div><i class="fa-solid fa-circle-check text-amber-700 mr-2"></i> In-Villa Bespoke Spa Suite</div>
                    </div>
                    <div class="pt-4">
                       
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Specifications</p>
                <h2 class="text-3xl font-serif text-neutral-900">Compare Accommodations</h2>
            </div>
            <div class="overflow-x-auto border border-neutral-200">
                <table class="w-full border-collapse text-left bg-white text-xs">
                    <thead>
                        <tr class="bg-neutral-950 text-white uppercase tracking-wider text-[10px]">
                            <th class="p-4 font-bold">Suite Category</th>
                            <th class="p-4 font-bold">Pricing Model Ledger</th>
                            <th class="p-4 font-bold">House Allocation Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @foreach($roomsLiveList as $room)
                        <tr class="odd:bg-white even:bg-neutral-50/50">
                            <td class="p-4 font-bold text-neutral-900 uppercase tracking-wide">{{ $room->name }}</td>
                            <td class="p-4 font-bold text-amber-800 font-mono">Rp {{ number_format($room->price_per_night, 0, ',', '.') }} / night</td>
                            <td class="p-4 font-mono text-[11px]">
                                <span class="{{ $room->available_count > 0 ? 'text-emerald-700' : 'text-red-700' }} font-bold">
                                    {{ $room->available_count }} Units Vacant Right Now
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-neutral-50 border-t border-neutral-200 py-24 px-6">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16">
                    <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Exclusives</p>
                    <h2 class="text-3xl font-serif text-neutral-900">Curated Stay Packages</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white border border-neutral-200 p-8 flex flex-col justify-between rounded-none">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Honeymoon Enrichment</span>
                            <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">The Oasis Romance</h4>
                            <p class="text-neutral-400 text-xs leading-relaxed mb-6">Nikmati dekorasi bunga aromatik kustom, sebotol sampanye organik selamat datang, serta akses pijat spa privat 90 menit.</p>
                        </div>
                        <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Details &rarr;</a>
                    </div>
                    <div class="bg-white border border-neutral-200 p-8 flex flex-col justify-between rounded-none">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Long Stay Benefit</span>
                            <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">Extended Sanctuary Journey</h4>
                            <p class="text-neutral-400 text-xs leading-relaxed mb-6">Menginap minimal 5 malam dan dapatkan potongan harga eksklusif 20%, gratis penjemputan bandara, serta laundry harian.</p>
                        </div>
                        <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Details &rarr;</a>
                    </div>
                    <div class="bg-white border border-neutral-200 p-8 flex flex-col justify-between rounded-none">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Advance Purchase</span>
                            <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">Early Bird Promotion</h4>
                            <p class="text-neutral-400 text-xs leading-relaxed mb-6">Rencanakan pelarian liburan Anda 30 hari lebih awal untuk mengamankan tarif penawaran terbaik kamar impian Anda.</p>
                        </div>
                        <a href="#" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Details &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-3xl mx-auto px-6 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Faq</p>
                <h2 class="text-2xl md:text-3xl font-serif text-neutral-900">Stay Configurations & Policies</h2>
            </div>
            <div class="space-y-6">
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Berapa batas kapasitas maksimal ranjang ekstra (extra bed)?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Setiap tipe Suite (kecuali Standard) dapat mengajukan maksimal 1 ranjang ekstra per kamar dengan biaya tambahan IDR 450.000 nett per malam, sudah termasuk porsi sarapan pagi.</p>
                </div>
                <div class="border-b border-neutral-200 pb-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-800 mb-1">Bagaimana regulasi akomodasi untuk anak-anak?</h4>
                    <p class="text-neutral-500 text-[11px] leading-relaxed">Anak-anak berusia di bawah 11 tahun dapat menginap secara gratis menggunakan konfigurasi ranjang yang sudah tersedia di dalam tipe kamar pesanan orang tua.</p>
                </div>
            </div>
        </section>

        <section class="bg-neutral-900 text-white py-24 px-6 text-center border-t border-neutral-800">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Find Your Perfect Room Today</p>
                <h2 class="text-3xl md:text-4xl font-light tracking-tight mb-8">Choose from our carefully curated collection of luxury rooms and suites.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#booking-bar" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Book Your Suite Now
                    </a>
                    <a href="mailto:concierge@oasishotel.com" class="border border-neutral-700 hover:border-neutral-500 text-neutral-300 font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Contact Concierge
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>