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
    [x-cloak] { display: none !important; }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased"
         x-data="{
            cart: JSON.parse(localStorage.getItem('oasis_restaurant_cart') || '[]'),
            showToast: false,
            toastMessage: '',

            init() {
                this.$watch('cart', value => {
                    localStorage.setItem('oasis_restaurant_cart', JSON.stringify(value));
                });
            },

            addItemToGlobalCart(id, name, price, imageUrl) {
                let found = this.cart.find(i => i.id === id);
                if (found) {
                    found.quantity++;
                } else {
                    this.cart.push({
                        id: id,
                        title: name,
                        price: price,
                        image_url: imageUrl,
                        quantity: 1,
                        venue: 'Oasis Fine Dining'
                    });
                }
                this.toastMessage = 'Added ' + name + ' to your dashboard cart!';
                this.showToast = true;
                setTimeout(() => this.showToast = false, 2500);
            }
         }">
        
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
                <a href="{{ route('restaurant', ['category' => 'All Menu', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category', 'All Menu') == 'All Menu' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} rounded-none">All Menu</a>
                <a href="{{ route('restaurant', ['category' => 'Appetizers', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category') == 'Appetizers' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} transition-colors">Appetizers</a>
                <a href="{{ route('restaurant', ['category' => 'Main Courses', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category') == 'Main Courses' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} transition-colors">Main Courses</a>
                <a href="{{ route('restaurant', ['category' => 'Seafood', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category') == 'Seafood' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} transition-colors">Seafood</a>
                <a href="{{ route('restaurant', ['category' => 'Steak Selection', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category') == 'Steak Selection' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} transition-colors">Steak Selection</a>
                <a href="{{ route('restaurant', ['category' => 'Desserts', 'search' => request('search'), 'min_price' => request('min_price'), 'max_price' => request('max_price')]) }}" class="px-5 py-2 {{ request('category') == 'Desserts' ? 'bg-neutral-900 text-white' : 'text-neutral-500 hover:text-neutral-900' }} transition-colors">Desserts</a>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start" id="menu-browsing-anchor">
                
                <form action="{{ route('restaurant') }}" method="GET" class="w-full lg:w-1/4">
                    <input type="hidden" name="category" value="{{ request('category', 'All Menu') }}">
                    <aside class="w-full bg-white border border-neutral-200 p-6 rounded-none space-y-6 sticky top-28">
                        <div class="border-b border-neutral-100 pb-3 flex justify-between items-center">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Search & Options</h3>
                            <a href="{{ route('restaurant') }}" class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 hover:text-neutral-900 underline">Reset</a>
                        </div>
                        
                        <div>
                            <label class="block text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-2">Search Dishes</label>
                            <input type="text" name="search" value="{{ request('search') }}" onchange="this.form.submit()" placeholder="Type dish name & press Enter..." class="w-full border border-neutral-300 px-3 py-2 text-xs rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                        </div>

                        <div class="border-t border-neutral-100 pt-4 space-y-3">
                            <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-700">Price Range (IDR)</h4>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="text-[8px] font-bold uppercase text-neutral-400 block mb-1">Min Price</label>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}" onchange="this.form.submit()" placeholder="Ex: 50000" class="w-full border border-neutral-300 px-2 py-1 text-xs rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                                </div>
                                <div>
                                    <label class="text-[8px] font-bold uppercase text-neutral-400 block mb-1">Max Price</label>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}" onchange="this.form.submit()" placeholder="Ex: 400000" class="w-full border border-neutral-300 px-2 py-1 text-xs rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                                </div>
                            </div>
                        </div>

                        <div class="border border-amber-800/20 bg-amber-50/40 p-4 rounded-none space-y-2 text-xs">
                            <h5 class="font-bold uppercase tracking-wider text-amber-900"><i class="fa-solid fa-bell-concierge"></i> 24/7 In-Room Delivery</h5>
                            <p class="text-neutral-600 text-[11px] leading-relaxed">All featured dishes can be directly ordered to your personal suite enclosure. Est transit time: 20-30 mins. Standard 10% service premium applies.</p>
                        </div>
                    </aside>
                </form>

                <section class="w-full lg:w-3/4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[960px] overflow-y-auto pr-2 custom-scrollbar" id="menu-cards-scrollbox">
                        
                        @forelse($culinaryMenus as $menu)
                        <div class="bg-white border border-neutral-200 rounded-none overflow-hidden group flex flex-col justify-between hover:border-neutral-400 transition-all duration-300">
                            <div>
                                <div class="h-48 overflow-hidden relative bg-neutral-100">
                                    <a href="{{ route('restaurant.detail', $menu->id) }}" class="block w-full h-full">
                                        <img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-700">
                                    </a>
                                    <span class="absolute bottom-4 left-4 bg-amber-800 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-none">
                                        <i class="fa-solid fa-star text-amber-400 mr-0.5"></i> Chef Recommendation
                                    </span>
                                </div>
                                
                                <div class="p-5">
                                    <div class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Premium Gastronomy</div>
                                    <a href="{{ route('restaurant.detail', $menu->id) }}" class="block group-hover:text-amber-800 transition-colors">
                                        <h4 class="text-sm font-bold uppercase tracking-wide text-neutral-900 font-sans mb-1.5">{{ $menu->name }}</h4>
                                    </a>
                                    <p class="text-neutral-400 text-[11px] leading-relaxed mb-3 line-clamp-3">{{ $menu->description }}</p>
                                    <div class="text-[9px] font-bold text-neutral-400 uppercase tracking-widest"><i class="fa-solid fa-fire-flame-curved text-neutral-400 mr-1"></i> Energy Value: Verified Organic</div>
                                </div>
                            </div>
                            
                            <div class="p-5 pt-0">
                                <div class="flex flex-col gap-3 border-t border-neutral-100 pt-3">
                                    <div class="text-base font-bold text-amber-900">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('restaurant.detail', $menu->id) }}" class="w-full text-center border border-neutral-300 hover:border-neutral-900 text-neutral-800 text-[9px] font-bold uppercase tracking-widest py-2 rounded-none transition-colors flex items-center justify-center">
                                            View Detail
                                        </a>
                                        <button type="button" @click="addItemToGlobalCart({{ $menu->id }}, '{{ addslashes($menu->name) }}', {{ $menu->price }}, '{{ $menu->foto_url }}')" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white text-[9px] font-bold uppercase tracking-widest py-2 rounded-none transition-colors cursor-pointer text-center">
                                            Add To Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 p-12 text-center bg-white border border-neutral-200">
                            <p class="text-xs text-neutral-400 italic font-medium">No gastronomy masterpiece matches your chosen filters.</p>
                        </div>
                        @endforelse

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

            <form action="{{ route('facilities.book') }}" method="POST" class="space-y-6" id="restaurant-table-form">
                @csrf
                <input type="hidden" name="facility_name" value="Restaurant Table">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Select Date</label>
                        <input type="date" name="booking_date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Select Preferred Time Slot</label>
                        <select name="booking_time" required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                            <option selected disabled>Choose time slot</option>
                            <option value="12:00:00">12:00 PM &mdash; Lunch</option>
                            <option value="13:30:00">01:30 PM &mdash; Lunch</option>
                            <option value="18:00:00">06:00 PM &mdash; Dinner</option>
                            <option value="19:30:00">07:30 PM &mdash; Dinner</option>
                            <option value="21:00:00">09:00 PM &mdash; Night Cap</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Number of Guests</label>
                        <input type="number" name="guests_count" min="1" max="20" value="2" required class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Seating Preference Alignment</label>
                        <select name="seating_preference" class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 cursor-pointer bg-transparent">
                            <option selected value="No Preference">No Preference (Optimized Area)</option>
                            <option value="Full Ocean Window">Full Ocean Window Seating</option>
                            <option value="Outdoor Open Air">Outdoor Open Air Deck Area</option>
                            <option value="Isolated Intimate">Isolated Intimate Cozy Booth</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-neutral-700 mb-2">Special Occasion / Dietary Notes</label>
                    <textarea name="notes" rows="3" placeholder="E.g., Birthday Celebration, Severe Nut Allergies, High Chair Requirements..." class="w-full border border-neutral-300 text-xs px-4 py-3 rounded-none focus:ring-1 focus:ring-neutral-900 focus:border-neutral-900 placeholder-neutral-300 bg-transparent"></textarea>
                </div>

                <div id="table-alert-box" class="hidden p-3 text-[11px] font-bold uppercase tracking-wider mb-4 rounded-none"></div>

                <div class="pt-2">
                    <button type="submit" id="table-submit-btn" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-4 rounded-none transition-all cursor-pointer">
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

        <div x-show="showToast" x-cloak 
             class="fixed bottom-5 right-5 z-50 bg-neutral-900 text-white text-xs uppercase tracking-wider font-bold py-3.5 px-6 shadow-2xl border border-neutral-800 flex items-center gap-2.5"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:leave="transition ease-in duration-200">
            <i class="fa-solid fa-circle-check text-emerald-400"></i>
            <span x-text="toastMessage"></span>
        </div>

        @include('layouts.footer')
    </div>
</x-guest-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (localStorage.getItem('restaurant_scroll_y')) {
            window.scrollTo(0, parseInt(localStorage.getItem('restaurant_scroll_y')));
            localStorage.removeItem('restaurant_scroll_y');
        }
        
        const scrollBox = document.getElementById('menu-cards-scrollbox');
        if (scrollBox && localStorage.getItem('menu_box_scroll_top')) {
            scrollBox.scrollTop = parseInt(localStorage.getItem('menu_box_scroll_top'));
            localStorage.removeItem('menu_box_scroll_top');
        }
    });

    window.addEventListener("beforeunload", function() {
        localStorage.setItem('restaurant_scroll_y', window.scrollY);
        const scrollBox = document.getElementById('menu-cards-scrollbox');
        if (scrollBox) {
            localStorage.setItem('menu_box_scroll_top', scrollBox.scrollTop);
        }
    });

    // RESERVASI MEJA RESTORAN AJAX HANDLING
    document.addEventListener('DOMContentLoaded', function () {
        const tableForm = document.getElementById('restaurant-table-form');
        const tableBtn = document.getElementById('table-submit-btn');
        const tableAlert = document.getElementById('table-alert-box');

        if (tableForm) {
            tableForm.addEventListener('submit', function (e) {
                e.preventDefault();
                tableBtn.disabled = true;
                tableBtn.innerText = "Processing Allocation...";

                fetch(tableForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(tableForm)
                })
                .then(async response => {
                    const data = await response.json();
                    tableBtn.disabled = false;
                    tableBtn.innerText = "Validate & Confirm Table Allocation";
                    tableAlert.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'border');

                    if (response.ok && data.success) {
                        tableAlert.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                        tableAlert.innerText = data.message;
                        tableForm.reset(); 
                    } else {
                        tableAlert.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                        tableAlert.innerText = data.message || "Gagal mengalokasikan meja.";
                    }
                })
                .catch(() => {
                    tableBtn.disabled = false;
                    tableBtn.innerText = "Validate & Confirm Table Allocation";
                    tableAlert.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                    tableAlert.innerText = "Terjadi gangguan transmisi sinyal menuju server lokal.";
                });
            });
        }
    });
</script>