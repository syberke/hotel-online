<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Reservations</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $totalReservations }}</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5">Live Core</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Arrivals</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $arrivalsCount }}</span>
                <span class="text-[10px] font-medium text-neutral-400">Expected</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Departures</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $departuresCount }}</span>
                <span class="text-[10px] font-medium text-neutral-400">Out House</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In-House Guests</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-emerald-700">{{ $inHouseCount }}</span>
                <span class="text-[10px] font-bold text-emerald-600">Active</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Revenue (This Month)</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-sm font-bold text-neutral-900 font-mono">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span>
                <span class="text-[9px] text-neutral-400 font-sans">Settled</span>
            </div>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-8">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                <form action="{{ url()->current() }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                    <input type="hidden" name="status_tab" value="{{ $currentTab }}">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservations List Stream</h3>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:flex-none md:min-w-[260px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by guest name, ID, phone..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase transition-colors">Search</button>
                        
                    </div>
                </form>

                <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-4 border-b border-neutral-50 pb-1">
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => 'all']) }}" class="pb-2 px-0.5 {{ $currentTab == 'all' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">All ({{ $tabCounters['all'] }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => 'confirmed']) }}" class="pb-2 px-0.5 {{ $currentTab == 'confirmed' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">Confirmed ({{ $tabCounters['confirmed'] }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => 'tentative']) }}" class="pb-2 px-0.5 {{ $currentTab == 'tentative' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">Tentative ({{ $tabCounters['tentative'] }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => 'cancelled']) }}" class="pb-2 px-0.5 {{ $currentTab == 'cancelled' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">Cancelled ({{ $tabCounters['cancelled'] }})</a>
                    <a href="{{ request()->fullUrlWithQuery(['status_tab' => 'no_show']) }}" class="pb-2 px-0.5 {{ $currentTab == 'no_show' ? 'text-neutral-900 border-b-2 border-neutral-900 font-bold' : 'hover:text-neutral-900' }}">No Show ({{ $tabCounters['no_show'] }})</a>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-3 font-semibold">Reservation ID</th>
                                <th class="py-3 px-3 font-semibold">Guest Name</th>
                                <th class="py-3 px-3 font-semibold">Check-In</th>
                                <th class="py-3 px-3 font-semibold">Check-Out</th>
                                <th class="py-3 px-3 font-semibold">Room Alloc.</th>
                                <th class="py-3 px-3 font-semibold text-center">Nights</th>
                                <th class="py-3 px-3 font-semibold">Status</th>
                                <th class="py-3 px-3 font-semibold text-right">Total Room Bill</th>
                                <th class="py-3 px-3 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($bookingsList as $resv)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">#RES-OA-{{ $resv->id }}</td>
                                    <td class="py-3.5 px-3">
                                        <span class="font-bold text-neutral-900 block">{{ $resv->guest_name }}</span>
                                        <span class="text-[9px] text-amber-700 font-mono font-bold block mt-0.5">
                                            {{ $resv->guest_record_id ? '#GST-'.str_pad($resv->guest_record_id, 5, '0', STR_PAD_LEFT) : 'Guest ID pending' }}
                                            &bull; {{ $resv->identity_number ?: 'Identity pending' }}
                                        </span>
                                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">{{ $resv->guest_phone ?? 'No Contact Record' }}</span>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-neutral-700">
                                        {{ \Carbon\Carbon::parse($resv->check_in)->format('d M Y') }}
                                        <span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">{{ \Carbon\Carbon::parse($resv->check_in)->format('D') }}</span>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-neutral-700">
                                        {{ \Carbon\Carbon::parse($resv->check_out)->format('d M Y') }}
                                        <span class="block text-[9px] text-neutral-400 font-sans font-normal mt-0.5">{{ \Carbon\Carbon::parse($resv->check_out)->format('D') }}</span>
                                    </td>
                                    <td class="py-3.5 px-3">
                                        <span class="text-neutral-900 font-mono font-bold block">No. {{ $resv->room_number }}</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ $resv->room_type }}</span>
                                    </td>
                                    <td class="py-3.5 px-3 font-mono text-center">
                                        {{ \Carbon\Carbon::parse($resv->check_in)->diffInDays(\Carbon\Carbon::parse($resv->check_out)) }}
                                    </td>
                                    <td class="py-3.5 px-3">
                                        @if($resv->status == 'confirmed')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Confirmed</span>
                                        @elseif($resv->status == 'checked_in')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Checked In</span>
                                        @elseif($resv->status == 'pending')
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Tentative</span>
                                        @else
                                            <span class="bg-neutral-50 text-neutral-500 border border-neutral-200 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">{{ $resv->status }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-3 font-mono font-bold text-neutral-900 text-right">Rp {{ number_format($resv->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-1">
                                            @if(auth()->user()->role !== 'manager')
                                                <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-900 bg-white text-neutral-500 hover:text-neutral-900 flex items-center justify-center cursor-pointer"><i class="fa-solid fa-pen text-[10px]"></i></button>
                                            @else
                                                <button class="w-6 h-6 bg-neutral-50 border border-neutral-200 text-neutral-400 flex items-center justify-center cursor-not-allowed" disabled><i class="fa-solid fa-lock text-[10px]"></i></button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-neutral-400">No real reservations found matching the current filters.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing entries {{ $bookingsList->firstItem() ?? 0 }} to {{ $bookingsList->lastItem() ?? 0 }} of {{ $bookingsList->total() }}</span>
                    <div class="font-mono text-neutral-800">
                        {{ $bookingsList->links() }}
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservation Pipeline Overview</h3>
                    <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-wider text-neutral-400">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-blue-500 inline-block"></span> Arrivals Curve</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Departures Curve</span>
                    </div>
                </div>
                
                <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 120" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                        <path d="{{ $svgArrivalsPath }}" fill="none" stroke="#3b82f6" stroke-width="2" />
                        <path d="{{ $svgDeparturesPath }}" fill="none" stroke="#10b981" stroke-width="2" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                        <span>{{ now()->subDays(6)->format('d M') }}</span>
                        <span>{{ now()->subDays(5)->format('d M') }}</span>
                        <span>{{ now()->subDays(4)->format('d M') }}</span>
                        <span>{{ now()->subDays(3)->format('d M') }}</span>
                        <span>{{ now()->subDays(2)->format('d M') }}</span>
                        <span>{{ now()->subDays(1)->format('d M') }}</span>
                        <span>{{ now()->format('d M') }} (Today)</span>
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <h3 class="font-serif text-sm text-neutral-900 font-bold border-b pb-2">Availability Check</h3>
                
                <div id="quick-check-form" class="space-y-3 text-xs">
                    @csrf
                    <div>
                        <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Suite Category / Room Type</label>
                        <select id="qc_room_type" class="w-full border p-2 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 text-neutral-700 font-medium">
                            <option value="Deluxe Room">Deluxe Room</option>
                            <option value="Deluxe Ocean View">Deluxe Ocean View</option>
                            <option value="Superior Room">Superior Room</option>
                            <option value="Premier Suite">Premier Suite</option>
                            <option value="Executive Suite">Executive Suite</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Check-In Date</label>
                            <input type="date" id="qc_check_in" value="{{ date('Y-m-d') }}" class="w-full border p-1.5 font-mono text-[11px] focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-[9px] uppercase tracking-wider font-bold text-neutral-400 mb-1">Check-Out Date</label>
                            <input type="date" id="qc_check_out" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border p-1.5 font-mono text-[11px] focus:outline-none">
                        </div>
                    </div>
                    <button type="button" onclick="runLiveAvailabilityCheck()" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold py-2 uppercase tracking-wide text-[10px] rounded-none transition-colors cursor-pointer">
                        Run Live Check
                    </button>
                </div>

                <div id="qc_result_box" class="hidden p-3 text-[11px] font-medium border transition-all">
                    <span id="qc_status_title" class="font-bold block uppercase tracking-wide text-[9px]"></span>
                    <p id="qc_message" class="mt-1 text-neutral-600 leading-normal"></p>
                    <p id="qc_details" class="mt-1 font-mono text-[10px] text-neutral-400 font-bold"></p>
                </div>
            </div>

            <script>
                function runLiveAvailabilityCheck() {
                    const roomType = document.getElementById('qc_room_type').value;
                    const checkIn = document.getElementById('qc_check_in').value;
                    const checkOut = document.getElementById('qc_check_out').value;
                    const resultBox = document.getElementById('qc_result_box');
                    const statusTitle = document.getElementById('qc_status_title');
                    const messageEl = document.getElementById('qc_message');
                    const detailsEl = document.getElementById('qc_details');

                    // Reset State Tampilan
                    resultBox.className = "hidden";
                    
                    fetch("{{ route('receptionist.quick_check') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            room_type: roomType,
                            check_in: checkIn,
                            check_out: checkOut
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        resultBox.classList.remove('hidden');
                        if (data.success) {
                            if (data.available) {
                                resultBox.className = "p-3 text-[11px] font-medium border bg-emerald-50/60 border-emerald-200 text-emerald-900";
                                statusTitle.innerText = "● Kamar Tersedia";
                                messageEl.innerText = data.message;
                                detailsEl.innerText = data.details;
                            } else {
                                resultBox.className = "p-3 text-[11px] font-medium border bg-rose-50/60 border-rose-200 text-rose-900";
                                statusTitle.innerText = "● Fully Booked";
                                messageEl.innerText = data.message;
                                detailsEl.innerText = "";
                            }
                        } else {
                            resultBox.className = "p-3 text-[11px] font-medium border bg-amber-50/60 border-amber-200 text-amber-900";
                            statusTitle.innerText = "● Error Validasi";
                            messageEl.innerText = data.message || "Terjadi kesalahan sistem.";
                            detailsEl.innerText = "";
                        }
                    })
                    .catch(error => {
                        resultBox.classList.remove('hidden');
                        resultBox.className = "p-3 text-[11px] font-medium border bg-neutral-50 border-neutral-200 text-neutral-800";
                        statusTitle.innerText = "● Network Error";
                        messageEl.innerText = "Gagal menghubungi server.";
                        detailsEl.innerText = "";
                    });
                }
            </script>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Reservation Source</h3>
                    <span class="text-[8px] text-neutral-400 font-bold font-mono">Live Ledger</span>
                </div>
                <div class="flex items-center gap-4">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="45 55" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="35 65" stroke-dashoffset="-45"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#d97706" stroke-width="4.5" stroke-dasharray="20 80" stroke-dashoffset="-80"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-sm font-bold font-mono text-neutral-900 block leading-none">{{ $totalReservations }}</span>
                            <span class="text-[8px] text-neutral-400 uppercase font-bold mt-0.5 block">Total</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-bold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1"></span>Website Portal</span><span class="text-neutral-900 font-mono">45%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1"></span>Walk-In Desk</span><span class="text-neutral-900 font-mono">35%</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1"></span>Connected OTA</span><span class="text-neutral-900 font-mono">20%</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Upcoming Arrivals</h3>
                </div>
                <div class="space-y-3 text-xs font-semibold">
                    @forelse($upcomingArrivals as $arrival)
                        <div class="flex justify-between items-start border-b border-neutral-50 pb-2">
                            <div class="flex gap-2.5">
                                <div class="flex gap-2.5">
    <img src="https://ui-avatars.com/api/?name={{ urlencode($arrival->guest_name) }}&background=f4f4f5&color=18181b" class="w-6 h-6 object-cover border rounded-sm">
    <div>
        <span class="text-neutral-900 block truncate max-w-[130px]">{{ $arrival->guest_name }}</span>
        <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">Room {{ $arrival->room_number }} • {{ $arrival->room_type }}</span>
    </div>
</div>
                                <div>
                                    <span class="text-neutral-900 block truncate max-w-[130px]">{{ $arrival->guest_name }}</span>
                                    <span class="text-[9px] text-neutral-400 font-normal block mt-0.5">Room {{ $arrival->room_number }} • {{ $arrival->room_type }}</span>
                                </div>
                            </div>
                            <span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 uppercase tracking-wide">Active</span>
                        </div>
                    @empty
                        <div class="text-center py-2 text-neutral-400 font-normal">No upcoming pipeline arrivals found.</div>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>
