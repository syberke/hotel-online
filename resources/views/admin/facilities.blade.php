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
        
        <div class="flex-1 w-full space-y-8">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['total_bookings'] }}</span>
                        <span class="text-[9px] text-neutral-400 font-sans">Entries</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Today's Bookings</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['today_bookings'] }}</span>
                        <span class="text-[9px] text-emerald-600 font-bold font-mono">Live</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Facilities</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-600">{{ $stats['active_fac'] }} / {{ $stats['total_fac'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Est. Revenue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-xs font-bold text-neutral-900 font-mono">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Utilization Rate</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['utilization'] }}%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 pb-3">
                    <button id="btnTabLedger" onclick="switchMainTab('ledger')" class="text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5 font-bold cursor-pointer">Bookings Overview</button>
                    <button id="btnTabCalendar" onclick="switchMainTab('calendar')" class="hover:text-neutral-900 pb-1.5 px-0.5 transition-colors cursor-pointer">Facilities Calendar</button>
                </div>

                <div id="sectionTabLedger" class="space-y-5">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
                        <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-3">
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="px-2.5 py-1.5 {{ $currentTab === 'all' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">All ({{ $stats['total_bookings'] }})</a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'upcoming']) }}" class="px-2.5 py-1.5 {{ $currentTab === 'upcoming' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">Upcoming</a>
                            <a href="{{ request()->fullUrlWithQuery(['tab' => 'in_progress']) }}" class="px-2.5 py-1.5 {{ $currentTab === 'in_progress' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">In Progress Today</a>
                        </div>

                        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full lg:w-auto">
                            <input type="hidden" name="tab" value="{{ $currentTab }}">
                            <div class="relative flex-1 lg:flex-none lg:min-w-[220px]">
                                <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by booking ID, guest..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                            </div>
                            <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider cursor-pointer">Filter</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar pt-1">
                        <table class="w-full text-left text-xs whitespace-nowrap">
                            <thead>
                                <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                    <th class="py-3 px-4 font-semibold">Booking ID</th>
                                    <th class="py-3 px-4 font-semibold">Guest Name</th>
                                    <th class="py-3 px-4 font-semibold">Facility Allocation</th>
                                    <th class="py-3 px-4 font-semibold">Scheduled Time</th>
                                    <th class="py-3 px-4 font-semibold">Status</th>
                                    <th class="py-3 px-4 font-semibold">Pax</th>
                                    <th class="py-3 px-4 text-center font-semibold">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                                @forelse($bookings as $book)
                                    <tr class="hover:bg-neutral-50/40 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">#FW-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td class="py-3.5 px-4">
                                            <span class="font-bold text-neutral-900 block">{{ $book->guest_name }}</span>
                                            <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">{{ $book->guest_phone ?? 'N/A' }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 font-bold text-neutral-900">{{ $book->facility_name }}</td>
                                        <td class="py-3.5 px-4 text-neutral-700 font-mono">
                                            {{ date('d M Y', strtotime($book->booking_date)) }}
                                            <span class="block text-[9px] text-neutral-400 font-normal mt-0.5">{{ date('h:i A', strtotime($book->booking_time)) }}</span>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">{{ $book->status }}</span>
                                        </td>
                                        <td class="py-3.5 px-4 font-mono text-neutral-900 font-bold">{{ $book->guests_count }} Guests</td>
                                        <td class="py-3.5 px-4 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button" onclick="viewBookingDetail({{ $book->id }})" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-amber-800 rounded-xs flex items-center justify-center cursor-pointer shadow-xs" title="Lihat detail reservasi fasilitas">
                                                    <i class="fa-solid fa-eye text-xs"></i>
                                                </button>
                                            @if(auth()->user()->role !== 'manager')
                                                <button type="button" onclick="openManageBookingModal({{ $book->id }})" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-600 rounded-xs mx-auto flex items-center justify-center cursor-pointer shadow-xs" title="Manage Status Pipeline">
                                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                                </button>
                                                @if($book->status === 'cancelled')
                                                    <form action="{{ route('admin.facilities.booking.delete', $book->id) }}" method="POST" class="inline-flex" data-confirm="Hapus reservasi fasilitas batal #FW-{{ str_pad($book->id, 4, '0', STR_PAD_LEFT) }}?" data-confirm-title="Hapus Reservasi Batal">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-rose-50 text-rose-600 rounded-xs flex items-center justify-center cursor-pointer shadow-xs" title="Hapus reservasi batal">
                                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-12 text-center text-neutral-400 italic">No facility booking transactions stored.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="sectionTabCalendar" class="hidden space-y-4">
                    <div class="bg-neutral-50 p-4 border border-neutral-100 text-[11px] text-neutral-500 font-medium flex items-center justify-between">
                        <span><i class="fa-regular fa-calendar-days mr-1 text-amber-700"></i> Active Hourly Sessions Slotted For Today ({{ date('d M Y') }})</span>
                        <span class="bg-white border text-[9px] px-2 py-0.5 font-mono">Real-time Matrix Feed</span>
                    </div>
                    <div class="space-y-2.5">
                        @foreach($bookings->where('booking_date', date('Y-m-d')) as $session)
                            <div class="bg-white border-l-4 border-amber-600 p-3 shadow-xs flex justify-between items-center hover:bg-neutral-50/50 transition-all">
                                <div class="space-y-0.5">
                                    <span class="font-mono text-xs font-bold text-neutral-900 bg-neutral-100 px-1.5 py-0.5">{{ date('H:i', strtotime($session->booking_time)) }}</span>
                                    <span class="font-bold text-neutral-800 ml-2">{{ $session->facility_name }}</span>
                                    <span class="text-neutral-400 block text-[10px] mt-1">Guest Master Assignment: <strong>{{ $session->guest_name }}</strong> ({{ $session->guests_count }} Pax)</span>
                                </div>
                                <span class="text-[9px] bg-emerald-50 border border-emerald-100 text-emerald-800 px-2 py-0.5 font-bold uppercase font-sans tracking-wider">Slotted Active</span>
                            </div>
                        @endforeach
                        @if($bookings->where('booking_date', date('Y-m-d'))->count() === 0)
                            <div class="text-center py-8 text-neutral-400 italic text-xs">No reservation time slots active for today.</div>
                        @endif
                    </div>
                </div>

            </div>

            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Facilities Physical Status & Inventory</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    @foreach($facilitiesGrid as $fac)
                        <div class="bg-white border border-neutral-200 shadow-sm overflow-hidden flex flex-col justify-between group relative">
                            <img src="{{ $fac->image_url ?? 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?q=80&w=400' }}" class="h-24 w-full object-cover border-b">
                            
                            <div class="p-3.5 space-y-2">
                                <span class="text-xs font-bold text-neutral-900 block truncate">{{ $fac->name }}</span>
                                <div class="flex justify-between items-center text-[9px] font-bold uppercase tracking-wider">
                                    <span class="text-emerald-600"><i class="fa-solid fa-circle text-[5px] mr-1"></i> {{ $fac->hours ?? 'Active' }}</span>
                                    <span class="text-neutral-400 font-mono">Util: {{ $fac->computed_util }}%</span>
                                </div>
                                <div class="w-full h-1 bg-neutral-100">
                                    <div class="h-full bg-amber-700" style="width: {{ $fac->computed_util }}%"></div>
                                </div>

                                @if(auth()->user()->role !== 'manager')
                                    <div class="pt-2 border-t border-neutral-100 flex justify-between text-[10px] font-bold uppercase tracking-wider mt-1">
                                        <button type="button" onclick="openEditFacilityModal({{ json_encode($fac) }})" class="text-blue-600 hover:text-blue-800 cursor-pointer"><i class="fa-regular fa-edit"></i> Edit</button>
                                        <form action="{{ route('admin.facilities.delete', $fac->id) }}" method="POST" data-confirm="Hapus area fasilitas {{ $fac->name }}? Data yang sudah dipakai reservasi akan tetap dilindungi sistem." data-confirm-title="Hapus Fasilitas">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 cursor-pointer"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    
                    @if(auth()->user()->role !== 'manager')
                        <button type="button" onclick="openCreateFacilityModal()" class="border border-dashed border-neutral-300 shadow-none p-6 flex flex-col items-center justify-center text-center gap-2 group cursor-pointer hover:border-neutral-900 transition-all bg-neutral-50/20">
                            <div class="text-neutral-400 group-hover:text-neutral-900 text-md"><i class="fa-solid fa-plus"></i></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 group-hover:text-neutral-900">Add Facility</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Today's Session Feed</h3>
                </div>
                <div class="space-y-3.5 text-xs font-medium text-neutral-600">
                    <div class="flex justify-between"><span>Upcoming Queue</span><span class="font-mono text-neutral-900 font-bold">{{ $asideStats['upcoming'] }}</span></div>
                    <div class="flex justify-between"><span>Active Internal Sessions</span><span class="font-mono text-neutral-900 font-bold">{{ $asideStats['in_house'] }}</span></div>
                    <div class="flex justify-between"><span>Completed Ledger Count</span><span class="font-mono text-neutral-900 font-bold">{{ $asideStats['completed'] }}</span></div>
                    <div class="flex justify-between"><span>Voided/Cancelled</span><span class="font-mono text-neutral-900 font-bold">{{ $asideStats['cancelled'] }}</span></div>
                    <div class="border-t border-neutral-100 my-1"></div>
                    <div class="flex justify-between text-neutral-900 font-bold pt-1"><span>Est. Venue Revenue</span><span class="font-mono text-emerald-700">Rp {{ number_format($asideStats['revenue'], 0, ',', '.') }}</span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Utilization Metric</h3>
                </div>
                <div class="flex items-center gap-4 my-2">
                    <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e5e7eb" stroke-width="3.5"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-sm font-light font-serif text-neutral-900 block leading-none">{{ $stats['utilization'] }}%</span>
                            <span class="text-[7px] text-neutral-400 uppercase tracking-wider font-bold mt-0.5 block">Overall</span>
                        </div>
                    </div>
                    <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                        @foreach($chartShares as $name => $percentage)
                            <div class="flex justify-between items-center">
                                <span class="truncate max-w-[110px]"><span class="w-1.5 h-1.5 bg-neutral-900 inline-block mr-1.5"></span>{{ $name }}</span>
                                <span class="text-neutral-800 font-mono font-bold">{{ $percentage }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>

    </div>

    <div id="modalBookingDetail" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Appointment Audit Log</h4>
                <button type="button" onclick="closeDetailBookingModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="space-y-3 text-xs font-medium text-neutral-600">
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Guest Name:</span><span id="det_guest_name" class="text-neutral-900 font-bold"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Contact Info:</span><span id="det_guest_phone" class="text-neutral-900 font-mono"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Target Area:</span><span id="det_facility_name" class="text-neutral-900 font-bold"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Time Window:</span><span id="det_booking_time" class="text-neutral-900 font-mono"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Notes / Preferences:</span><span id="det_notes" class="text-neutral-400 italic"></span></div>
            </div>
        </div>
    </div>

    <div id="modalManageBooking" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Manage Status Pipeline</h4>
                <button type="button" onclick="closeManageBookingModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formUpdateBookingStatus" action="" method="POST" class="space-y-4" data-confirm="Perbarui status reservasi fasilitas ini?" data-confirm-title="Perbarui Status Fasilitas">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Operational Status</label>
                    <select name="status" id="manage_status_select" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                        <option value="confirmed">Confirmed / Scheduled</option>
                        <option value="completed">Completed / Settled</option>
                        <option value="cancelled">Void / Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Update Status Matrix</button>
            </form>
        </div>
    </div>

    <div id="modalCreateFacility" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Register New Resort Facility</h4>
                <button type="button" onclick="closeCreateFacilityModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.facilities.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Facility Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Category Group</label>
                    <select name="category" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                        <option value="Wellness">Wellness & Spa</option>
                        <option value="Sports & Fitness">Sports & Fitness</option>
                        <option value="Pools & Beach">Pools & Beach</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Operational Hours</label>
                    <input type="text" name="hours" placeholder="e.g. 07:00 AM - 09:00 PM" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Display Image URL Address</label>
                    <input type="url" name="image_url" placeholder="https://..." class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Publish Area Inventory</button>
            </form>
        </div>
    </div>

    <div id="modalEditFacility" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Modify Facility Specifications</h4>
                <button type="button" onclick="closeEditFacilityModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formEditFacility" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Facility Name</label>
                    <input type="text" name="name" id="edit_fac_name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Category Group</label>
                    <select name="category" id="edit_fac_category" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                        <option value="Wellness">Wellness</option>
                        <option value="Sports & Fitness">Sports & Fitness</option>
                        <option value="Pools & Beach">Pools & Beach</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Operational Hours</label>
                    <input type="text" name="hours" id="edit_fac_hours" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Display Image URL Address</label>
                    <input type="url" name="image_url" id="edit_fac_image_url" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Update Area Metadata</button>
            </form>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    // Logika Alur Penggantian Sub-Tab (Bookings Overview vs Calendar)
    function switchMainTab(targetClass) {
        const ledgerSec = document.getElementById('sectionTabLedger');
        const calendarSec = document.getElementById('sectionTabCalendar');
        const btnLedger = document.getElementById('btnTabLedger');
        const btnCalendar = document.getElementById('btnTabCalendar');

        if(targetClass === 'ledger') {
            ledgerSec.classList.remove('hidden');
            calendarSec.classList.add('hidden');
            btnLedger.className = "text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5 font-bold cursor-pointer";
            btnCalendar.className = "hover:text-neutral-900 pb-1.5 px-0.5 transition-colors cursor-pointer";
        } else {
            ledgerSec.classList.add('hidden');
            calendarSec.classList.remove('hidden');
            btnCalendar.className = "text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5 font-bold cursor-pointer";
            btnLedger.className = "hover:text-neutral-900 pb-1.5 px-0.5 transition-colors cursor-pointer";
        }
    }

    // Modal AJAX View Details (Manager Mode)
    function viewBookingDetail(id) {
        fetch(`/admin/facilities/booking/${id}/detail`)
            .then(response => {
                if (!response.ok) throw new Error('Detail reservasi fasilitas tidak tersedia.');
                return response.json();
            })
            .then(res => {
                if(res.success) {
                    document.getElementById('det_guest_name').innerText = res.data.guest_name;
                    document.getElementById('det_guest_phone').innerText = res.data.guest_phone ?? 'N/A';
                    document.getElementById('det_facility_name').innerText = res.data.facility_name;
                    document.getElementById('det_booking_time').innerText = `${res.data.booking_date} @ ${res.data.booking_time}`;
                    document.getElementById('det_notes').innerText = res.data.notes ?? 'No preferences left.';
                    document.getElementById('modalBookingDetail').classList.remove('hidden');
                } else {
                    throw new Error(res.message || 'Gagal mengambil detail reservasi.');
                }
            })
            .catch(error => OasisDialog.error(error.message));
    }

    // Modal Update Status Pipeline (Admin Mode)
    function openManageBookingModal(id) {
        fetch(`/admin/facilities/booking/${id}/detail`)
            .then(response => {
                if (!response.ok) throw new Error('Data status fasilitas tidak tersedia.');
                return response.json();
            })
            .then(res => {
                if(res.success) {
                    document.getElementById('manage_status_select').value = res.data.status;
                    document.getElementById('formUpdateBookingStatus').action = `/admin/facilities/booking/${id}/update-status`;
                    document.getElementById('modalManageBooking').classList.remove('hidden');
                } else {
                    throw new Error(res.message || 'Status fasilitas tidak dapat dibuka.');
                }
            })
            .catch(error => OasisDialog.error(error.message));
    }

    function closeDetailBookingModal() { document.getElementById('modalBookingDetail').classList.add('hidden'); }
    function closeManageBookingModal() { document.getElementById('modalManageBooking').classList.add('hidden'); }

    // Modal Kontrol Inventory CRUD Fisik Fasilitas
    function openCreateFacilityModal() { document.getElementById('modalCreateFacility').classList.remove('hidden'); }
    function closeCreateFacilityModal() { document.getElementById('modalCreateFacility').classList.add('hidden'); }
    
    function openEditFacilityModal(fac) {
        document.getElementById('edit_fac_name').value = fac.name;
        document.getElementById('edit_fac_category').value = fac.category ?? 'Wellness';
        document.getElementById('edit_fac_hours').value = fac.hours ?? '';
        document.getElementById('edit_fac_image_url').value = fac.image_url ?? '';
        document.getElementById('formEditFacility').action = `/admin/facilities/${fac.id}/update`;
        document.getElementById('modalEditFacility').classList.remove('hidden');
    }
    function closeEditFacilityModal() { document.getElementById('modalEditFacility').classList.add('hidden'); }
</script>
