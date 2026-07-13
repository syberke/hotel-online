<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f3; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d4d4d4; }
    [x-cloak] { display: none !important; }

    .selected-neon-row {
        background-color: #f5f5f5 !important;
        border-left: 3px solid #737373 !important;
    }
</style>

<x-admin-dashboard-layout>

    @if(session('success'))
        <div class="bg-emerald-900/90 border border-emerald-700 text-emerald-200 p-4 text-xs font-semibold uppercase tracking-wider mb-6 flex items-center shadow-md">
            <i class="fa-solid fa-circle-check mr-2 text-emerald-400 text-sm"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-950/95 border border-rose-800 text-rose-300 p-4 text-xs font-semibold uppercase tracking-wider mb-6 flex items-center shadow-md">
            <i class="fa-solid fa-triangle-exclamation mr-2 text-rose-400 text-sm"></i> {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['total'] }}</span>
                        <span class="text-[9px] text-neutral-400 font-medium">All Units</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Available Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['available'] }}</span>
                        <span class="text-[9px] font-bold text-emerald-600">{{ $stats['available_pct'] }}% <span class="text-neutral-400 font-normal">Vacant</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Reserved Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-700">{{ $stats['reserved'] }}</span>
                        <span class="text-[9px] font-bold text-amber-600">{{ $stats['reserved_pct'] }}% <span class="text-neutral-400 font-normal">Booked</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupied Rooms</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-700">{{ $stats['occupied'] }}</span>
                        <span class="text-[9px] font-bold text-blue-600">{{ $stats['occupied_pct'] }}% <span class="text-neutral-400 font-normal">Live</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Out Of Order</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-700">{{ $stats['maintenance'] }}</span>
                        <span class="text-[9px] font-bold text-amber-600">{{ $stats['maintenance_pct'] }}% <span class="text-neutral-400 font-normal">Maint.</span></span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Cleaning Process</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-purple-700">{{ $stats['cleaning'] }}</span>
                        <span class="text-[9px] font-bold text-purple-600">{{ $stats['cleaning_pct'] }}% <span class="text-neutral-400 font-normal">Turn</span></span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <form action="{{ request()->url() }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 lg:border-none pb-2 lg:pb-0">
                        <button type="button" onclick="switchTab('rooms')" id="tab-rooms" class="text-neutral-900 border-b-2 border-neutral-900 pb-1 px-0.5 transition-colors">Room List</button>
                        <button type="button" onclick="switchTab('types')" id="tab-types" class="hover:text-neutral-900 transition-colors pb-1 px-0.5 text-neutral-400">Room Types</button>
                    </div>

                    <div id="room-list-controls" class="flex flex-wrap items-center gap-3">
                        <div class="relative min-w-[220px] flex-1 lg:flex-none">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by room number or type..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        
                        <div class="relative flex-1 lg:flex-none">
                            <select name="status_filter" onchange="this.form.submit()" class="appearance-none w-full border border-neutral-200 bg-white pl-3 pr-8 py-2 text-xs font-medium text-neutral-700 focus:outline-none focus:border-neutral-900 cursor-pointer">
                                <option value="">All Statuses</option>
                                <option value="available" {{ request('status_filter') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="reserved" {{ request('status_filter') == 'reserved' ? 'selected' : '' }}>Reserved</option>
                                <option value="occupied" {{ request('status_filter') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                                <option value="maintenance" {{ request('status_filter') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="dirty" {{ request('status_filter') == 'dirty' ? 'selected' : '' }}>Cleaning</option>
                            </select>
                            <i class="fa-solid fa-chevron-down text-[9px] text-neutral-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>

                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">Apply</button>

                        @if(auth()->user()->role !== 'manager')
                            <button type="button" onclick="openAddRoomModal()" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add Room
                            </button>
                        @endif
                    </div>
                </form>

                <div id="room-list-section" class="overflow-x-auto custom-scrollbar pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-4 font-semibold">Room Number</th>
                                <th class="py-3 px-4 font-semibold">Room Type</th>
                                <th class="py-3 px-4 font-semibold">Floor</th>
                                <th class="py-3 px-4 font-semibold">Status / Rentang Jadwal</th>
                                <th class="py-3 px-4 font-semibold">Capacity</th>
                                <th class="py-3 px-4 font-semibold">Price / Night</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($rooms as $room)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-4 px-4 flex items-center gap-3">
                                        <img src="{{ $room->foto_url ?? 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1200' }}" class="w-12 h-8 object-cover border border-neutral-200 rounded-sm">
                                        <div>
                                            <span class="text-xs font-bold text-neutral-900 block font-mono">Room {{ $room->room_number }}</span>
                                            <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ $room->type_name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-neutral-800">{{ $room->type_name }}</td>
                                    <td class="py-4 px-4 font-mono">{{ substr($room->room_number, 0, strlen($room->room_number) - 2) ?: '1' }}F</td>
                                    <td class="py-4 px-4">
                                        @if($room->status == 'occupied')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Occupied</span>
                                            @if($room->active_booking)
                                                <span class="text-[9px] text-neutral-500 block font-semibold mt-1 bg-neutral-100 p-1 border-l-2 border-blue-500">
                                                    <i class="fa-regular fa-calendar-days text-[9px] mr-1 text-blue-600"></i>
                                                    {{ \Carbon\Carbon::parse($room->active_booking->check_in)->format('d M') }} s/d {{ \Carbon\Carbon::parse($room->active_booking->check_out)->format('d M Y') }}
                                                </span>
                                            @endif
                                        @elseif($room->status == 'reserved')
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Reserved</span>
                                            @if($room->active_booking)
                                                <span class="text-[9px] text-neutral-500 block font-semibold mt-1 bg-neutral-100 p-1 border-l-2 border-amber-500">
                                                    <i class="fa-regular fa-calendar-check text-[9px] mr-1 text-amber-700"></i>
                                                    {{ \Carbon\Carbon::parse($room->active_booking->check_in)->format('d M') }} s/d {{ \Carbon\Carbon::parse($room->active_booking->check_out)->format('d M Y') }}
                                                </span>
                                            @endif
                                        @elseif($room->status == 'available')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Available</span>
                                        @elseif($room->status == 'dirty')
                                            <span class="bg-purple-50 text-purple-800 border border-purple-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Cleaning</span>
                                        @else
                                            <span class="bg-red-50 text-red-800 border border-red-100 text-[9px] px-2 py-0.5 font-bold uppercase tracking-wide">Out Of Order</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-neutral-500 text-[11px]"><i class="fa-solid fa-user-friends mr-1"></i> Max {{ $room->max_capacity }} Pax</td>
                                    <td class="py-4 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center">
                                        @if(auth()->user()->role !== 'manager')
                                            <button type="button" onclick="openGlobalDropdown(event, {{ $room->id }}, '{{ $room->room_number }}')" class="dropdown-trigger-btn w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-500 cursor-pointer transition-colors shadow-xs">
                                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                            </button>
                                        @else
                                            <button type="button" onclick="openManagerViewModal({{ $room->id }})" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-800 cursor-pointer transition-colors shadow-xs"><i class="fa-solid fa-eye text-xs"></i></button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-neutral-400 font-sans italic">No physical luxury suites registry entries match filter criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-4 font-medium">
                        <span>Showing entries {{ $rooms->firstItem() ?? 0 }} to {{ $rooms->lastItem() ?? 0 }} of {{ $rooms->total() }} total rooms</span>
                        <div class="font-sans text-neutral-800">{{ $rooms->links() }}</div>
                    </div>
                </div>

                <div id="room-type-section" class="hidden pt-2 space-y-4">
                    <div class="flex justify-between items-center border-b border-neutral-100 pb-2">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-400">Ledger Matrix Classification</h3>
                        @if(auth()->user()->role !== 'manager')
                            <button type="button" onclick="openAddRoomTypeModal()" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1.5 transition-colors shadow-sm cursor-pointer">
                                <i class="fa-solid fa-plus text-[9px] mr-1"></i> Add New Class Type
                            </button>
                        @endif
                    </div>

                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                    <th class="py-3 px-4 font-semibold">Class Identity</th>
                                    <th class="py-3 px-4 font-semibold">Blueprint Layout Specs</th>
                                    <th class="py-3 px-4 font-semibold text-center">Max Capacity</th>
                                    <th class="py-3 px-4 font-semibold">Base Price / Night</th>
                                    <th class="py-3 px-4 font-semibold text-center">Active Inventory</th>
                                    <th class="py-3 px-4 text-center font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                                @forelse($roomTypesList as $type)
                                    <tr class="hover:bg-neutral-50/40 transition-colors">
                                        <td class="py-4 px-4 flex items-center gap-3">
                                            <img src="{{ $type->foto_url ?? 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?q=80&w=600' }}" class="w-14 h-9 object-cover border border-neutral-200 rounded-xs">
                                            <div>
                                                <span class="font-bold text-neutral-900 block">{{ $type->name }}</span>
                                                <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Perspective: {{ $type->view_perspective ?? 'Standard View' }}</span>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="max-w-xs space-y-0.5">
                                                <p class="text-neutral-800 font-semibold text-[11px] truncate">{{ $type->bed_configuration ?? 'Single/Double Bed' }} &bull; {{ $type->room_size ?? '-' }}</p>
                                                <p class="text-neutral-400 text-[10px] truncate">{{ $type->description ?? 'No core description logged.' }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-center font-mono font-bold text-neutral-800">{{ $type->max_capacity }} Pax</td>
                                        <td class="py-4 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($type->price, 0, ',', '.') }}</td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="bg-neutral-100 text-neutral-800 px-2 py-0.5 text-[10px] font-bold border border-neutral-200">{{ $roomCount[$type->id] ?? 0 }} Units</span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if(auth()->user()->role !== 'manager')
                                                <div class="flex items-center justify-center gap-1.5">
                                                    <button type="button" 
                                                            onclick="openEditRoomTypeModal({{ json_encode($type) }}, {{ $roomCount[$type->id] ?? 0 }})" 
                                                            class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-900 hover:text-white text-neutral-700 cursor-pointer flex items-center justify-center shadow-xs transition-colors">
                                                        <i class="fa-solid fa-pen text-xs"></i>
                                                    </button>
                                                    <button type="button" onclick="confirmDeleteRoomType({{ $type->id }}, '{{ addslashes($type->name) }}')" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-rose-600 hover:text-white text-rose-600 cursor-pointer flex items-center justify-center shadow-xs transition-colors">
                                                        <i class="fa-solid fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-neutral-400 text-[10px] font-bold uppercase tracking-wider">Read Only</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-neutral-400 font-sans italic">No room types configured yet inside PostgreSQL schema matrix.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Room Type Matrix</h3>
                </div>
                
                <div class="space-y-3.5 text-xs font-semibold text-neutral-500">
                    <div class="flex justify-between text-[8px] text-neutral-400 uppercase tracking-wider font-bold border-b border-neutral-50 pb-1.5">
                        <span>Room Type Class</span>
                        <div class="grid grid-cols-4 gap-1.5 w-36 text-center font-mono">
                            <span>Tot</span>
                            <span>Occ</span>
                            <span>Res</span>
                            <span>Avail</span>
                        </div>
                    </div>
                    @forelse($summary as $row)
                        <div class="flex justify-between items-center hover:text-neutral-900 transition-colors py-0.5">
                            <span class="text-neutral-800 font-bold truncate pr-2" style="max-w: calc(100% - 144px);">{{ $row['name'] }}</span>
                            <div class="grid grid-cols-4 gap-1.5 w-36 text-center font-mono font-bold text-neutral-700">
                                <span class="bg-neutral-50 py-0.5 text-neutral-900 border border-neutral-100 rounded-xs">{{ $row['total'] }}</span>
                                <span class="bg-blue-50/60 py-0.5 text-blue-600 border border-blue-50 rounded-xs">{{ $row['occupied'] }}</span>
                                <span class="bg-amber-50/60 py-0.5 text-amber-700 border border-amber-50 rounded-xs">{{ $row['reserved'] }}</span>
                                <span class="bg-emerald-50/60 py-0.5 text-emerald-600 border border-emerald-50 rounded-xs">{{ $row['available'] }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-2 text-neutral-400 font-normal">No type summary records compiled.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Inventory Overview</h3>
                </div>

                <div class="flex justify-between items-center text-xs font-bold text-neutral-800 px-1 mb-3">
                    <span class="font-serif">{{ now()->format('F Y') }}</span>
                    <span class="text-[9px] bg-neutral-900 text-white px-2 py-0.5 rounded font-mono uppercase">Today</span>
                </div>

                <div class="grid grid-cols-7 gap-y-2 text-center text-[10px] font-medium text-neutral-400 border-b border-neutral-100 pb-3">
                    <span class="font-bold">Mon</span><span class="font-bold">Tue</span><span class="font-bold">Wed</span><span class="font-bold">Thu</span><span class="font-bold">Fri</span><span class="font-bold">Sat</span><span class="font-bold">Sun</span>
                    
                    @php
                        $startOfMonth = now()->startOfMonth();
                        $daysInMonth = now()->daysInMonth;
                        $dayOfWeek = $startOfMonth->dayOfWeekIso;
                        $todayDay = now()->day;
                    @endphp

                    @for($i = 1; $i < $dayOfWeek; $i++) <span></span> @endfor

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @if($day == $todayDay)
                            <span class="py-1 bg-amber-800 text-white font-bold flex items-center justify-center rounded-none shadow-sm">{{ $day }}</span>
                        @else
                            <span class="py-1 text-neutral-700">{{ $day }}</span>
                        @endif
                    @endfor
                </div>

                <div class="grid grid-cols-2 gap-x-2 gap-y-1.5 text-[9px] font-bold uppercase tracking-wider text-neutral-500 pt-3">
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-emerald-500 inline-block"></span> <span>Available</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-amber-500 inline-block"></span> <span>Reserved</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-blue-500 inline-block"></span> <span>Occupied</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-purple-500 inline-block"></span> <span>Cleaning</span></div>
                    <div class="flex items-center gap-1.5"><span class="w-2 h-2 bg-red-500 inline-block"></span> <span>Maint. Block</span></div>
                </div>
            </div>
        </aside>

    </div>

    @if(auth()->user()->role !== 'manager')
        <div id="addRoomModal" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
            <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl flex flex-col font-sans">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Register New Enclosure Room</h4>
                    <button type="button" onclick="closeAddRoomModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
                </div>
                
                <form action="{{ route('rooms.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Room Number (Mono-Index)</label>
                        <input type="text" name="room_number" placeholder="e.g., 101, 305" required class="w-full px-3 py-2 text-xs font-mono font-bold border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Room Type Allocation</label>
                        <select name="room_type_id" required class="w-full px-3 py-2 text-xs font-medium border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-white">
                            @foreach($roomTypesList as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} (Rp {{ number_format($type->price,0,',','.') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Initial Readiness Status</label>
                        <select name="status" required class="w-full px-3 py-2 text-xs font-medium border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-white">
                            <option value="available">Available (Vacant Ready)</option>
                            <option value="dirty">Dirty / Cleaning Turn</option>
                            <option value="maintenance">Maintenance Block (OOS)</option>
                        </select>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">Confirm Matrix</button>
                        <button type="button" onclick="closeAddRoomModal()" class="border border-neutral-200 text-neutral-700 hover:bg-neutral-50 font-bold text-[10px] uppercase tracking-widest px-4 py-2.5 bg-white cursor-pointer">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div id="managerRoomViewModal" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-md w-full p-6 shadow-2xl flex flex-col font-sans text-neutral-900">
            <div class="flex justify-between items-center border-b border-neutral-200 pb-3 mb-4">
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-amber-800">Enclosure Room Audit Blueprint</h4>
                    <span id="mv_title_room" class="text-base font-serif font-bold text-neutral-900 mt-0.5 block"></span>
                </div>
                <button type="button" onclick="closeManagerViewModal()" class="text-neutral-400 hover:text-neutral-900 text-lg cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4 bg-neutral-50 p-3 border border-neutral-100 text-xs">
                    <div>
                        <span class="block text-[9px] font-bold uppercase text-neutral-400">Kategori Kamar</span>
                        <span id="mv_room_type" class="font-bold text-neutral-800 font-sans"></span>
                    </div>
                    <div>
                        <span class="block text-[9px] font-bold uppercase text-neutral-400">Harga Per Malam</span>
                        <span id="mv_room_price" class="font-mono font-bold text-neutral-900"></span>
                    </div>
                    <div class="mt-2">
                        <span class="block text-[9px] font-bold uppercase text-neutral-400">Kapasitas Maksimal</span>
                        <span id="mv_room_cap" class="font-medium text-neutral-700"></span>
                    </div>
                    <div class="mt-2">
                        <span class="block text-[9px] font-bold uppercase text-neutral-400">Status Kesiapan</span>
                        <span id="mv_room_status" class="inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider"></span>
                    </div>
                </div>

                <div id="mv_booking_section" class="border border-neutral-200 p-4 space-y-3 hidden">
                    <span id="mv_booking_heading" class="text-[9px] font-bold uppercase tracking-widest text-blue-700 block border-b border-blue-50 pb-1.5"><i class="fa-solid fa-user-tag mr-1"></i> Guest Booking Manifest</span>
                    <div class="text-xs space-y-2 text-neutral-600 font-medium">
                        <div class="flex justify-between"><span>Nama Tamu Terdaftar</span><span id="mv_guest_name" class="font-bold text-neutral-900"></span></div>
                        <div class="flex justify-between"><span>Email Korespondensi</span><span id="mv_guest_email" class="font-mono text-neutral-800"></span></div>
                        <div class="flex justify-between"><span>Jumlah Tamu Kamar</span><span id="mv_guest_count" class="font-bold text-neutral-900"></span></div>
                        <div class="flex justify-between border-t pt-2 mt-2 border-neutral-100">
                            <span>Rentang Jadwal Inap</span>
                            <span id="mv_stay_dates" class="font-bold text-neutral-900 bg-neutral-100 px-2 py-0.5 text-[10px]"></span>
                        </div>
                    </div>
                </div>

                <div id="mv_empty_section" class="border border-dashed border-neutral-200 p-6 text-center text-xs text-neutral-400 italic hidden">
                    <i class="fa-solid fa-bed text-lg text-neutral-300 block mb-1"></i>
                    Unit kamar saat ini kosong dan tidak memiliki manifes inap aktif.
                </div>
            </div>

            <button type="button" onclick="closeManagerViewModal()" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 mt-5 shadow-sm">Dismiss Audit Blueprint</button>
        </div>
    </div>

    <div id="global-action-dropdown" class="hidden fixed w-48 bg-white border border-neutral-200 shadow-2xl z-50 text-left font-sans text-xs">
        <div class="p-2 border-b border-neutral-100 bg-neutral-50 text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Ubah Status Kamar <span id="drop-room-title" class="font-mono text-neutral-900"></span></div>
        <form id="form-update-status" action="" method="POST" class="m-0">
            @csrf
            <button name="status" value="available" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-emerald-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Set Available</button>
            <button name="status" value="dirty" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-purple-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span> Set Cleaning</button>
            <button name="status" value="maintenance" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-amber-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Set Out Of Order</button>
        </form>
        <div class="border-t border-neutral-100 p-1">
            <form id="form-delete-unit" action="" method="POST" data-confirm="Hapus unit kamar ini?" data-confirm-title="Hapus Unit Kamar" class="m-0">
                @csrf @method('DELETE')
                <button type="submit" class="w-full text-left px-3 py-1.5 hover:bg-rose-50 text-rose-600 font-bold rounded-xs flex items-center cursor-pointer"><i class="fa-regular fa-trash-can mr-2"></i> Delete Unit</button>
            </form>
        </div>
    </div>

@if(auth()->user()->role !== 'manager')
        <div id="addRoomTypeModal" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4 overflow-y-auto">
            <div class="bg-white border border-neutral-200 max-w-2xl w-full p-6 md:p-8 shadow-2xl flex flex-col font-sans my-8">
                
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-6">
                    <div>
                        <h4 id="rtFormTitle" class="text-xs font-bold uppercase tracking-widest text-neutral-900">Add New Room Type Class</h4>
                        <p class="text-[10px] text-neutral-400 mt-0.5">Configure the specifications, imagery, and structural matrix for room blueprint.</p>
                    </div>
                    <button type="button" onclick="closeAddRoomTypeModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer transition-colors">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>
                
                <form id="rtForm" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" id="rtTypeId" name="type_id" value="">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Room Type Name</label>
                            <input type="text" id="rtName" name="name" placeholder="e.g., Luxury Beachfront Suite" required 
                                   class="w-full px-3 py-2 text-xs font-semibold border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Image URL Access Link (`foto_url`)</label>
                            <input type="url" id="rtFotoUrl" name="foto_url" placeholder="https://images.unsplash.com/..." 
                                   class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 font-mono transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Max Capacity (`max_capacity`)</label>
                            <div class="relative h-9">
                                <input type="number" id="rtCapacity" name="max_capacity" min="1" max="12" placeholder="2" required 
                                       class="w-full h-full pl-3 pr-10 py-2 text-xs font-bold border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 font-mono transition-all">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-neutral-400 uppercase pointer-events-none">Pax</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Room Dimension Size (`room_size`)</label>
                            <div class="relative h-9">
                                <input type="text" id="rtRoomSize" name="room_size" placeholder="e.g., 45 m² or 60 sqm" 
                                       class="w-full h-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 transition-all">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Base Price / Night (`price`)</label>
                            <div class="relative h-9">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-neutral-400 pointer-events-none">Rp</span>
                                <input type="number" id="rtPrice" name="price" min="0" placeholder="1250000" required 
                                       class="w-full h-full pl-8 pr-3 py-2 text-xs font-bold border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 font-mono transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Bed Configuration (`bed_configuration`)</label>
                            <input type="text" id="rtBedConfig" name="bed_configuration" placeholder="e.g., 1 King Bed or 2 Twin Beds" 
                                   class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 transition-all">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">View Perspective Landscape (`view_perspective`)</label>
                            <input type="text" id="rtViewPerspective" name="view_perspective" placeholder="e.g., Ocean Horizon View, Tropical Garden" 
                                   class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Core Description Text</label>
                        <textarea id="rtDescription" name="description" placeholder="Write premium core profile summary description layout..." rows="3" 
                                  class="w-full px-3 py-2 text-xs font-medium border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 resize-none transition-all"></textarea>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-500 mb-1.5">Luxury Room Amenities Matrix (`amenities` - Separated with commas)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-xs"><i class="fa-solid fa-wand-magic-sparkles"></i></span>
                            <input type="text" id="rtAmenities" name="amenities" placeholder="Free Wi-Fi, Private Pool, Mini Bar, Bathtub" 
                                   class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 focus:ring-1 focus:ring-neutral-900 bg-neutral-50/30 transition-all">
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-neutral-100 items-center justify-end">
                        <button type="button" onclick="closeAddRoomTypeModal()" 
                                class="border border-neutral-200 text-neutral-700 hover:bg-neutral-50 font-bold text-[10px] uppercase tracking-widest px-5 py-3 bg-white cursor-pointer transition-colors rounded-none">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-3 px-6 transition-all shadow-sm cursor-pointer rounded-none active:translate-y-[1px]">
                            Commit Structure Model
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</x-admin-dashboard-layout>

<script type="text/javascript">
    function openManagerViewModal(roomId) {
        const modal = document.getElementById('managerRoomViewModal');
        const bookingSection = document.getElementById('mv_booking_section');
        const emptySection = document.getElementById('mv_empty_section');
        const statusBadge = document.getElementById('mv_room_status');

        fetch(`/admin/rooms/${roomId}/json-detail`)
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('mv_title_room').innerText = 'Room ' + res.room.room_number;
                    document.getElementById('mv_room_type').innerText = res.room.type_name;
                    document.getElementById('mv_room_price').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(res.room.price);
                    document.getElementById('mv_room_cap').innerText = 'Max ' + res.room.max_capacity + ' Persons';

                    statusBadge.innerText = res.room.status;
                    if (res.room.status === 'available') {
                        statusBadge.className = "inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-800 border border-emerald-100";
                    } else if (res.room.status === 'reserved') {
                        statusBadge.className = "inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-amber-50 text-amber-800 border border-amber-100";
                    } else if (res.room.status === 'occupied') {
                        statusBadge.className = "inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-blue-50 text-blue-800 border border-blue-100";
                    } else if (res.room.status === 'dirty') {
                        statusBadge.className = "inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-purple-50 text-purple-800 border border-purple-100";
                    } else {
                        statusBadge.className = "inline-block mt-0.5 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider bg-red-50 text-red-800 border border-red-100";
                    }

                    if (res.booking) {
                        document.getElementById('mv_booking_heading').innerHTML = res.room.status === 'occupied'
                            ? '<i class="fa-solid fa-user-tag mr-1"></i> Live Active Guest Stay Manifest'
                            : '<i class="fa-solid fa-calendar-check mr-1"></i> Upcoming Reservation Manifest';
                        document.getElementById('mv_guest_name').innerText = res.booking.guest_name;
                        document.getElementById('mv_guest_email').innerText = res.booking.guest_email;
                        document.getElementById('mv_guest_count').innerText = res.booking.guests_count + ' Persons';
                        
                        const inDate = new Date(res.booking.check_in).toLocaleDateString('id-ID', {day: 'numeric', month: 'short'});
                        const outDate = new Date(res.booking.check_out).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric'});
                        document.getElementById('mv_stay_dates').innerText = inDate + ' - ' + outDate;

                        bookingSection.classList.remove('hidden');
                        emptySection.classList.add('hidden');
                    } else {
                        bookingSection.classList.add('hidden');
                        emptySection.classList.remove('hidden');
                    }

                    modal.classList.remove('hidden');
                } else {
                    OasisDialog.error('Gagal memproses data visualisasi kamar.');
                }
            })
            .catch(() => OasisDialog.error('Terjadi gangguan transmisi jaringan data.'));
    }

    function closeManagerViewModal() {
        document.getElementById('managerRoomViewModal').classList.add('hidden');
    }

    function openGlobalDropdown(event, roomId, roomNumber) {
        event.stopPropagation();
        const dropdown = document.getElementById('global-action-dropdown');
        const triggerBtn = event.currentTarget;
        
        document.getElementById('drop-room-title').innerText = '(Rm ' + roomNumber + ')';
        document.getElementById('form-update-status').action = `/rooms/${roomId}/update-status`;
        document.getElementById('form-delete-unit').action = `/rooms/${roomId}/delete`;
        
        const rect = triggerBtn.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        dropdown.style.left = (rect.left + window.scrollX - 160) + 'px';
        dropdown.classList.remove('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('global-action-dropdown');
        if (dropdown && !dropdown.contains(event.target) && !event.target.closest('.dropdown-trigger-btn')) {
            dropdown.classList.add('hidden');
        }
    });

    function openAddRoomModal() {
        const modal = document.getElementById('addRoomModal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeAddRoomModal() {
        const modal = document.getElementById('addRoomModal');
        if (modal) modal.classList.add('hidden');
    }

    function switchTab(tab) {
        const roomListSection = document.getElementById('room-list-section');
        const roomTypeSection = document.getElementById('room-type-section');
        const roomListControls = document.getElementById('room-list-controls');
        const tabRooms = document.getElementById('tab-rooms');
        const tabTypes = document.getElementById('tab-types');

        if (tab === 'rooms') {
            roomListSection.classList.remove('hidden');
            roomTypeSection.classList.add('hidden');
            roomListControls.classList.remove('hidden');
            tabRooms.classList.add('text-neutral-900', 'border-b-2', 'border-neutral-900');
            tabRooms.classList.remove('text-neutral-400');
            tabTypes.classList.add('text-neutral-400');
            tabTypes.classList.remove('text-neutral-900', 'border-b-2', 'border-neutral-900');
        } else {
            roomListSection.classList.add('hidden');
            roomTypeSection.classList.remove('hidden');
            roomListControls.classList.add('hidden');
            tabTypes.classList.add('text-neutral-900', 'border-b-2', 'border-neutral-900');
            tabTypes.classList.remove('text-neutral-400');
            tabRooms.classList.add('text-neutral-400');
            tabRooms.classList.remove('text-neutral-900', 'border-b-2', 'border-neutral-900');
        }
    }

    // ==========================================
    // CONTROLLER LOGIC: ROOM TYPES SCHEMA CRUD
    // ==========================================
    function openAddRoomTypeModal() {
        const modal = document.getElementById('addRoomTypeModal');
        if (modal) {
            document.getElementById('rtForm').reset();
            document.getElementById('rtForm').action = '/admin/room-types/store';
            document.getElementById('rtFormTitle').innerText = 'Add New Room Type Class';
            document.getElementById('rtTypeId').value = '';
            modal.classList.remove('hidden');
        }
    }

    function openEditRoomTypeModal(typeObj, countUnits) {
        const modal = document.getElementById('addRoomTypeModal');
        if (modal) {
            document.getElementById('rtFormTitle').innerText = 'Edit Room Type: ' + typeObj.name;
            document.getElementById('rtTypeId').value = typeObj.id;
            document.getElementById('rtName').value = typeObj.name;
            document.getElementById('rtDescription').value = typeObj.description || '';
            document.getElementById('rtCapacity').value = typeObj.max_capacity || 2;
            document.getElementById('rtPrice').value = Math.round(typeObj.price);
            document.getElementById('rtFotoUrl').value = typeObj.foto_url || '';
            document.getElementById('rtRoomSize').value = typeObj.room_size || '';
            document.getElementById('rtBedConfig').value = typeObj.bed_configuration || '';
            document.getElementById('rtViewPerspective').value = typeObj.view_perspective || '';
            document.getElementById('rtAmenities').value = typeObj.amenities || '';
            
            document.getElementById('rtForm').action = `/admin/room-types/${typeObj.id}/update`;
            modal.classList.remove('hidden');
        }
    }

    function closeAddRoomTypeModal() {
        const modal = document.getElementById('addRoomTypeModal');
        if (modal) modal.classList.add('hidden');
    }

    async function confirmDeleteRoomType(id, name) {
        const confirmed = await OasisDialog.confirm(
            `Hapus room type "${name}"? Tindakan ini dapat memengaruhi relasi inventory kamar.`,
            'Hapus Room Type'
        );
        if (confirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/room-types/${id}/delete`;
            form.innerHTML = '{{ csrf_field() }} @method("DELETE")';
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
