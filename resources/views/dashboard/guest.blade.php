<x-guest-layout>

    <div class="min-h-screen bg-[#faf9f5] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900">

        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 relative shadow-2xl">
            <div>
                <div class="p-8 border-b border-neutral-900 text-center relative group">
                    <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                    <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">Guest Portal</p>
                    <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
                </div>

                <nav class="p-4 pt-6 space-y-1">
                    <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                    
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-bold uppercase tracking-widest bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500 transition-all">
                        <i class="fa-solid fa-square-poll-horizontal text-sm"></i> Dashboard
                    </a>
                    
                    <a href="{{ route('bookings.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all">
                        <i class="fa-solid fa-calendar-days text-sm"></i> My Bookings
                    </a>
                    
                    <a href="{{ route('stay.my') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all">
                        <i class="fa-solid fa-key text-sm"></i> My Stay
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                    
                    <a href="{{ route('room.service') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all">
                        <i class="fa-solid fa-bell-concierge text-sm"></i> Room Service
                    </a>
                    
                    <a href="{{ route('restaurant.orders') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all flex justify-between">
                        <span class="flex items-center gap-3.5"><i class="fa-solid fa-utensils text-sm"></i> Restaurant</span>
                        @if($activeOrders->isNotEmpty())
                            <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                        @endif
                    </a>
                    
                    <a href="{{ route('facilities.booking') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all">
                        <i class="fa-solid fa-spa text-sm"></i> Facilities
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Account Control</span>
                    
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs font-medium uppercase tracking-widest hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700 transition-all">
                        <i class="fa-solid fa-sliders text-sm"></i> Profile Settings
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4">
                <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 rounded-none flex items-center justify-between">
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Nusa Dua, Bali</span>
                        <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Tropical Clearness</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-light font-serif text-white tracking-wide">{{ $temperature }}</div>
                        <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-sun mr-1"></i> Live</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i> Logout Portal
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Welcome Back,</span>
                    <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">{{ auth()->user()->name }}</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                        <i class="fa-solid fa-crown text-amber-700 text-xs"></i> Oasis Patron Member
                    </span>
                    <div class="h-6 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                    </a>
                </div>
            </header>

            <div class="p-10 space-y-8 flex-1">
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-stretch">
                    <div class="lg:col-span-2 relative bg-neutral-950 overflow-hidden border border-neutral-200/40 flex flex-col justify-end p-8 min-h-[260px] shadow-sm group">
                        <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=1200&auto=format&fit=crop" alt="Resort Atmosphere" class="absolute inset-0 w-full h-full object-cover opacity-45 scale-102 group-hover:scale-100 transition-transform duration-[4000ms]">
                        <div class="absolute inset-0 bg-gradient-to-t from-neutral-950/80 via-transparent to-transparent"></div>
                        <div class="relative z-10 text-white space-y-2">
                            <span class="text-[9px] font-bold uppercase tracking-[0.3em] text-amber-400 block">Sanctuary Experience</span>
                            <h2 class="text-3xl font-serif tracking-wide font-light">Where Luxury Meets Serenity</h2>
                            <p class="text-neutral-300 text-xs font-normal max-w-md leading-relaxed">We hope you have a memorable stay. Contact our internal concierge desk anytime for bespoke activity coordinates, transport arrangements, or technical support.</p>
                        </div>
                    </div>

                    <div class="bg-neutral-900 text-white p-6 border border-neutral-800 rounded-none flex flex-col justify-between shadow-md relative">
                        @if($bookings->isNotEmpty())
                            @php $latest = $bookings->first(); @endphp
                            <div>
                                <div class="flex justify-between items-center border-b border-neutral-800 pb-3.5 mb-4">
                                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Current Reservation</h3>
                                    <span class="bg-amber-950/60 text-amber-400 border border-amber-900/60 text-[8px] font-bold uppercase tracking-[0.15em] px-2.5 py-0.5">{{ $latest->status }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-xs font-medium text-neutral-400">
                                    <div>
                                        <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5">Check-In Date</span>
                                        <span class="text-neutral-100 font-bold tracking-wide">{{ date('d M Y', strtotime($latest->check_in_date)) }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5">Check-Out Date</span>
                                        <span class="text-neutral-100 font-bold tracking-wide">{{ date('d M Y', strtotime($latest->check_out_date)) }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5">Suite Designation</span>
                                        <span class="text-neutral-100 font-bold line-clamp-1 uppercase tracking-wide">{{ $latest->type_name ?? 'Premium Enclave' }}</span>
                                    </div>
                                    <div>
                                        <span class="block text-[8px] font-bold uppercase tracking-widest text-neutral-500 mb-0.5">Room Inventory</span>
                                        <span class="text-amber-400 font-bold tracking-wide">Suite {{ $latest->room_number ?? 'Assigning...' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-neutral-800 mt-4 flex justify-between items-center">
                                <span class="text-[10px] text-neutral-500 uppercase tracking-wider">ID: #OA-{{ $latest->id }}</span>
                                <a href="{{ route('bookings.my') }}" class="text-[10px] font-bold uppercase tracking-widest text-white underline hover:text-amber-400 transition-colors">Manage Stay &rarr;</a>
                            </div>
                        @else
                            <div class="text-center py-8 my-auto space-y-4">
                                <div class="w-10 h-10 border border-neutral-800 rounded-full flex items-center justify-center mx-auto text-neutral-500">
                                    <i class="fa-solid fa-bed text-sm"></i>
                                </div>
                                <p class="text-xs text-neutral-400 max-w-[200px] mx-auto leading-relaxed">No active luxury room reservation found for your account.</p>
                                <a href="{{ route('home') }}#booking-bar" class="inline-block text-[9px] font-bold uppercase tracking-widest bg-white hover:bg-amber-400 text-neutral-950 py-2.5 px-5 transition-colors">Book A Suite Now</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
                    <a href="{{ route('room.service') }}" class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-bell-concierge"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Room Service</h4>
                    </a>
                    <a href="{{ route('restaurant.orders') }}" class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-utensils"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Restaurant</h4>
                    </a>
                    <a href="{{ route('facilities.booking') }}" class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-spa"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Facilities</h4>
                    </a>
                    <div class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm cursor-pointer">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-feather-pointed"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Special Requests</h4>
                    </div>
                    <div class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm cursor-pointer">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-message"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Messages Center</h4>
                    </div>
                    <a href="{{ route('billing.matrix') }}" class="bg-white border border-neutral-200 p-5 text-center hover:border-neutral-900 transition-all duration-300 group flex flex-col justify-center items-center shadow-sm">
                        <div class="text-neutral-800 text-lg group-hover:text-amber-700 transition-colors"><i class="fa-solid fa-receipt"></i></div>
                        <h4 class="text-[9px] font-bold uppercase tracking-[0.15em] text-neutral-800 mt-2.5">Billing Matrix</h4>
                    </a>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                    
                    <div class="bg-white border border-neutral-200/80 p-6 shadow-sm rounded-none space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3.5">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-amber-700 rounded-full"></span> Your Active Manifest Stays
                            </h3>
                            <span class="text-[10px] bg-neutral-50 border px-2.5 py-0.5 text-neutral-500 font-mono font-bold">{{ $bookings->count() }} Total Tracked</span>
                        </div>
                        <div class="divide-y divide-neutral-100">
                            @forelse($bookings as $b)
                                <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group/row">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900 transition-colors group-hover/row:text-amber-800">#OA-{{ $b->id }} &bull; {{ $b->type_name ?? 'Deluxe Room' }}</h4>
                                        <p class="text-[10px] text-neutral-400 font-medium mt-1">Schedule Matrix: <span class="text-neutral-600 font-bold">{{ date('d M Y', strtotime($b->check_in_date)) }}</span> &rarr; <span class="text-neutral-600 font-bold">{{ date('d M Y', strtotime($b->check_out_date)) }}</span></p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-mono font-bold text-neutral-900 block">Rp {{ number_format($b->total_price, 0, ',', '.') }}</span>
                                        <span class="text-[8px] uppercase font-bold tracking-widest inline-block px-1.5 py-0.2 mt-1 {{ $b->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-100' }}">{{ $b->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center space-y-2">
                                    <p class="text-neutral-400 text-xs italic">Belum ada rincian manifes reservasi menginap di data server.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200/80 p-6 shadow-sm rounded-none space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3.5">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 flex items-center gap-2">
                                <span class="w-1.5 h-1.5 bg-neutral-900 rounded-full"></span> Recent Culinary Gastronomy Orders
                            </h3>
                            <span class="text-[10px] bg-neutral-50 border px-2.5 py-0.5 text-neutral-500 font-mono font-bold">{{ $activeOrders->count() }} Submissions</span>
                        </div>
                        <div class="divide-y divide-neutral-100">
                            @forelse($activeOrders as $order)
                                <div class="flex items-center justify-between py-4 first:pt-0 last:pb-0 group/row">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wide text-neutral-900 transition-colors group-hover/row:text-amber-800">Order #F&B-{{ $order->id }}</h4>
                                        <p class="text-[10px] text-neutral-400 font-medium mt-1">Placed Log: {{ date('d M Y H:i', strtotime($order->created_at)) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-mono font-bold text-neutral-900 block">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                        <span class="bg-neutral-950 text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 inline-block mt-1">{{ $order->status }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center space-y-2">
                                    <p class="text-neutral-400 text-xs italic">Tidak ada antrean pesanan makanan yang sedang diproses.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>

            </div>

            @include('layouts.footer')
        </main>

    </div>
</x-guest-layout>