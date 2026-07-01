<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full text-xs">
        
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                
                <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-4 border-b border-neutral-100 pb-1 font-sans">
                    <a href="{{ request()->fullUrlWithQuery(['call_tab' => 'all']) }}" class="pb-2 px-0.5 {{ $currentTab == 'all' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">All ({{ $countAll }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['call_tab' => 'due_today']) }}" class="pb-2 px-0.5 {{ $currentTab == 'due_today' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">Due Today ({{ $countDueToday }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['call_tab' => 'upcoming']) }}" class="pb-2 px-0.5 {{ $currentTab == 'upcoming' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">Upcoming ({{ $countUpcoming }})</a>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full">
                    <input type="hidden" name="call_tab" value="{{ $currentTab }}">
                    <div class="relative flex-1">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or room..." class="w-full pr-3 pl-9 py-2 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    </div>
                    <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 font-bold uppercase transition-colors rounded-none">Search</button>
                </form>

                <div class="space-y-2">
                    <span class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 block mb-1"><i class="fa-regular fa-clock mr-1 text-blue-600"></i> Active Log Stream</span>
                    
                    <div class="divide-y border border-neutral-100 rounded-none bg-white font-semibold text-neutral-600">
                        @forelse($wakeupCallsList as $call)
                            @php
                                $isRowSelected = $selectedCall && $selectedCall->id == $call->id;
                            @endphp
                            <div onclick="window.location.href='{{ request()->fullUrlWithQuery(['selected_call_id' => $call->id]) }}'" 
                                 class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer {{ $isRowSelected ? 'bg-blue-50/20 border-l-2 border-blue-600' : '' }}">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 text-center font-mono text-neutral-900 font-bold">
                                        <span class="block text-sm">{{ $call->room_number }}</span>
                                        <span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Room</span>
                                    </div>
                                    <div class="border-l pl-3">
                                        <span class="text-neutral-900 font-bold block truncate max-w-[150px]">{{ $call->guest_name }}</span>
                                        <span class="text-[9px] text-neutral-400 font-mono font-normal block mt-0.5">{{ \Carbon\Carbon::parse($call->call_date)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-mono text-neutral-900 font-bold text-sm block">{{ \Carbon\Carbon::parse($call->call_time)->format('h:i A') }}</span>
                                    
                                    @if($call->status == 'completed')
                                        <span class="bg-emerald-50 text-emerald-800 text-[7px] px-1.5 py-0.2 uppercase font-sans font-bold border border-emerald-100 rounded-none inline-block">Completed</span>
                                    @elseif($call->status == 'in_progress')
                                        <span class="bg-amber-50 text-amber-800 text-[7px] px-1.5 py-0.2 uppercase font-sans font-bold border border-amber-100 rounded-none inline-block">In Progress</span>
                                    @else
                                        <span class="bg-blue-50 text-blue-800 text-[7px] px-1.5 py-0.2 uppercase font-sans font-bold border border-blue-100 rounded-none inline-block">Scheduled</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-6 text-center text-neutral-400">No active wake-up call sheets logs detected.</div>
                        @endforelse
                    </div>
                </div>

                <div class="flex justify-between items-center text-[10px] text-neutral-400 font-medium pt-1">
                    <span>Showing entries {{ $wakeupCallsList->firstItem() ?? 0 }} to {{ $wakeupCallsList->lastItem() ?? 0 }} of {{ $wakeupCallsList->total() }} entries</span>
                    <div class="font-mono font-bold text-neutral-800">
                        {{ $wakeupCallsList->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4">
            @if($selectedCall)
                <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Wake-up Call Target Details</h3>
                        <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide rounded-none">{{ $selectedCall->status }}</span>
                    </div>

                    <div class="flex items-center gap-3.5 py-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($selectedCall->guest_name) }}&background=18181b&color=ffffff" class="w-10 h-10 object-cover border rounded-sm">
                        <div>
                            <h4 class="text-sm font-bold text-neutral-900">{{ $selectedCall->guest_name }}</h4>
                            <span class="text-[9px] text-neutral-400 font-mono block mt-0.5 truncate max-w-[190px]">{{ $selectedCall->guest_email }} &bull; {{ $selectedCall->guest_phone ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2 border-t pt-3 font-mono text-neutral-900 font-bold text-center">
                        <div class="bg-neutral-50 p-2 border"><span class="text-[8px] font-sans uppercase font-bold text-neutral-400 block mb-0.5">Room</span>{{ $selectedCall->room_number }}</div>
                        <div class="bg-neutral-50 p-2 border truncate text-[10px]"><span class="text-[8px] font-sans uppercase font-bold text-neutral-400 block mb-0.5">Type</span>{{ $selectedCall->room_type_name }}</div>
                        <div class="bg-neutral-50 p-2 border text-[10px]"><span class="text-[8px] font-sans uppercase font-bold text-neutral-400 block mb-0.5">Stay In</span>{{ \Carbon\Carbon::parse($selectedCall->check_in)->format('d M') }}</div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                    <div class="flex justify-between items-center border-b pb-2">
                        <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Wake-up Call Information</h3>
                    </div>

                    <div class="space-y-3.5 font-semibold text-neutral-700">
                        <div class="flex items-center justify-between gap-4">
                            <span class="text-neutral-400 font-normal"><i class="fa-regular fa-clock w-4 mr-1 text-center"></i> Wake-up Time</span>
                            <div class="font-mono text-neutral-900 font-bold text-sm bg-neutral-50 px-3 py-1 border">
                                {{ \Carbon\Carbon::parse($selectedCall->call_time)->format('h:i A') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-neutral-400 font-normal"><i class="fa-regular fa-calendar w-4 mr-1 text-center"></i> Scheduled Date</span>
                            <input type="text" value="{{ \Carbon\Carbon::parse($selectedCall->call_date)->format('d M Y') }}" class="border p-1.5 font-mono text-[11px] bg-neutral-50 text-right w-40 font-bold text-neutral-900" readonly>
                        </div>

                        <div class="flex items-center justify-between gap-4">
                            <span class="text-neutral-400 font-normal"><i class="fa-solid fa-phone-volume w-4 mr-1 text-center"></i> Routing Method</span>
                            <span class="text-neutral-900 font-sans font-bold">In-Room PBX Line</span>
                        </div>

                        <div class="border-t pt-3">
                            <span class="text-neutral-400 font-normal block mb-1.5"><i class="fa-solid fa-note-sticky w-4 mr-1 text-center"></i> Front Office Special Notes</span>
                            <span class="text-neutral-900 block font-medium bg-neutral-50 p-2.5 border border-neutral-100 leading-relaxed">{{ $selectedCall->notes ?? 'No special operational delivery instructions flagged.' }}</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 border border-dashed text-center text-neutral-400">Select a wake-up log item entry block from the left panel column list view.</div>
            @endif
        </div>

        <aside class="lg:col-span-3 space-y-4 shrink-0">
            <div class="bg-white border border-neutral-200 p-5 shadow-sm space-y-4">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Today's Summary Metrics</h4>
                
                <div class="grid grid-cols-2 gap-3 font-semibold text-center text-neutral-500">
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <span class="text-lg font-bold text-neutral-900 block font-mono">{{ $countDueToday }}</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans mt-0.5">Due Today</span>
                    </div>
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <span class="text-lg font-bold text-neutral-900 block font-mono">{{ $countUpcoming }}</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans mt-0.5">Upcoming</span>
                    </div>
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center col-span-2">
                        <span class="text-lg font-bold text-emerald-600 block font-mono">{{ $countCompleted }}</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans mt-0.5">Completed Manifest</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[11px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1">Operational Action Gateway</h4>
                <div class="space-y-2 font-sans normal-case">
                    <button type="button" onclick="window.print()" class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-print text-neutral-400 text-center w-4 text-xs"></i> Print Registry List (Today)
                    </button>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>