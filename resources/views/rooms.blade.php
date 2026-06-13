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

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <div class="bg-white border border-neutral-200 p-5 rounded-none shadow-xl grid grid-cols-1 md:grid-cols-6 gap-4 items-center">
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check In</label>
                    <input type="date" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Check Out</label>
                    <input type="date" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent cursor-pointer">
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Guests</label>
                    <select class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                        <option>1 Guest</option>
                        <option selected>2 Guests, 1 Room</option>
                        <option>4 Guests, 2 Rooms</option>
                    </select>
                </div>
                <div class="border-b md:border-b-0 md:border-r border-neutral-200 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Room Type</label>
                    <select class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 appearance-none bg-transparent cursor-pointer">
                        <option selected>All Room Types</option>
                        <option>Standard Room</option>
                        <option>Deluxe Room</option>
                        <option>Executive Suite</option>
                        <option>Presidential Suite</option>
                    </select>
                </div>
                <div class="border-b md:border-b-0 pb-3 md:pb-0 md:pr-3">
                    <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Promo Code</label>
                    <input type="text" placeholder="Optional" class="w-full border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 placeholder-neutral-300 bg-transparent">
                </div>
                <div>
                    <button class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-3.5 rounded-none transition-colors">
                        Check Availability
                    </button>
                </div>
            </div>
        </section>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b border-neutral-200 pb-4 mb-8 text-xs font-medium text-neutral-500 gap-4">
                <div class="flex flex-wrap gap-6 items-center uppercase tracking-wider text-[11px]">
                    <div>Available Inventory: <span class="font-bold text-neutral-900">24 Rooms</span></div>
                    <div>Avg Rating: <span class="font-bold text-neutral-900"><i class="fa-solid fa-star text-amber-500 mr-0.5"></i> 4.9/5.0</span></div>
                    <div class="text-amber-800 font-bold"><i class="fa-solid fa-shield-halved"></i> Best Price Guarantee</div>
                </div>
                <div class="flex items-center space-x-2 w-full md:w-auto justify-between md:justify-end">
                    <label class="uppercase tracking-widest text-[10px] font-bold text-neutral-400">Sort By:</label>
                    <select class="border border-neutral-300 bg-white text-[11px] font-bold uppercase tracking-wider px-3 py-1.5 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer">
                        <option>Recommended</option>
                        <option>Lowest Price</option>
                        <option>Highest Price</option>
                        <option>Highest Rated</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <aside class="w-full lg:w-1/4 bg-white border border-neutral-200 p-6 rounded-none space-y-6 sticky top-28">
                    <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Filter Rooms</h3>
                        <button type="reset" class="text-[10px] uppercase tracking-wider font-bold text-neutral-400 hover:text-neutral-900 underline">Clear All</button>
                    </div>

                    <div>
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-3">Price Range (IDR)</h4>
                        <div class="h-1 bg-neutral-200 relative mb-4">
                            <div class="absolute h-full bg-neutral-900 left-0 right-0"></div>
                        </div>
                        <div class="flex justify-between text-[10px] font-bold text-neutral-500">
                            <span>Rp 500.000</span>
                            <span>Rp 5.000.000+</span>
                        </div>
                    </div>

                    <div class="space-y-2 border-t border-neutral-100 pt-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-2">Room Category</h4>
                        @foreach(['Standard Room', 'Deluxe Room', 'Family Room', 'Executive Suite', 'Ocean Suite', 'Presidential Suite'] as $cat)
                            <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer hover:text-neutral-900">
                                <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2.5">
                                {{ $cat }}
                            </label>
                        @endforeach
                    </div>

                    <div class="space-y-2 border-t border-neutral-100 pt-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-2">View Type</h4>
                        @foreach(['Ocean View', 'City View', 'Pool View', 'Garden View'] as $view)
                            <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer hover:text-neutral-900">
                                <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2.5">
                                {{ $view }}
                            </label>
                        @endforeach
                    </div>

                    <div class="space-y-2 border-t border-neutral-100 pt-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-2">Room Amenities</h4>
                        <div class="grid grid-cols-1 gap-2 max-h-40 overflow-y-auto pr-2">
                            @foreach(['Free Wifi', 'Air Conditioning', 'Smart TV Console', 'Luxury Bathtub', 'Private Balcony', 'Espresso Machine', 'Mini Bar Lounge', 'Dedicated Work Desk'] as $amenity)
                                <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer hover:text-neutral-900">
                                    <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2.5">
                                    {{ $amenity }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-t border-neutral-100 pt-4 space-y-3">
                        <label class="flex justify-between items-center text-xs font-medium text-neutral-600 cursor-pointer">
                            <span>Instant Booking Only</span>
                            <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-4 h-4">
                        </label>
                        <label class="flex justify-between items-center text-xs font-medium text-neutral-600 cursor-pointer">
                            <span>Free Cancellation Inclusions</span>
                            <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-4 h-4">
                        </label>
                    </div>
                </aside>

            <section class="w-full lg:w-3/4">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[1100px] overflow-y-auto pr-2 custom-scrollbar">
        
        @php
        $roomsList = [
            ['name' => 'Standard Room', 'img' => 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?q=80&w=600', 'price' => '600.000', 'view' => 'City View', 'bed' => '1 Queen Bed', 'size' => '24 m²', 'desc' => 'Affordable sleek minimalist comfort integrated with standard urban panoramic systems.'],
            ['name' => 'Deluxe Room', 'img' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?q=80&w=600', 'price' => '850.000', 'view' => 'Partial Sea View', 'bed' => '1 King Bed', 'size' => '28 m²', 'desc' => 'Premium structural interiors featuring private viewing balconies and bespoke vanity sets.'],
            ['name' => 'Family Room', 'img' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=600', 'price' => '1.200.000', 'view' => 'Garden View', 'bed' => '2 Double Beds', 'size' => '40 m²', 'desc' => 'Larger architectural layout options designed specifically for family-centric requirements.'],
            ['name' => 'Executive Suite', 'img' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?q=80&w=600', 'price' => '1.650.000', 'view' => 'Ocean Horizon View', 'bed' => '1 Elite King Bed', 'size' => '45 m²', 'desc' => 'Spacious separated living configurations paired with 24-hour assigned concierge execution.'],
            ['name' => 'Ocean Suite', 'img' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?q=80&w=600', 'price' => '2.450.000', 'view' => 'Unobstructed Ocean View', 'bed' => '1 Master King Bed', 'size' => '60 m²', 'desc' => 'Panoramic oceanside architecture equipped with custom soaking stone tubs and fine bars.'],
            ['name' => 'Presidential Suite', 'img' => 'https://images.unsplash.com/photo-1540518614846-7eded433c457?q=80&w=600', 'price' => '4.850.000', 'view' => 'Private Beach View', 'bed' => '2 Grand King Beds', 'size' => '120 m²', 'desc' => 'The absolute scale of luxury hospitality. Features isolated lap pools and private culinary chefs.']
        ];
        @endphp

        @foreach($roomsList as $room)
        <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
            <div>
                <div class="h-56 overflow-hidden relative bg-neutral-100">
                    <img src="{{ $room['img'] }}" alt="{{ $room['name'] }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                    <button class="absolute top-4 right-4 w-8 h-8 bg-white/80 backdrop-blur-md rounded-none flex items-center justify-center text-neutral-500 hover:text-red-600 transition-colors">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    <div class="absolute bottom-4 left-4 flex flex-col gap-1.5 items-start">
                        <span class="bg-neutral-900/90 backdrop-blur-md text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded-none">Oasis Exclusive</span>
                        <span class="bg-emerald-800 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none">Breakfast Included</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex justify-between items-baseline mb-2">
                        <h3 class="text-base font-bold tracking-tight text-neutral-900 uppercase font-sans">{{ $room['name'] }}</h3>
                        <div class="flex items-center text-[10px] font-bold text-neutral-800">
                            <i class="fa-solid fa-star text-amber-500 mr-1"></i> 4.9 <span class="text-neutral-400 font-normal ms-0.5">(32)</span>
                        </div>
                    </div>
                    <p class="text-neutral-400 text-[11px] leading-relaxed mb-4">{{ $room['desc'] }}</p>
                    
                    <div class="grid grid-cols-2 gap-y-2 border-t border-b border-neutral-100 py-3 text-[10px] font-bold uppercase tracking-wider text-neutral-500">
                        <div><i class="fa-solid fa-expand text-amber-800 w-4 mr-1"></i> {{ $room['size'] }}</div>
                        <div><i class="fa-solid fa-bed text-amber-800 w-4 mr-1"></i> {{ $room['bed'] }}</div>
                        <div class="col-span-2"><i class="fa-solid fa-compass text-amber-800 w-4 mr-1"></i> {{ $room['view'] }}</div>
                    </div>
                </div>
            </div>

            <div class="p-6 pt-0">
                <div class="flex justify-between items-end mb-4">
                    <div class="text-[10px] uppercase tracking-widest text-neutral-400 font-bold">Price Per Night:</div>
                    <div class="text-right">
                        <div class="text-xs text-neutral-300 line-through font-medium">Rp {{ number_format(floatval(str_replace('.', '', $room['price'])) * 1.15, 0, ',', '.') }}</div>
                        <div class="text-base font-bold text-amber-800">Rp {{ $room['price'] }} <span class="text-neutral-400 font-normal text-[10px] tracking-normal">/ night</span></div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button class="border border-neutral-300 hover:border-neutral-900 text-neutral-800 text-[10px] font-bold uppercase tracking-widest py-3 rounded-none transition-colors">View Details</button>
                    <button class="bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-3 rounded-none transition-all">Book Now</button>
                </div>
            </div>
        </div>
        @endforeach

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
                        <button class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                            Take Virtual Tour
                        </button>
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
                            <th class="p-4 font-bold">Dimension</th>
                            <th class="p-4 font-bold">Max Occupancy</th>
                            <th class="p-4 font-bold">Core Inclusions</th>
                            <th class="p-4 font-bold">Pricing Model</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        <tr>
                            <td class="p-4 font-bold text-neutral-900">Standard Room</td>
                            <td class="p-4">24 m²</td>
                            <td class="p-4">2 Adults</td>
                            <td class="p-4">Wifi, Smart TV, City View</td>
                            <td class="p-4 font-bold text-amber-800">Rp 600.000 / night</td>
                        </tr>
                        <tr class="bg-neutral-50/50">
                            <td class="p-4 font-bold text-neutral-900">Deluxe Room</td>
                            <td class="p-4">28 m²</td>
                            <td class="p-4">2 Adults</td>
                            <td class="p-4">Balcony, Espresso Gear, Mini Bar</td>
                            <td class="p-4 font-bold text-amber-800">Rp 850.000 / night</td>
                        </tr>
                        <tr>
                            <td class="p-4 font-bold text-neutral-900">Executive Suite</td>
                            <td class="p-4">45 m²</td>
                            <td class="p-4">3 Adults</td>
                            <td class="p-4">Separate Living, Bath, 24/7 Butler</td>
                            <td class="p-4 font-bold text-amber-800">Rp 1.650.000 / night</td>
                        </tr>
                        <tr class="bg-neutral-50/50">
                            <td class="p-4 font-bold text-neutral-900">Presidential Suite</td>
                            <td class="p-4">120 m²</td>
                            <td class="p-4">5 Adults</td>
                            <td class="p-4">Private Pool, Full Kitchen, Fleet Transit</td>
                            <td class="p-4 font-bold text-amber-800">Rp 4.850.000 / night</td>
                        </tr>
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