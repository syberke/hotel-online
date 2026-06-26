<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start">
        
        <div class="flex-1 w-full space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-regular fa-calendar text-amber-700"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Total Resv</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['total_resv'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-regular fa-circle-check text-emerald-600"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Confirmed</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['confirmed'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-regular fa-clock text-amber-500"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Pending</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['pending'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-solid fa-plane-arrival text-blue-600"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Arrivals (Today)</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['arrivals'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-solid fa-plane-departure text-neutral-500"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Departures (Today)</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['departures'] }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET" class="bg-white border border-neutral-200 p-4 shadow-sm flex flex-wrap items-center gap-4">
                <div class="relative flex-1 min-w-[240px]">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by booking ID, guest name, email..." class="w-full pl-10 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                </div>
                
                <select name="status" onchange="this.form.submit()" class="border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-xs font-medium focus:outline-none focus:border-neutral-900 text-neutral-700">
                    <option value="All Status">All Status</option>
                    @foreach(['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>

                <select name="room_type" onchange="this.form.submit()" class="border border-neutral-200 bg-neutral-50/50 px-3 py-2 text-xs font-medium focus:outline-none focus:border-neutral-900 text-neutral-700">
                    <option value="All Room Types">All Room Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->name }}" {{ request('room_type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>

                <div class="border border-neutral-200 bg-neutral-50/50 px-3 py-1.5 text-xs font-medium text-neutral-600 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-neutral-400"></i>
                    <input type="text" name="date_range" value="{{ request('date_range') }}" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="bg-transparent focus:outline-none text-xs w-44 placeholder-neutral-400">
                </div>

                <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">Apply Filters</button>
                @if(request()->anyFilled(['search', 'status', 'room_type', 'date_range']))
                    <a href="{{ url()->current() }}" class="border border-neutral-200 text-neutral-500 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase transition-colors">Reset</a>
                @endif
            </form>

            <div class="bg-white border border-neutral-200 shadow-sm overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                            <th class="py-4 px-6 font-semibold">Booking ID</th>
                            <th class="py-4 px-4 font-semibold">Guest</th>
                            <th class="py-4 px-4 font-semibold">Room Type & Number</th>
                            <th class="py-4 px-4 font-semibold">Check-In</th>
                            <th class="py-4 px-4 font-semibold">Check-Out</th>
                            <th class="py-4 px-4 font-semibold">Status</th>
                            <th class="py-4 px-4 font-semibold">Payment</th>
                            <th class="py-4 px-4 font-semibold">Total</th>
                            <th class="py-4 px-6 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @forelse($bookings as $booking)
                            @php
                                // Cek data pembayaran terakhir yang terkait booking ini
                                $latestPayment = $booking->payments->last();
                                $paymentStatus = $latestPayment ? $latestPayment->payment_status : 'pending';
                                $paymentMethod = $latestPayment ? $latestPayment->payment_method : '-';
                                
                                // Deteksi baris aktif/terpilih untuk efek sorot border
                                $isSelected = $selectedBooking && $selectedBooking->id == $booking->id;
                            @endphp
                            <tr onclick="window.location.href='{{ request()->fullUrlWithQuery(['selected_id' => $booking->id]) }}'" 
                                class="hover:bg-neutral-50/40 transition-colors cursor-pointer {{ $isSelected ? 'bg-amber-50/20 border-l-2 border-amber-600' : '' }}">
                                
                                <td class="py-4 px-6">
                                    <span class="font-bold text-neutral-900 block">#{{ $booking->id }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">System Reservation</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="font-bold text-neutral-900 block">{{ $booking->user->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">{{ $booking->user->email ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-neutral-800 block">{{ $booking->room->roomType->name ?? 'Unassigned' }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Room {{ $booking->room->room_number ?? '-' }} &bull; Max {{ $booking->room->roomType->max_capacity ?? 2 }} Pax</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">{{ \Carbon\Carbon::parse($booking->check_in)->format('H:i A') }}</span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">12:00 PM</span>
                                </td>
                                <td class="py-4 px-4">
                                    @if($booking->status == 'confirmed')
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Confirmed</span>
                                    @elseif($booking->status == 'pending')
                                        <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Pending</span>
                                    @elseif($booking->status == 'checked_in')
                                        <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Checked In</span>
                                    @else
                                        <span class="bg-neutral-100 text-neutral-800 border border-neutral-200 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">{{ str_replace('_', ' ', $booking->status) }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    @if($paymentStatus == 'paid')
                                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.2 font-bold uppercase">Paid</span>
                                    @elseif($paymentStatus == 'failed')
                                        <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-2 py-0.2 font-bold uppercase">Failed</span>
                                    @else
                                        <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-2 py-0.2 font-bold uppercase">Unpaid</span>
                                    @endif
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5 uppercase">{{ str_replace('_', ' ', $paymentMethod) }}</span>
                                </td>
                                <td class="py-4 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="py-4 px-6 text-center" onclick="event.stopPropagation();">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="View Only Modality Controlled"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-neutral-400">No matching reservations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-xs text-neutral-400 font-medium pt-2">
                <span>Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} results</span>
                
                <div class="flex items-center gap-1.5 font-mono text-neutral-800">
                    {{ $bookings->links('pagination::tailwind') }}
                </div>

                <div class="flex items-center gap-2 text-neutral-500">
                    <span>Rows per page:</span>
                    <form action="{{ url()->current() }}" method="GET" class="inline">
                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if(request('room_type')) <input type="hidden" name="room_type" value="{{ request('room_type') }}"> @endif
                        <select name="per_page" onchange="this.form.submit()" class="border border-neutral-200 bg-white px-2 py-1 focus:outline-none text-neutral-800 font-bold">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-96 bg-white border border-neutral-200 shadow-sm p-6 space-y-6 shrink-0 relative">
            @if($selectedBooking)
                @php
                    $asidePayment = $selectedBooking->payments->last();
                @endphp
                
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4">
                    <h3 class="font-serif text-base text-neutral-900 tracking-wide font-medium">Reservation Details</h3>
                    <a href="{{ request()->url() }}" class="text-neutral-400 hover:text-neutral-900 transition-colors cursor-pointer"><i class="fa-solid fa-xmark text-sm"></i></a>
                </div>

                <div class="flex justify-between items-center">
                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">{{ str_replace('_', ' ', $selectedBooking->status) }}</span>
                    <div class="text-right">
                        <span class="text-sm font-bold text-neutral-900 font-mono block">#{{ $selectedBooking->id }}</span>
                        <span class="text-[9px] text-neutral-400 block mt-0.5 font-semibold uppercase tracking-wider">System Operational</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between items-baseline">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Guest Information</h4>
                        @if(auth()->user()->role !== 'manager')
                            <button class="text-[9px] font-bold text-amber-800 uppercase hover:underline cursor-pointer">Edit</button>
                        @endif
                    </div>
                    <div class="flex items-center gap-4 p-3 bg-neutral-50/60 border border-neutral-100">
                        <img src="{{ $selectedBooking->user->foto_url ?? 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1200' }}" class="w-10 h-10 object-cover border border-neutral-200">
                        <div>
                            <span class="text-xs font-bold text-neutral-900 block flex items-center gap-2">
                                {{ $selectedBooking->user->name ?? 'N/A' }} 
                                @if(($selectedBooking->user->role ?? '') === 'vip')
                                    <span class="bg-amber-100 text-amber-900 border border-amber-200 font-mono font-bold text-[7px] px-1.5 py-0.2 tracking-normal uppercase scale-95">VIP Guest</span>
                                @endif
                            </span>
                            <span class="text-[10px] text-neutral-500 font-medium block mt-0.5">{{ $selectedBooking->user->email ?? 'N/A' }}</span>
                            <span class="text-[10px] text-neutral-400 font-mono block mt-0.5">{{ $selectedBooking->user->phone ?? '-' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-medium text-neutral-500 px-1">
                        <i class="fa-solid fa-location-dot text-neutral-400"></i>
                        <span>{{ $selectedBooking->user->address ?? 'No permanent address recorded' }}</span>
                    </div>
                </div>

                <div class="space-y-3 pt-2 border-t border-neutral-100">
                    <div class="flex justify-between items-baseline">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Reservation Information</h4>
                        @if(auth()->user()->role !== 'manager')
                            <button class="text-[9px] font-bold text-amber-800 uppercase hover:underline cursor-pointer">Edit</button>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs font-medium text-neutral-600 px-1">
                        <div><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Room Type</span><span class="text-neutral-900 font-bold">{{ $selectedBooking->room->roomType->name ?? '-' }}</span></div>
                        <div><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Room Number</span><span class="text-amber-800 font-mono font-bold">{{ $selectedBooking->room->room_number ?? 'TBD' }}</span></div>
                        <div><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Check-In</span><span class="text-neutral-900 font-bold">{{ \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y, H:i A') }}</span></div>
                        <div><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Check-Out</span><span class="text-neutral-900 font-bold">{{ \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y, 12:00 PM') }}</span></div>
                        <div>
                            <span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Nights</span>
                            <span class="text-neutral-900 font-bold">
                                {{ \Carbon\Carbon::parse($selectedBooking->check_in)->diffInDays(\Carbon\Carbon::parse($selectedBooking->check_out)) }} Nights
                            </span>
                        </div>
                        <div><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Guests</span><span class="text-neutral-900 font-bold">{{ $selectedBooking->guests_count }} Registered</span></div>
                        <div class="col-span-2"><span class="block text-[9px] text-neutral-400 uppercase tracking-wider mb-0.5">Rate Plan Integration</span><span class="text-neutral-900 font-bold">Standard Nightly Rate (Rp {{ number_format($selectedBooking->room->roomType->price ?? 0, 0, ',', '.') }}/night)</span></div>
                    </div>
                </div>

                <div class="space-y-3 pt-3 border-t border-neutral-100">
                    <div class="flex justify-between items-baseline">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Payment Information</h4>
                        @if(auth()->user()->role !== 'manager')
                            <button class="text-[9px] font-bold text-amber-800 uppercase hover:underline cursor-pointer">View</button>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 gap-y-2 text-xs font-medium text-neutral-600 px-1">
                        <div><span class="text-neutral-400">Payment Method</span></div>
                        <div class="text-right text-neutral-900 font-bold uppercase">{{ str_replace('_', ' ', $asidePayment->payment_method ?? 'None') }}</div>
                        
                        <div><span class="text-neutral-400">Payment Status</span></div>
                        <div class="text-right">
                            @if(($asidePayment->payment_status ?? '') == 'paid')
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.2 font-bold uppercase">Paid</span>
                            @else
                                <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-2 py-0.2 font-bold uppercase">Unpaid/Pending</span>
                            @endif
                        </div>
                        
                        <div><span class="text-neutral-400">Transaction Date</span></div>
                        <div class="text-right font-mono text-neutral-500">{{ $asidePayment ? \Carbon\Carbon::parse($asidePayment->created_at)->format('d M Y, H:i A') : '-' }}</div>
                        
                        <div class="col-span-2 border-t border-neutral-100 my-1"></div>
                        <div><span class="text-neutral-900 font-bold">Total Amount</span></div>
                        <div class="text-right font-mono text-amber-950 font-bold text-sm">Rp {{ number_format($selectedBooking->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="pt-4 border-t border-neutral-100 space-y-2.5">
                    @if(auth()->user()->role !== 'manager')
                        <button class="w-full bg-amber-800 hover:bg-amber-900 text-white font-bold text-[10px] uppercase tracking-widest py-3 transition-colors shadow-sm cursor-pointer"><i class="fa-regular fa-pen-to-square mr-1"></i> Edit Reservation</button>
                        
                        <form action="{{ route('bookings.cancel', $selectedBooking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                            @csrf
                            <button type="submit" class="w-full bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-bold text-[10px] uppercase tracking-widest py-3 transition-colors cursor-pointer">Cancel Reservation</button>
                        </form>
                        
                        <button class="w-full bg-white hover:bg-neutral-50 text-neutral-400 font-bold text-[10px] uppercase tracking-widest py-2.5 transition-all flex items-center justify-center gap-1.5 cursor-pointer"><i class="fa-regular fa-envelope text-xs"></i> Send Email to Guest</button>
                    @else
                        <button onclick="window.print()" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-3 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-print mr-1"></i> Print Registration Form</button>
                        <button class="w-full bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 font-bold text-[10px] uppercase tracking-widest py-3 transition-colors cursor-pointer"><i class="fa-regular fa-file-pdf mr-1"></i> Download Folio Invoice</button>
                        <div class="p-3 bg-neutral-50 border border-neutral-200 text-center select-none">
                            <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block"><i class="fa-solid fa-lock text-amber-700 mr-1"></i> Read-Only Audit Desk</span>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 text-neutral-400 text-xs">
                    <i class="fa-solid fa-folder-open block text-lg mb-2"></i> No active reservation data to view.
                </div>
            @endif
        </aside>

    </div>

</x-admin-dashboard-layout>