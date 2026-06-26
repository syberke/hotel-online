<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Arrivals</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $arrivalsCount }}</span>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'arrivals']) }}" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View List &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Departures</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $departuresCount }}</span>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'departures']) }}" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View List &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Currently Checked In</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $checkedInCount }}</span>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'in_house']) }}" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View Guests &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Available Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $availableRooms }}</span>
                        <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">Room Status &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $occupancyRate }}%</span>
                        <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View Report &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-6">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                    <input type="hidden" name="tab" value="{{ $currentTab }}">
                    <div>
                        <h2 class="font-serif text-base text-neutral-900 tracking-wide font-medium">Today's Front Desk Monitor</h2>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[240px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reservation, guest name, ID..." class="pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50 w-full">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-1.5 text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">Search</button>
                    </div>
                </form>

                <div class="flex border-b border-neutral-200 text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6">
                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="pb-3 px-1 flex items-center gap-1.5 {{ $currentTab == 'all' ? 'border-b-2 border-neutral-900 text-neutral-900' : 'border-b-2 border-transparent hover:text-neutral-900' }}">
                        All Operational Movement
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'arrivals']) }}" class="pb-3 px-1 flex items-center gap-1.5 {{ $currentTab == 'arrivals' ? 'border-b-2 border-neutral-900 text-neutral-900' : 'border-b-2 border-transparent hover:text-neutral-900' }}">
                        Expected Arrivals <span class="bg-neutral-100 font-mono text-[10px] px-2 py-0.5 rounded">{{ $arrivalsCount }}</span>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'in_house']) }}" class="pb-3 px-1 flex items-center gap-1.5 {{ $currentTab == 'in_house' ? 'border-b-2 border-neutral-900 text-neutral-900' : 'border-b-2 border-transparent hover:text-neutral-900' }}">
                        Current In House <span class="bg-neutral-100 font-mono text-[10px] px-2 py-0.5 rounded">{{ $checkedInCount }}</span>
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['tab' => 'departures']) }}" class="pb-3 px-1 flex items-center gap-1.5 {{ $currentTab == 'departures' ? 'border-b-2 border-neutral-900 text-neutral-900' : 'border-b-2 border-transparent hover:text-neutral-900' }}">
                        Expected Departures <span class="bg-neutral-100 font-mono text-[10px] px-2 py-0.5 rounded">{{ $departuresCount }}</span>
                    </a>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                                <th class="py-3 px-4 font-semibold">Booking ID</th>
                                <th class="py-3 px-4 font-semibold">Guest Passport / Info</th>
                                <th class="py-3 px-4 font-semibold">Allocated Room</th>
                                <th class="py-3 px-4 font-semibold">Check-In</th>
                                <th class="py-3 px-4 font-semibold">Check-Out</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($todayReservations as $resv)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-neutral-900 block">#OA-{{ $resv->id }}</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">System Ledger</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-neutral-900 block">{{ $resv->guest_name }}</span>
                                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">{{ $resv->guest_email }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="text-neutral-800 block">{{ $resv->room_type }}</span>
                                        <span class="text-[10px] text-neutral-500 block font-mono font-bold mt-0.5">Room {{ $resv->room_number }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($resv->check_in)->format('d M Y') }}</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">From 2:00 PM</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($resv->check_out)->format('d M Y') }}</span>
                                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">
                                            {{ \Carbon\Carbon::parse($resv->check_in)->diffInDays(\Carbon\Carbon::parse($resv->check_out)) }} Nights
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($resv->status == 'checked_in')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">In House</span>
                                        @elseif($resv->status == 'confirmed')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Confirmed Arrival</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">{{ str_replace('_', ' ', $resv->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        @if(auth()->user()->role !== 'manager')
                                            <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                        @else
                                            <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 cursor-pointer" title="Audit Log View"><i class="fa-solid fa-eye text-xs"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 text-center text-neutral-400">No active front-desk ledger records found for today.</td>
                                endtr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pt-2 border-t border-neutral-100 flex items-center justify-between">
                    <span class="text-[11px] text-neutral-400 font-medium">Showing entries {{ $todayReservations->firstItem() ?? 0 }} to {{ $todayReservations->lastItem() ?? 0 }} of {{ $todayReservations->total() }}</span>
                    <div class="font-mono text-neutral-800 text-[11px]">
                        {{ $todayReservations->links() }}
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">In House Guests Manifest</h3>
                    <span class="text-[10px] font-mono text-neutral-400">Real-Time Registration Stream</span>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] pb-2">
                                <th class="pb-3 px-2 font-semibold">Guest Profile</th>
                                <th class="pb-3 px-2 font-semibold">Room Mapping</th>
                                <th class="pb-3 px-2 font-semibold">Check-In Date</th>
                                <th class="pb-3 px-2 font-semibold">Expected Check-Out</th>
                                <th class="pb-3 px-2 font-semibold">Duration</th>
                                <th class="pb-3 px-2 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50/60 font-medium text-neutral-600">
                            @forelse($inHouseGuests as $guest)
                                <tr class="hover:bg-neutral-50/20 transition-colors">
                                    <td class="py-3 px-2 flex items-center gap-3">
                                        <img src="{{ $guest->guest_avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100' }}" class="w-7 h-7 object-cover border rounded-sm">
                                        <div>
                                            <span class="font-bold text-neutral-900 block">{{ $guest->guest_name }}</span>
                                            <span class="text-[9px] text-neutral-400 block font-normal">{{ $guest->guest_phone ?? 'No phone logged' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="text-neutral-900 font-bold block">{{ $guest->room_type }}</span>
                                        <span class="text-[9px] text-neutral-500 font-mono font-bold">No. {{ $guest->room_number }}</span>
                                    </td>
                                    <td class="py-3 px-2 text-neutral-500">
                                        {{ \Carbon\Carbon::parse($guest->check_in)->format('d M Y') }}
                                        <span class="block text-[9px] text-neutral-400 font-normal">Registered Checked In</span>
                                    </td>
                                    <td class="py-3 px-2 text-neutral-500">
                                        {{ \Carbon\Carbon::parse($guest->check_out)->format('d M Y') }}
                                        <span class="block text-[9px] text-neutral-400 font-normal">12:00 PM Standard</span>
                                    </td>
                                    <td class="py-3 px-2 font-mono text-neutral-900">
                                        {{ \Carbon\Carbon::parse($guest->check_in)->diffInDays(\Carbon\Carbon::parse($guest->check_out)) }} Nts
                                    </td>
                                    <td class="py-3 px-2 text-center text-neutral-500">
                                        <button class="border border-neutral-200 hover:bg-neutral-900 hover:text-white font-bold text-[9px] uppercase tracking-wider px-3 py-1 text-neutral-700 bg-white mr-2 transition-all cursor-pointer">View Folio</button>
                                        @if(auth()->user()->role !== 'manager')
                                            <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                        @else
                                            <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 cursor-pointer"><i class="fa-solid fa-eye text-xs"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-neutral-400">There are currently no checked-in guests inside the hotel enclave.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="pt-2 border-t border-neutral-100 flex items-center justify-between">
                    <span class="text-[11px] text-neutral-400 font-medium">Showing entries {{ $inHouseGuests->firstItem() ?? 0 }} to {{ $inHouseGuests->lastItem() ?? 0 }} of {{ $inHouseGuests->total() }}</span>
                    <div class="font-mono text-neutral-800 text-[11px]">
                        {{ $inHouseGuests->links() }}
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Today's Arrival Stream</h3>
                    <span class="text-[9px] bg-neutral-100 text-neutral-800 font-bold px-1.5 py-0.5 uppercase tracking-tight">Live</span>
                </div>
                
                <div class="space-y-3.5">
                    @forelse($asideArrivals as $arr)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <img src="{{ $arr->guest_avatar ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100' }}" class="w-8 h-8 object-cover border rounded-sm">
                                <div>
                                    <span class="text-xs font-bold text-neutral-900 block leading-tight">{{ $arr->guest_name }}</span>
                                    <span class="text-[9px] font-medium text-neutral-400 block mt-0.5">{{ $arr->room_type }} &bull; Max {{ $arr->guests_count ?? 2 }}A</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-mono font-bold text-neutral-500 block">ETA 2 PM</span>
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[7px] font-bold uppercase px-1.5 py-0.2 tracking-wide block mt-1">Pending</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-neutral-400 text-[11px]">No expected arrivals left for today.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Physical Room Allocations</h3>
                </div>
                
                @php
                    // Hitung koordinat SVG dasharray dinamis berdasarkan rasio ketersediaan fisik nyata
                    $occupiedPercent = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;
                    $availablePercent = $totalRooms > 0 ? ($availableRooms / $totalRooms) * 100 : 0;
                    $maintenancePercent = $totalRooms > 0 ? ($outOfOrderRooms / $totalRooms) * 100 : 0;
                @endphp
                <div class="flex items-center gap-4 my-2">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="{{ $maintenancePercent }} {{ 100 - $maintenancePercent }}" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#3b82f6" stroke-width="4.5" stroke-dasharray="{{ $availablePercent }} {{ 100 - $availablePercent }}" stroke-dashoffset="-{{ $maintenancePercent }}"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="{{ $occupiedPercent }} {{ 100 - $occupiedPercent }}" stroke-dashoffset="-{{ $maintenancePercent + $availablePercent }}"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-lg font-light font-serif text-neutral-900 block leading-none">{{ $totalRooms }}</span>
                            <span class="text-[8px] text-neutral-400 uppercase tracking-wider font-bold mt-0.5 block">Kamar</span>
                        </div>
                    </div>
                    <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-2 h-2 bg-emerald-500 inline-block mr-1.5"></span>Occupied</span><span class="text-neutral-800 font-mono">{{ $occupiedRooms }} ({{ round($occupiedPercent,1) }}%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-2 h-2 bg-blue-500 inline-block mr-1.5"></span>Available</span><span class="text-neutral-800 font-mono">{{ $availableRooms }} ({{ round($availablePercent,1) }}%)</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-2 h-2 bg-red-500 inline-block mr-1.5"></span>Maintenance</span><span class="text-neutral-800 font-mono">{{ $outOfOrderRooms }} ({{ round($maintenancePercent,1) }}%)</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                @if(auth()->user()->role !== 'manager')
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2">Quick Front Actions</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <a href="{{ route('receptionist.walkin') }}" class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 transition-all flex flex-col justify-center items-center gap-2 group cursor-pointer">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-plus"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Walk-In Reg</span>
                        </a>
                        <a href="{{ route('receptionist.checkin') }}" class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 transition-all flex flex-col justify-center items-center gap-2 group cursor-pointer">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-door-open"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Check-In</span>
                        </a>
                        <a href="{{ route('receptionist.checkout') }}" class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 transition-all flex flex-col justify-center items-center gap-2 group cursor-pointer">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-receipt"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Check-Out</span>
                        </a>
                        <a href="{{ route('receptionist.reservations') }}" class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 transition-all flex flex-col justify-center items-center gap-2 group cursor-pointer">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-magnifying-glass"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Search Resv</span>
                        </a>
                    </div>
                @else
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2"><i class="fa-solid fa-folder-open text-amber-700 mr-1"></i> Management Audits</h3>
                    <div class="grid grid-cols-1 gap-2 text-[10px] font-bold uppercase tracking-wider">
                        <button class="w-full bg-neutral-950 hover:bg-neutral-900 text-white p-3 flex items-center justify-center gap-2 cursor-pointer transition-colors"><i class="fa-solid fa-file-invoice"></i> Download Occupancy Audit</button>
                        <button class="w-full bg-white border border-neutral-200 hover:border-neutral-900 text-neutral-800 p-3 flex items-center justify-center gap-2 cursor-pointer transition-all"><i class="fa-solid fa-users"></i> Guest Manifest Log</button>
                    </div>
                @endif
            </div>

        </aside>

    </div>

</x-admin-dashboard-layout>