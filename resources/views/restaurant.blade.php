<style>
    /* Desain scrollbar minimalis khusus area menu Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #faf9f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e5e5; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a3a3a3; 
    }
</style>
<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        @include('layouts.navigation')

        <header class="relative h-[65vh] bg-neutral-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=2069&auto=format&fit=crop" 
                 alt="Oasis Luxury Dining Atmosphere" 
                 class="w-full h-full object-cover opacity-50 scale-105">
            
            <div class="absolute inset-0 flex flex-col justify-center px-6">
                <div class="max-w-7xl mx-auto w-full text-white">
                    <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 mb-4 font-bold">
                        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
                        <span>/</span>
                        <span class="text-amber-400">Restaurant</span>
                    </nav>
                    <p class="text-xs uppercase tracking-[0.4em] font-bold text-amber-400 mb-3">Gastronomy & Culinary</p>
                    <h1 class="text-4xl md:text-6xl font-light tracking-tight leading-none mb-4">
                        Exceptional Dining,<br><span class="font-serif italic font-normal text-amber-100">Extraordinary Experiences</span>
                    </h1>
                    <p class="text-neutral-300 text-xs md:text-sm max-w-xl font-medium leading-relaxed mb-6">
                        Discover world-class cuisine crafted by our award-winning culinary team using the absolute finest, sustainable seasonal ingredients.
                    </p>
                    <a href="#reservation-block" class="inline-block bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-3.5 px-8 rounded-none transition-colors">
                        Reserve A Table
                    </a>
                </div>
            </div>
        </header>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative -mt-12 z-20">
            <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xl grid grid-cols-2 md:grid-cols-6 gap-6 text-center">
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-award"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Award Chefs</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-leaf"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Fresh Ingredients</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-utensils"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Fine Experience</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-water"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Ocean Views</h5>
                </div>
                <div class="border-r border-neutral-100 last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-wine-glass"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">Private Dining</h5>
                </div>
                <div class="last:border-none">
                    <div class="text-amber-800 text-lg mb-1"><i class="fa-solid fa-globe"></i></div>
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-800">International</h5>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-10">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-center border-b border-neutral-200 pb-12">
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">150+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Menu Items</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">5</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Dining Venues</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">4.9</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Guest Rating</div>
                </div>
                <div>
                    <div class="text-2xl font-light text-neutral-900 tracking-tight font-serif">20+</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">International Dishes</div>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <div class="text-2xl font-light text-amber-800 tracking-tight font-serif">15 Years</div>
                    <div class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mt-1">Culinary Excellence</div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Our Dining Venues</p>
                <h2 class="text-3xl font-serif text-neutral-900">A Culinary Journey for Every Taste</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @php
                $venues = [
                    ['name' => 'Oasis Signature Restaurant', 'img' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?q=80&w=600', 'hours' => '06:30 AM - 11:00 PM', 'loc' => 'Main Building - Level 1', 'desc' => 'All-day fine dining offering curated international fusion menus alongside secure live cooking configurations.'],
                    ['name' => 'Azure Rooftop Lounge', 'img' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?q=80&w=600', 'hours' => '04:00 PM - 01:00 AM', 'loc' => 'Rooftop - Level 8', 'desc' => 'Open-air sky panoramic lounge offering tailored premium mixology cocktails, aged wines, and light raw bites.'],
                    ['name' => 'Sandy Beach Grill', 'img' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=600', 'hours' => '11:00 AM - 10:00 PM', 'loc' => 'Beachfront Area', 'desc' => 'Casual luxury shoreside execution serving custom charcoal-grilled ocean catches and seasonal meats.'],
                    ['name' => 'The Private Dining Room', 'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=600', 'hours' => 'By Reservation Only', 'loc' => 'Main Building - Level 2', 'desc' => 'Bespoke high-end custom culinary hosting chambers for corporate alignments, private family structures, or VIP retreats.'],
                    ['name' => 'Poolside Bar & Cabana', 'img' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=600', 'hours' => '09:00 AM - 08:00 PM', 'loc' => 'Infinity Pool Deck', 'desc' => 'Submerged counter service and poolside cabana deliveries featuring organic juices and artisanal refreshments.']
                ];
                @endphp

                @foreach($venues as $venue)
                <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
                    <div>
                        <div class="h-56 overflow-hidden relative bg-neutral-100">
                            <img src="{{ $venue['img'] }}" alt="{{ $venue['name'] }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                        </div>
                        <div class="p-6">
                            <h3 class="text-base font-bold uppercase tracking-wider text-neutral-900 mb-2">{{ $venue['name'] }}</h3>
                            <p class="text-neutral-500 text-xs leading-relaxed mb-4">{{ $venue['desc'] }}</p>
                            <div class="text-[10px] text-neutral-400 font-bold uppercase tracking-wider space-y-1.5 border-t border-neutral-100 pt-3">
                                <div><i class="fa-regular fa-clock text-amber-800 w-4 mr-1"></i> {{ $venue['hours'] }}</div>
                                <div><i class="fa-solid fa-location-dot text-amber-800 w-4 mr-1"></i> {{ $venue['loc'] }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 pt-0">
                        <a href="#reservation-block" class="w-full text-center block bg-neutral-900 hover:bg-neutral-800 text-white text-[10px] font-bold uppercase tracking-widest py-3 rounded-none transition-colors">
                            Reserve Venue Table
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 border-t border-neutral-200">
            <div class="text-center mb-12">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">The Gastronomy Menu</p>
                <h2 class="text-3xl font-serif text-neutral-900">Curated Masterpiece Creations</h2>
            </div>

            <div class="flex flex-wrap justify-center gap-2 border-b border-neutral-200/60 pb-6 mb-8 text-[10px] font-bold uppercase tracking-widest">
                <button class="px-5 py-2 bg-neutral-900 text-white rounded-none">All Menu</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Appetizers</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Main Courses</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Seafood</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Steak Selection</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Desserts</button>
                <button class="px-5 py-2 text-neutral-500 hover:text-neutral-900 transition-colors">Wine Vault</button>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                
                <aside class="w-full lg:w-1/4 bg-white border border-neutral-200 p-6 rounded-none space-y-6 sticky top-28">
                    <div class="border-b border-neutral-100 pb-3">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Search & Options</h3>
                    </div>
                    <div>
                        <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Search Dishes</label>
                        <input type="text" placeholder="Type dish name..." class="w-full border border-neutral-300 px-3 py-2 text-xs rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                    </div>
                    <div class="space-y-2 border-t border-neutral-100 pt-4">
                        <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700 mb-2">Dietary Preference</h4>
                        @foreach(['Gluten-Free', 'Vegetarian', 'Nut-Free Certified', 'Halal Compliant'] as $diet)
                            <label class="flex items-center text-xs font-medium text-neutral-600 cursor-pointer hover:text-neutral-900">
                                <input type="checkbox" class="rounded-none border-neutral-300 text-neutral-900 focus:ring-neutral-900 w-3.5 h-3.5 me-2.5">
                                {{ $diet }}
                            </label>
                        @endforeach
                    </div>

                    <div class="border border-amber-800/20 bg-amber-50/40 p-4 rounded-none space-y-2 text-xs">
                        <h5 class="font-bold uppercase tracking-wider text-amber-900"><i class="fa-solid fa-bell-concierge"></i> 24/7 In-Room Delivery</h5>
                        <p class="text-neutral-600 text-[11px] leading-relaxed">All featured dishes can be directly ordered to your personal suite enclosure. Est transit time: 20-30 mins. Standard 10% service premium applies.</p>
                    </div>
                </aside>

                <section class="w-full lg:w-3/4">
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[960px] overflow-y-auto pr-2 custom-scrollbar">
        
        @php
        $dishes = [
            ['name' => 'Australian Wagyu Tenderloin', 'img' => 'https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=400', 'price' => '415.000', 'cat' => 'Steak Selection', 'cal' => '680 kcal', 'desc' => 'MBS 8-9 pure-bred wagyu center cut, served alongside charred truffle asparagus and master bone-marrow sauce reduction.'],
            ['name' => 'Lobster Thermidor', 'img' => 'https://images.unsplash.com/photo-1533777857889-4be7c70b33f7?q=80&w=400', 'price' => '380.000', 'cat' => 'Seafood', 'cal' => '540 kcal', 'desc' => 'Pristine native rock lobster tail out of local shells, blanketed in rich Cognac cream, wild chanterelles, and Gruyere gratin.'],
            ['name' => 'Pan-Seared Atlantic Salmon', 'img' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?q=80&w=400', 'price' => '245.000', 'cat' => 'Seafood', 'cal' => '420 kcal', 'desc' => 'Crisp-skinned cold-water salmon placed over clean saffron veloute, crushed fingerling potatoes, and micro-herbs.'],
            ['name' => 'Truffle Mushroom Risotto', 'img' => 'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?q=80&w=400', 'price' => '185.000', 'cat' => 'Main Courses', 'cal' => '510 kcal', 'desc' => 'Acquerello aged carnaroli rice slowly executed with local king oyster configurations, fresh shaved Umbrian black truffles, and Reggiano cream.'],
            ['name' => 'Signature Gold Cheesecake', 'img' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?q=80&w=400', 'price' => '95.000', 'cat' => 'Desserts', 'cal' => '380 kcal', 'desc' => 'Velvety Madagascar vanilla bean baked cheese matrix structured with a raw graham base and finalized with edible 24K gold foil lines.'],
            ['name' => 'Molten Chocolate Lava Cake', 'img' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?q=80&w=400', 'price' => '85.000', 'cat' => 'Desserts', 'cal' => '460 kcal', 'desc' => 'Decadent Valrhona 70% dark single-origin core cake emitting flowing warm centers, paired with house-churned organic pistachio gelato.']
        ];
        @endphp

        @foreach($dishes as $dish)
        <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
            <div>
                <div class="h-48 overflow-hidden relative bg-neutral-100">
                    <img src="{{ $dish['img'] }}" alt="{{ $dish['name'] }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                    <button class="absolute top-4 right-4 w-8 h-8 bg-white/80 backdrop-blur-md rounded-none flex items-center justify-center text-neutral-500 hover:text-neutral-900 transition-colors">
                        <i class="fa-regular fa-bookmark"></i>
                    </button>
                    <span class="absolute bottom-4 left-4 bg-amber-800 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none">
                        <i class="fa-solid fa-star text-amber-400 mr-0.5"></i> Chef Recommendation
                    </span>
                </div>
                <div class="p-5">
                    <div class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">{{ $dish['cat'] }}</div>
                    <h4 class="text-sm font-bold uppercase tracking-wide text-neutral-900 font-sans mb-1.5">{{ $dish['name'] }}</h4>
                    <p class="text-neutral-400 text-[11px] leading-relaxed mb-3">{{ $dish['desc'] }}</p>
                    <div class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest"><i class="fa-solid fa-fire-flame-curved text-neutral-400 mr-1"></i> Energy Value: {{ $dish['cal'] }}</div>
                </div>
            </div>
            <div class="p-5 pt-0">
                <div class="flex justify-between items-center border-t border-neutral-100 pt-3">
                    <div class="text-base font-bold text-amber-900">Rp {{ $dish['price'] }}</div>
                    <button class="bg-neutral-900 hover:bg-neutral-800 text-white text-[9px] font-bold uppercase tracking-widest py-2 px-4 rounded-none transition-colors">
                        Add To Order
                    </button>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</section>
            </div>
        </section>

        <section class="bg-white border-y border-neutral-200 py-24 px-6">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="h-[500px] overflow-hidden bg-neutral-100 border border-neutral-200">
                    <img src="https://images.unsplash.com/photo-1577219491135-ce391730fb2c?q=80&w=800" alt="Executive Chef Alessandro Romano" class="w-full h-full object-cover">
                </div>
                <div class="space-y-6">
                    <p class="text-xs uppercase tracking-[0.3em] font-bold text-amber-700">The Kitchen Mastermind</p>
                    <h2 class="text-3xl md:text-5xl font-serif leading-tight">Chef Alessandro Romano</h2>
                    <p class="text-neutral-500 text-xs md:text-sm leading-relaxed">
                        Membawa pengalaman lebih dari dua dekade memimpin dapur-dapur legendaris berlabel Michelin di Milan dan Tokyo. Chef Alessandro mendedikasikan hidupnya untuk mentransformasikan bahan baku lokal musiman yang jujur menjadi simfoni rasa modern yang mengelevasi standar gastronomi mewah dunia.
                    </p>
                    <blockquote class="border-l-2 border-amber-700 pl-4 font-serif italic text-neutral-700 text-sm md:text-base my-2">
                        "Culinary execution is an uncompromised art form. We do not just process fresh elements; we tell geological stories through taste layers."
                    </blockquote>
                    <div class="grid grid-cols-2 gap-4 border-t border-neutral-100 pt-6 text-xs font-bold uppercase tracking-wider text-neutral-400">
                        <div>Experience Matrix: <span class="text-neutral-900 font-bold">22+ Years</span></div>
                        <div>Global Awards: <span class="text-neutral-900 font-bold">3x Michelin Gold Medalist</span></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center mb-16">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Exclusive Events</p>
                <h2 class="text-3xl font-serif text-neutral-900">Special Dining Experiences</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="border border-neutral-200 p-8 bg-neutral-50/50 flex flex-col justify-between rounded-none">
                    <div>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Romantic Private Track</span>
                        <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">Bespoke Beachfront Dinner</h4>
                        <p class="text-neutral-400 text-xs leading-relaxed mb-6">Makan malam intim berdua di pesisir pasir putih di bawah kanopi lilin aromatik dengan koki privat khusus untuk Anda.</p>
                    </div>
                    <a href="#reservation-block" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Experience &rarr;</a>
                </div>
                <div class="border border-neutral-200 p-8 bg-neutral-50/50 flex flex-col justify-between rounded-none">
                    <div>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Wine & Beverage Connoisseur</span>
                        <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">Elite Vault Wine Tasting</h4>
                        <p class="text-neutral-400 text-xs leading-relaxed mb-6">Dipandu langsung oleh master sommelier berlisensi untuk mengeksplorasi koleksi wine vintage terbaik dunia di bunker bawah tanah Oasis.</p>
                    </div>
                    <a href="#reservation-block" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Experience &rarr;</a>
                </div>
                <div class="border border-neutral-200 p-8 bg-neutral-50/50 flex flex-col justify-between rounded-none">
                    <div>
                        <span class="text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-1">Weekend Exclusive Ritual</span>
                        <h4 class="text-base font-bold uppercase tracking-wider text-neutral-800 mt-4 mb-2">Grand Oceanside Sunday Brunch</h4>
                        <p class="text-neutral-400 text-xs leading-relaxed mb-6">Selebrasi kuliner akhir pekan tanpa batas menyajikan sajian kaviar premium, lobster termidor hangat, serta dessert artisan bebas pilih.</p>
                    </div>
                    <a href="#reservation-block" class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 underline hover:text-amber-700">Inquire Experience &rarr;</a>
                </div>
            </div>
        </section>

        <section id="reservation-block" class="max-w-4xl mx-auto px-6 py-20 bg-white border border-neutral-200 shadow-xl mb-24">
            <div class="text-center mb-12">
                <p class="text-xs uppercase tracking-widest font-bold text-amber-700 mb-2">Instant Booking Interface</p>
                <h2 class="text-2xl md:text-3xl font-serif text-neutral-900">Secure Your Dining Table</h2>
                <p class="text-neutral-400 text-xs mt-2">Reservations are highly recommended at least 24 hours in advance to guarantee optimized seating.</p>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Select Date</label>
                        <input type="date" required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Select Preferred Time Slot</label>
                        <select required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                            <option selected disabled>Choose time slot</option>
                            <option>12:00 PM &mdash; Lunch</option>
                            <option>01:30 PM &mdash; Lunch</option>
                            <option>06:00 PM &mdash; Dinner</option>
                            <option>07:30 PM &mdash; Dinner</option>
                            <option>09:00 PM &mdash; Night Cap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Number of Guests</label>
                        <select required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                            <option>1 Attendee</option>
                            <option selected>2 Attendees</option>
                            <option>4 Attendees</option>
                            <option>6+ Attendees (Requires Private Room)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Seating Preference Alignment</label>
                        <select class="w-full border-none border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                            <option selected>No Preference (Optimized Area)</option>
                            <option>Full Ocean Window Seating</option>
                            <option>Outdoor Open Air Deck Area</option>
                            <option>Isolated Intimate Cozy Booth</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Special Occasion / Dietary Notes</label>
                    <textarea rows="3" placeholder="E.g., Birthday Celebration, Severe Nut Allergies, High Chair Requirements..." class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 placeholder-neutral-300 bg-transparent"></textarea>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all">
                        Validate & Confirm Table Allocation
                    </button>
                </div>
            </form>
        </section>

        <section class="max-w-3xl mx-auto px-6 pb-24 text-center border-b border-neutral-200/60">
            <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-800 mb-2"><i class="fa-solid fa-circle-info"></i> House Policy Directive</h4>
            <p class="text-neutral-400 text-[11px] leading-relaxed max-w-xl mx-auto">
                **Smart Casual Dress Code**: Pakaian santai berkelas diwajibkan untuk seluruh area kuliner malam mulai pukul 18:00 WITA. Sandal jepit kamar hotel, pakaian olahraga ketat, dan baju renang terbuka dilarang masuk ke dalam ruangan makan utama demi menjaga privasi dan kenyamanan tamu lain.
            </p>
        </section>

        <section class="bg-neutral-900 text-white py-24 px-6 text-center">
            <div class="max-w-xl mx-auto">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-400 font-bold mb-3">Reserve Your Culinary Experience</p>
                <h2 class="text-3xl md:text-4xl font-light tracking-tight mb-8">Experience unforgettable dining moments crafted by our award-winning culinary team.</h2>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="#reservation-block" class="bg-amber-700 hover:bg-amber-800 text-white font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Reserve Table Instantly
                    </a>
                    <a href="mailto:concierge@oasishotel.com" class="border border-neutral-700 hover:border-neutral-500 text-neutral-300 font-bold text-[10px] uppercase tracking-widest py-4 px-8 rounded-none transition-all">
                        Contact Sommelier
                    </a>
                </div>
            </div>
        </section>

        @include('layouts.footer')

    </div>
</x-guest-layout>