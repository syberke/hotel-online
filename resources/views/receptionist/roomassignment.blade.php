<x-receptionist-dashboard-layout>
    @if(session('success'))
        <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold uppercase tracking-wider">
            <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600 text-xs"><i class="fa-solid fa-hotel"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Arrivals Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $arrivalsCount }}</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-amber-50 border border-amber-100 flex items-center justify-center text-amber-600 text-xs"><i class="fa-solid fa-user-clock"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Unassigned</span>
                <span class="text-xl font-bold text-amber-600 block font-mono mt-0.5">{{ $unassignedCount }}</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fa-solid fa-circle-check"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Assigned Today</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $assignedCount }}</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Reservations</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex items-center gap-4 shadow-sm">
            <div class="w-10 h-10 bg-purple-50 border border-purple-100 flex items-center justify-center text-purple-600 text-xs"><i class="fa-solid fa-door-open"></i></div>
            <div>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Available Rooms</span>
                <span class="text-xl font-bold text-neutral-900 block font-mono mt-0.5">{{ $freeRoomsCount }}</span>
                <span class="text-[9px] text-neutral-400 font-normal block">Rooms Free</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start w-full mt-6">
        
        <div class="xl:col-span-8 space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b pb-3">
                    <div class="text-xs font-bold uppercase tracking-wider text-neutral-900 font-sans">
                        Unassigned Queue ({{ $unassignedReservations->count() }})
                    </div>

                    <div class="flex items-center gap-3 w-full md:w-auto text-xs">
                        <div class="relative flex-1 md:flex-none md:min-w-[240px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or booking ID..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white font-bold uppercase tracking-wider px-4 py-1.5 transition-colors">Apply</button>
                    </div>
                </form>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3">Reservation ID</th>
                                <th class="py-3 px-3">Guest Profile</th>
                                <th class="py-3 px-3">Check-In</th>
                                <th class="py-3 px-3">Check-Out</th>
                                <th class="py-3 px-3">Class Type</th>
                                <th class="py-3 px-3 text-center">Manifest</th>
                                <th class="py-3 px-3">Status</th>
                                <th class="py-3 px-3 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($unassignedReservations as $res)
                                @php 
                                    $isSelectedRow = $activeTarget && $activeTarget->id == $res->id;
                                @endphp
                                <tr class="hover:bg-neutral-50/40 transition-colors {{ $isSelectedRow ? 'bg-blue-50/40 font-bold' : '' }}">
                                    <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">#RES-OA-{{ $res->id }}</td>
                                    <td class="py-3.5 px-3">
                                        <span class="font-bold text-neutral-900 block">{{ $res->guest_name }}</span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">{{ $res->guest_phone }}</span>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-neutral-700">{{ \Carbon\Carbon::parse($res->check_in)->format('d M Y') }}</td>
                                    <td class="py-3.5 px-3 font-mono text-neutral-700">{{ \Carbon\Carbon::parse($res->check_out)->format('d M Y') }}</td>
                                    <td class="py-3.5 px-3 text-neutral-900 font-bold">{{ $res->room_type }}</td>
                                    <td class="py-3.5 px-3 font-mono text-center">{{ $res->guests_count }} Pax</td>
                                    <td class="py-3.5 px-3"><span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">{{ $res->status }}</span></td>
                                    <td class="py-3.5 px-3 text-center">
                                        <a href="{{ request()->fullUrlWithQuery(['selected_booking_id' => $res->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white text-[10px] font-bold uppercase px-3 py-1 transition-colors rounded-none">Select Target</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-6 text-center text-neutral-400">No unassigned arrivals pipeline registered today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <div class="flex items-center justify-between border-b pb-3 text-xs font-semibold">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Property Building Floor Grid Map</h3>
                </div>

                <div class="space-y-4 text-[11px] font-bold relative z-20">
                    @foreach($floorsGrid as $floorNumber => $rooms)
                        <div class="flex items-center gap-4 relative z-30">
                            <div class="w-10 py-3 bg-neutral-100 text-neutral-500 font-mono text-center border font-bold shrink-0 select-none">FL {{ $floorNumber }}</div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-8 gap-2 w-full text-center relative">
                                @foreach($rooms as $room)
                                    @php
                                        if($room->status == 'occupied') {
                                            $styleClass = 'bg-blue-50/50 border-blue-200 text-blue-800';
                                            $statusText = 'Occupied';
                                        } elseif($room->status == 'dirty') {
                                            $styleClass = 'bg-purple-50/50 border-purple-200 text-purple-800';
                                            $statusText = 'Dirty';
                                        } elseif($room->status == 'maintenance') {
                                            $styleClass = 'bg-amber-50/50 border-amber-200 text-amber-800';
                                            $statusText = 'OOO';
                                        } else {
                                            $styleClass = 'bg-emerald-50/40 border-emerald-200 text-emerald-800';
                                            $statusText = 'Available';
                                        }
                                    @endphp
                                    <div class="border p-2 {{ $styleClass }} rounded-none shadow-xs relative group cursor-pointer hover:border-neutral-900 transition-all">
                                        <span class="block font-mono font-bold text-xs">{{ $room->room_number }}</span>
                                        <span class="text-[7px] font-sans uppercase font-bold tracking-tight block mt-0.5">{{ $statusText }}</span>
                                        
                                        <div class="hidden group-hover:block absolute bottom-full left-1/2 -translate-x-1/2 bg-neutral-950 text-white text-[9px] font-sans font-normal px-2.5 py-1.5 shadow-2xl z-50 whitespace-nowrap rounded-none mb-2 pointer-events-none border border-neutral-800">
                                            Room {{ $room->room_number }} &bull; Standard Suite
                                            <div class="absolute top-full left-1/2 -translate-x-1/2 w-0 h-0 border-x-4 border-x-transparent border-t-4 border-t-neutral-950"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-6 pt-4 border-t border-neutral-100 text-[10px] uppercase font-bold text-neutral-400 select-none">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-emerald-500 inline-block"></span> Available</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-blue-600 inline-block"></span> Occupied</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-amber-500 inline-block"></span> Maintenance</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 bg-purple-500 inline-block"></span> Dirty Queue</span>
                </div>
            </div>
        </div>

        <aside class="xl:col-span-4 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            @if($activeTarget)
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Assign Allocation</h3>
                </div>

                <div class="flex items-center gap-3.5 py-1">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($activeTarget->guest_name) }}&background=18181b&color=ffffff" class="w-10 h-10 object-cover border rounded-sm">
                    <div>
                        <h4 class="text-sm font-bold text-neutral-900">{{ $activeTarget->guest_name }}</h4>
                        <span class="text-[9px] text-neutral-400 font-mono font-normal mt-0.5 block">#RES-OA-{{ $activeTarget->id }} &bull; Confirmed</span>
                    </div>
                </div>

                <div class="space-y-3 text-xs font-semibold text-neutral-600 border-t pt-3.5">
                    <div class="flex justify-between"><span>Check-in Date</span><span class="text-neutral-900 font-mono">{{ \Carbon\Carbon::parse($activeTarget->check_in)->format('d M Y') }}</span></div>
                    <div class="flex justify-between"><span>Check-out Date</span><span class="text-neutral-900 font-mono">{{ \Carbon\Carbon::parse($activeTarget->check_out)->format('d M Y') }}</span></div>
                    <div class="flex justify-between"><span>Required Class</span><span class="text-blue-600 font-bold">{{ $activeTarget->room_type }}</span></div>
                    <div class="flex justify-between"><span>Total Manifest</span><span class="text-neutral-900 font-mono">{{ $activeTarget->guests_count }} Pax</span></div>
                </div>

                <form action="{{ route('receptionist.roomassignment') }}" method="POST" class="border-t pt-4 space-y-4">
                    @csrf
                    <input type="hidden" name="submit_assignment_booking_id" value="{{ $activeTarget->id }}">

                    <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider">
                        <span class="text-neutral-400 text-[10px]">Select Ready Room Physical</span>
                    </div>

                    <div class="space-y-2 text-xs font-semibold text-neutral-700 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                        @forelse($availablePhysicalRooms as $index => $freeRoom)
                            <label class="border {{ $index == 0 ? 'border-neutral-900' : 'border-neutral-200' }} p-2.5 flex items-center justify-between bg-neutral-50/40 hover:border-neutral-900 cursor-pointer select-none rounded-none block">
                                <div class="flex items-center gap-2.5">
                                    <input type="radio" name="assign_selected_room_id" value="{{ $freeRoom->id }}" {{ $index == 0 ? 'checked' : '' }} class="border-neutral-3">
                                    <span class="text-neutral-900 font-bold font-mono">Room {{ $freeRoom->room_number }}</span>
                                </div>
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[8px] font-bold px-1.5 uppercase tracking-wide">Vacant Clean</span>
                            </label>
                        @empty
                            <div class="p-4 text-center text-rose-600 bg-rose-50 border border-rose-100 text-[11px]">
                                No vacant clean inventory currently available for this specific room class.
                            </div>
                        @endforelse
                    </div>

                    <div class="pt-2">
                        <button type="submit" {{ count($availablePhysicalRooms) == 0 ? 'disabled' : '' }} class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 text-xs font-bold uppercase tracking-wider rounded-none cursor-pointer transition-colors shadow-sm disabled:bg-neutral-300 disabled:cursor-not-allowed text-center">
                            <i class="fa-solid fa-circle-check mr-1 text-[11px]"></i> Commit Room Assignment
                        </button>
                    </div>
                </form>
            @else
                <div class="text-center py-12 text-neutral-400 text-xs font-medium">No unassigned queue selected. Select a radar row to open parameters.</div>
            @endif
        </aside>
    </div>
</x-receptionist-dashboard-layout>