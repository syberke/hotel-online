<x-guest-layout>
    <div class="min-h-screen flex bg-[#fcfcfc] text-neutral-900 font-sans antialiased">
        
        <aside class="w-64 bg-neutral-950 text-white flex flex-col justify-between min-h-screen sticky top-0 z-30 shadow-xl shrink-0">
            <div>
                <div class="p-6 border-b border-neutral-900 flex items-center gap-3">
                    <div class="w-8 h-8 text-amber-400 text-2xl flex items-center justify-center">
                        <i class="fa-solid fa-leaf"></i>
                    </div>
                    <div>
                        <span class="font-serif text-lg tracking-widest uppercase block">Oasis</span>
                        <span class="text-[9px] uppercase tracking-[0.3em] text-neutral-500 -mt-1 block">Luxury Hotel</span>
                    </div>
                </div>

                <nav class="p-4 space-y-6">
                    <div>
                        <span class="px-3 text-[10px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Menu</span>
                        <ul class="space-y-1">
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-chart-pie w-4 text-center"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-amber-400 bg-neutral-900/60 border-l-2 border-amber-500 transition-all">
                                    <i class="fa-solid fa-calendar-check w-4 text-center"></i> My Bookings
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-regular fa-user w-4 text-center"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-regular fa-credit-card w-4 text-center"></i> Payment Methods
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-award w-4 text-center"></i> Loyalty Points
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors flex justify-between">
                                    <span class="flex items-center gap-3"><i class="fa-regular fa-envelope w-4 text-center"></i> Messages</span>
                                    <span class="bg-amber-700 text-white text-[9px] px-1.5 py-0.5 font-bold">2</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div>
                        <span class="px-3 text-[10px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Explore</span>
                        <ul class="space-y-1">
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-bed w-4 text-center"></i> Rooms & Suites
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-utensils w-4 text-center"></i> Restaurant
                                </a>
                            </li>
                            <li>
                                <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-neutral-400 hover:text-white transition-colors">
                                    <i class="fa-solid fa-spa w-4 text-center"></i> Facilities
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <div class="p-4 m-4 bg-neutral-900 border border-neutral-800 relative overflow-hidden group">
                <div class="absolute inset-0 bg-cover bg-center opacity-20 scale-105 group-hover:scale-100 transition-transform duration-700" style="background-image: url('https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=500')"></div>
                <div class="relative z-10">
                    <span class="text-[8px] font-bold uppercase tracking-widest text-amber-400 block mb-1">Exclusive Member Offer</span>
                    <h5 class="text-xs font-bold text-white mb-2 leading-tight">Enjoy up to 20% discount on your next stay</h5>
                    <a href="#" class="w-full text-center block bg-white hover:bg-amber-400 hover:text-neutral-900 text-neutral-950 font-bold text-[9px] uppercase tracking-wider py-2 transition-all">Explore Offers</a>
                </div>
            </div>
        </aside>

        <main class="flex-1 flex flex-col min-w-0">
            
            <header class="h-20 bg-white border-b border-neutral-200/60 px-8 flex items-center justify-between sticky top-0 z-20">
                <div>
                    <h2 class="text-sm font-bold text-neutral-800">Welcome back, Alex <span class="ms-2 bg-amber-100 border border-amber-300 text-amber-800 text-[9px] px-2 py-0.5 font-bold tracking-widest uppercase">Gold Member</span></h2>
                    <p class="text-[11px] text-neutral-400 uppercase tracking-wider">Manage your bookings and view your stay details</p>
                </div>
                
                <div class="flex items-center gap-6">
                    <div class="bg-neutral-50 border px-4 py-1.5 flex items-center gap-3">
                        <span class="text-amber-700 text-xs"><i class="fa-solid fa-coins"></i></span>
                        <div class="text-right">
                            <span class="text-xs font-bold text-neutral-800 block leading-none">12,450</span>
                            <span class="text-[8px] text-neutral-400 uppercase tracking-widest font-bold">Reward Points</span>
                        </div>
                    </div>
                    <button class="relative text-neutral-500 hover:text-neutral-900 transition-colors">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-amber-700 rounded-full"></span>
                    </button>
                    <div class="w-9 h-9 bg-neutral-200 rounded-full overflow-hidden border border-neutral-300">
                        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop" alt="Profile" class="w-full h-full object-cover">
                    </div>
                </div>
            </header>

            <div class="p-8 max-w-7xl w-full mx-auto space-y-8 flex-1">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-3xl font-serif tracking-wide text-neutral-900">My Bookings</h1>
                        <p class="text-xs text-neutral-500">View and manage all your reservations across our sanctuaries.</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border p-5 flex flex-col justify-between group hover:border-neutral-400 transition-colors">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400">Upcoming Stays</span>
                        <div class="mt-4">
                            <span class="text-3xl font-light text-neutral-900 block leading-none">2</span>
                            <span class="text-[9px] text-neutral-500 font-medium block mt-1 uppercase">Next stay: May 24, 2026</span>
                        </div>
                    </div>
                    <div class="bg-white border p-5 flex flex-col justify-between group hover:border-neutral-400 transition-colors">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400">Completed Stays</span>
                        <div class="mt-4">
                            <span class="text-3xl font-light text-neutral-900 block leading-none">5</span>
                            <span class="text-[9px] text-neutral-500 font-medium block mt-1 uppercase">Thank you for staying!</span>
                        </div>
                    </div>
                    <div class="bg-white border p-5 flex flex-col justify-between group hover:border-neutral-400 transition-colors">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400">Cancelled Bookings</span>
                        <div class="mt-4">
                            <span class="text-3xl font-light text-neutral-900 block leading-none">1</span>
                            <span class="text-[9px] text-neutral-500 font-medium block mt-1 uppercase">This year index analytics</span>
                        </div>
                    </div>
                    <div class="bg-white border p-5 flex flex-col justify-between group hover:border-neutral-400 transition-colors">
                        <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400">Total Spending Ledger</span>
                        <div class="mt-4">
                            <span class="text-xl font-bold text-amber-900 block leading-none">Rp 18.750.000</span>
                            <span class="text-[9px] text-neutral-400 font-medium block mt-1 uppercase">Inclusive of tax fees</span>
                        </div>
                    </div>
                </div>

                <div class="border-b border-neutral-200 flex flex-wrap justify-between items-center gap-4">
                    <div class="flex gap-6 text-xs uppercase font-bold tracking-wider">
                        <button class="border-b-2 border-neutral-900 pb-3 text-neutral-900">All Bookings</button>
                        <button class="text-neutral-400 hover:text-neutral-700 pb-3 flex items-center gap-1.5">Upcoming <span class="bg-neutral-100 text-neutral-600 font-bold px-1.5 py-0.2 text-[10px]">2</span></button>
                        <button class="text-neutral-400 hover:text-neutral-700 pb-3 flex items-center gap-1.5">Completed <span class="bg-neutral-100 text-neutral-600 font-bold px-1.5 py-0.2 text-[10px]">5</span></button>
                        <button class="text-neutral-400 hover:text-neutral-700 pb-3 flex items-center gap-1.5">Cancelled <span class="bg-neutral-100 text-neutral-600 font-bold px-1.5 py-0.2 text-[10px]">1</span></button>
                    </div>
                    
                    <div class="pb-2 flex items-center gap-2">
                        <div class="relative">
                            <input type="text" placeholder="Search bookings..." class="border border-neutral-300 bg-white pl-8 pr-4 py-1.5 text-xs text-neutral-800 placeholder-neutral-400 focus:ring-0 focus:border-neutral-900 min-w-[220px]">
                            <span class="absolute left-2.5 top-2 text-xs text-neutral-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                        </div>
                        <button class="border border-neutral-300 hover:border-neutral-900 text-neutral-800 px-3 py-1.5 text-xs font-bold uppercase tracking-wider flex items-center gap-2 bg-white transition-colors">
                            <i class="fa-solid fa-sliders"></i> Filter
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    
                    <div class="bg-white border border-neutral-200 hover:border-neutral-400 transition-all grid grid-cols-1 lg:grid-cols-12 items-center group">
                        <div class="lg:col-span-3 h-40 lg:h-full overflow-hidden bg-neutral-100 relative">
                            <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=600" alt="Executive Suite" class="w-full h-full object-cover group-hover:scale-102 transition-transform duration-500">
                        </div>
                        
                        <div class="p-6 lg:col-span-9 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Booking ID: BK-240524-001</span>
                                <h4 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">Executive Suite</h4>
                                <p class="text-neutral-500 text-xs flex items-center gap-1.5"><i class="fa-solid fa-water text-amber-800"></i> Ocean Horizon View &bull; Room 1205</p>
                                <span class="text-[10px] text-neutral-400 font-medium block pt-1"><i class="fa-regular fa-user mr-1"></i> 2 Adults, 1 Room</span>
                            </div>

                            <div class="space-y-1 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Duration Tracks</span>
                                <span class="text-xs font-bold text-neutral-800 block">May 24 &rarr; May 26, 2026</span>
                                <span class="text-[10px] text-amber-800 font-bold uppercase tracking-widest block">2 Nights Absolute Stay</span>
                            </div>

                            <div class="space-y-1.5 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Status Valuations</span>
                                <div class="flex md:justify-center gap-1.5">
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-emerald-50 border border-emerald-200 text-emerald-800">Confirmed</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-neutral-900 text-white">Paid</span>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end gap-2">
                                <span class="text-right block">
                                    <span class="text-xs text-neutral-400 block font-normal leading-none mb-0.5">Total Price</span>
                                    <span class="text-sm font-bold text-neutral-900 block leading-none">Rp 3.300.000</span>
                                    <span class="text-[9px] text-neutral-400 block mt-0.5">Rp 1.650.000 / night</span>
                                </span>
                                <div class="flex gap-2 w-full md:w-auto pt-1">
                                    <button class="flex-1 md:flex-none border border-neutral-300 hover:border-neutral-900 px-3 py-1.5 text-[10px] font-bold uppercase tracking-wider text-neutral-800 transition-colors bg-white">Modify</button>
                                    <button class="flex-1 md:flex-none bg-neutral-900 hover:bg-neutral-800 text-white px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider transition-all">View Details</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 hover:border-neutral-400 transition-all grid grid-cols-1 lg:grid-cols-12 items-center group">
                        <div class="lg:col-span-3 h-40 lg:h-full overflow-hidden bg-neutral-100">
                            <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=600" alt="Deluxe Room" class="w-full h-full object-cover opacity-80 group-hover:scale-102 transition-transform duration-500">
                        </div>
                        
                        <div class="p-6 lg:col-span-9 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Booking ID: BK-240418-002</span>
                                <h4 class="text-sm font-bold text-neutral-900 uppercase tracking-wide">Deluxe Room</h4>
                                <p class="text-neutral-500 text-xs flex items-center gap-1.5"><i class="fa-solid fa-mountain-sun text-neutral-400"></i> Partial Sea View &bull; Room 0803</p>
                                <span class="text-[10px] text-neutral-400 font-medium block pt-1"><i class="fa-regular fa-user mr-1"></i> 2 Adults, 1 Room</span>
                            </div>

                            <div class="space-y-1 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Duration Tracks</span>
                                <span class="text-xs font-bold text-neutral-800 block">Apr 18 &rarr; Apr 20, 2026</span>
                                <span class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest block">2 Nights Archive</span>
                            </div>

                            <div class="space-y-1.5 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Status Valuations</span>
                                <div class="flex md:justify-center gap-1.5">
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-neutral-100 border text-neutral-500">Completed</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-neutral-100 text-neutral-400 border-none">Paid</span>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end gap-2">
                                <span class="text-right block">
                                    <span class="text-xs text-neutral-400 block font-normal leading-none mb-0.5">Total Price</span>
                                    <span class="text-sm font-bold text-neutral-700 block leading-none">Rp 2.100.000</span>
                                    <span class="text-[9px] text-neutral-400 block mt-0.5">Rp 1.050.000 / night</span>
                                </span>
                                <div class="flex gap-2 w-full md:w-auto pt-1">
                                    <button class="w-full md:w-auto border border-neutral-300 hover:border-neutral-900 px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-neutral-800 transition-colors bg-white">Book Again</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 hover:border-neutral-400 transition-all grid grid-cols-1 lg:grid-cols-12 items-center group">
                        <div class="lg:col-span-3 h-40 lg:h-full overflow-hidden bg-neutral-100 grayscale opacity-60">
                            <img src="https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=600" alt="Family Room" class="w-full h-full object-cover">
                        </div>
                        
                        <div class="p-6 lg:col-span-9 grid grid-cols-1 md:grid-cols-4 gap-6 items-center">
                            <div class="space-y-1">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Booking ID: BK-240304-003</span>
                                <h4 class="text-sm font-bold text-neutral-400 line-through uppercase tracking-wide">Family Room</h4>
                                <p class="text-neutral-400 text-xs flex items-center gap-1.5"><i class="fa-solid fa-tree"></i> Garden View &bull; Room 0501</p>
                                <span class="text-[10px] text-neutral-400 font-medium block pt-1"><i class="fa-regular fa-user mr-1"></i> 3 Adults, 1 Room</span>
                            </div>

                            <div class="space-y-1 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Duration Tracks</span>
                                <span class="text-xs font-bold text-neutral-400 block">Mar 04 &rarr; Mar 06, 2026</span>
                            </div>

                            <div class="space-y-1.5 md:text-center">
                                <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 block">Status Valuations</span>
                                <div class="flex md:justify-center gap-1.5">
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-red-50 border border-red-200 text-red-700">Cancelled</span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 bg-neutral-100 text-neutral-500">Refunded</span>
                                </div>
                            </div>

                            <div class="flex flex-col md:items-end gap-2">
                                <span class="text-right block">
                                    <span class="text-xs text-neutral-400 block font-normal leading-none mb-0.5">Total Amount</span>
                                    <span class="text-sm font-bold text-neutral-400 block leading-none">Rp 2.400.000</span>
                                </span>
                                <div class="flex gap-2 w-full md:w-auto pt-1">
                                    <button class="w-full md:w-auto border border-neutral-300 hover:bg-neutral-900 hover:text-white px-4 py-1.5 text-[10px] font-bold uppercase tracking-wider text-neutral-800 transition-colors bg-white">Rebook</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="bg-neutral-100 border border-neutral-200 p-6 rounded-none flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-4 text-center md:text-left flex-col md:flex-row">
                        <div class="w-10 h-10 bg-neutral-900 text-white rounded-full flex items-center justify-center text-sm shadow-md">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-neutral-900">Need help with your booking?</h4>
                            <p class="text-xs text-neutral-500">Our elite concierge team is available 24/7 to assist with bespoke requests.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 w-full md:w-auto justify-center">
                        <a href="mailto:concierge@oasis.com" class="border border-neutral-300 bg-white hover:border-neutral-900 text-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider text-center transition-colors">Email Support</a>
                        <a href="tel:+623611234567" class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-2 text-xs font-bold uppercase tracking-wider text-center transition-all shadow-sm">Contact Concierge</a>
                    </div>
                </div>

            </div>
        </main>
    </div>
</x-guest-layout>