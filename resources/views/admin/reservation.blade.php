<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f5f5f3; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d4d4d4; }
    [x-cloak] { display: none !important; }

    /* Pengaturan Baris Reservasi Terpilih yang Minimalis & Tenang */
    .selected-neon-row {
        background-color: #f5f5f5 !important; /* bg-neutral-100 lembut */
        border-left: 3px solid #737373 !important; /* Aksen batas abu-abu netral */
    }

    /* Pengaturan Kertas Cetak Kwitansi */
    @media print {
        @page {
            size: auto;
            margin: 0mm;
        }
        body * {
            visibility: hidden;
        }
        #print-aside-target, #print-aside-target * {
            visibility: visible;
        }
        #print-aside-target {
            position: fixed;
            inset: 0;
            display: flex !important;
            align-items: center;
            justify-content: center;
            background: #ffffff !important;
            width: 100vw;
            height: 100vh;
            margin: 0 !important;
            padding: 0 !important;
        }
        .aside-action-buttons, .fa-xmark, form {
            display: none !important;
        }
        .aside-main-container {
            box-shadow: none !important;
            border: 1px solid #e5e5e5 !important;
            padding: 40px !important;
            width: 85% !important;
            max-width: 650px !important;
            margin: 0 auto !important;
            background-color: #ffffff !important;
        }
        .bg-neutral-50, .bg-neutral-50\/60 {
            background-color: #f9f9f9 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

<x-admin-dashboard-layout>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-3 text-xs font-semibold mb-4 flex items-center">
            <i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-800 p-3 text-xs font-semibold mb-4 flex items-center">
            <i class="fa-solid fa-circle-exclamation mr-2 text-rose-600"></i> {{ session('error') }}
        </div>
    @endif

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
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Arrivals</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['arrivals'] }}</span>
                    </div>
                </div>
                <div class="bg-white p-4 border border-neutral-200/60 flex items-center gap-3 shadow-sm">
                    <div class="w-9 h-9 bg-neutral-50 border border-neutral-100 flex items-center justify-center text-neutral-400 text-sm"><i class="fa-solid fa-plane-departure text-neutral-500"></i></div>
                    <div>
                        <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wide block">Departures</span>
                        <span class="text-lg font-light font-serif text-neutral-900 block">{{ $stats['departures'] }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ url()->current() }}" method="GET" id="filter-matrix-form" class="bg-white border border-neutral-200 p-4 shadow-sm flex flex-wrap items-center gap-4">
                <input type="hidden" name="per_page" value="{{ request('per_page', 10) }}">
                <div class="relative flex-1 min-w-[240px]">
                    <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by booking ID, guest name, email..." class="w-full pl-10 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                </div>
                
                <select name="status" onchange="this.form.submit()" class="border border-neutral-200 bg-white px-3 py-2 text-xs font-medium focus:outline-none focus:border-neutral-900 text-neutral-700">
                    <option value="All Status">All Status</option>
                    @foreach(['Pending', 'Confirmed', 'Checked In', 'Checked Out', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>

                <select name="room_type" onchange="this.form.submit()" class="border border-neutral-200 bg-white px-3 py-2 text-xs font-medium focus:outline-none focus:border-neutral-900 text-neutral-700">
                    <option value="All Room Types">All Room Types</option>
                    @foreach($roomTypes as $type)
                        <option value="{{ $type->name }}" {{ request('room_type') == $type->name ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>

                <div class="border border-neutral-200 bg-white px-3 py-1.5 text-xs font-medium text-neutral-600 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-neutral-400"></i>
                    <input type="text" name="date_range" value="{{ request('date_range') }}" placeholder="YYYY-MM-DD - YYYY-MM-DD" class="bg-transparent focus:outline-none text-xs w-44 placeholder-neutral-400">
                </div>

                <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors cursor-pointer">Apply Filters</button>
                @if(request()->anyFilled(['search', 'status', 'room_type', 'date_range']))
                    <a href="{{ route('admin.reservation') }}" class="border border-neutral-200 text-neutral-500 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase transition-colors">Reset</a>
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
                                $latestPayment = $booking->payments->last();
                                $paymentStatus = $latestPayment ? $latestPayment->payment_status : 'pending';
                                $paymentMethod = $latestPayment ? $latestPayment->payment_method : 'transfer';
                                $isSelected = $selectedBooking && $selectedBooking->id == $booking->id;
                                
                                if($booking->status == 'confirmed' || $booking->status == 'checked_in' || $booking->status == 'checked_out') {
                                    $paymentStatus = 'paid';
                                }
                                if($booking->status == 'cancelled') {
                                    $paymentStatus = 'failed';
                                }
                            @endphp
                            <tr onclick="window.location.href='{{ request()->fullUrlWithQuery(['selected_id' => $booking->id]) }}'" 
                                class="hover:bg-neutral-50/40 transition-colors cursor-pointer {{ $isSelected ? 'selected-neon-row' : '' }}">
                                
                                <td class="py-4 px-6 align-middle">
                                    <span class="font-bold text-neutral-900 block">#OA-{{ str_pad($booking->id, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">System Reservation</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="font-bold text-neutral-900 block">{{ $booking->user->name ?? 'N/A' }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">{{ $booking->user->email ?? 'N/A' }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-neutral-800 block font-semibold">{{ $booking->room->roomType->name ?? 'Unassigned' }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Room {{ $booking->room->room_number ?? '-' }} &bull; Max {{ $booking->room->roomType->max_capacity ?? 2 }} Pax</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($booking->check_in)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">02:00 PM</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <span class="text-neutral-800 block">{{ \Carbon\Carbon::parse($booking->check_out)->format('d M Y') }}</span>
                                    <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">12:00 PM</span>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div class="inline-block leading-none">
                                        @if($booking->status == 'confirmed')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide rounded-xs">Confirmed</span>
                                        @elseif($booking->status == 'pending')
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide rounded-xs">Pending</span>
                                        @elseif($booking->status == 'checked_in')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide rounded-xs">Checked In</span>
                                        @elseif($booking->status == 'checked_out')
                                            <span class="bg-neutral-100 text-neutral-700 border border-neutral-200 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide rounded-xs">Checked Out</span>
                                        @else
                                            <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide rounded-xs">Cancelled</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 align-middle">
                                    <div class="inline-block leading-none">
                                        @if($paymentStatus == 'paid')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase rounded-xs">Paid</span>
                                        @elseif($paymentStatus == 'failed')
                                            <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-2 py-0.5 font-bold uppercase rounded-xs">Failed</span>
                                        @else
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase rounded-xs">Unpaid</span>
                                        @endif
                                    </div>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-1 uppercase font-mono">{{ str_replace('_', ' ', $paymentMethod) }}</span>
                                </td>
                                <td class="py-4 px-4 align-middle font-mono font-bold text-neutral-900">
                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-4 px-6 align-middle text-center" onclick="event.stopPropagation();">
                                    <div class="flex items-center justify-center gap-1">
                                        <button onclick="openGlobalViewModal({{ $booking->id }})" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-600 transition-colors cursor-pointer flex items-center justify-center" title="Lihat Detail"><i class="fa-solid fa-eye text-xs"></i></button>
                                        
                                        @if(auth()->user()->role !== 'manager')
                                            <button onclick="openEditStatusModal({{ $booking->id }}, '{{ $booking->status }}')" class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-900 text-amber-800 transition-colors cursor-pointer flex items-center justify-center" title="Edit Status"><i class="fa-solid fa-pen text-xs"></i></button>
                                            <form action="{{ route('admin.reservations.delete', $booking->id) }}" method="POST" class="inline m-0 p-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data manifes ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-7 h-7 bg-white border border-neutral-200 hover:border-rose-600 text-rose-600 transition-colors cursor-pointer flex items-center justify-center" title="Hapus Permanen"><i class="fa-solid fa-trash text-xs"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-neutral-400 italic font-sans">No matching reservations found in our ledger core.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 text-xs text-neutral-400 font-medium pt-2">
                <span>Showing {{ $bookings->firstItem() ?? 0 }} to {{ $bookings->lastItem() ?? 0 }} of {{ $bookings->total() }} results</span>
                
                <div class="flex items-center gap-1.5 font-mono text-neutral-800">
                    {{ $bookings->links() }}
                </div>

                <div class="flex items-center gap-2 text-neutral-500">
                    <span>Rows per page:</span>
                    <select name="per_page" onchange="updateRowPerPage(this.value)" class="border border-neutral-200 bg-white px-2 py-1 focus:outline-none text-neutral-800 font-bold focus:border-neutral-950">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
        </div>

        <aside id="print-aside-target" class="w-full xl:w-96 shrink-0 relative">
            @if($selectedBooking)
                @php
                    $asidePayment = $selectedBooking->payments->last();
                    $asidePayStatus = $asidePayment ? $asidePayment->payment_status : 'pending';
                    if($selectedBooking->status == 'confirmed' || $selectedBooking->status == 'checked_in' || $selectedBooking->status == 'checked_out') {
                        $asidePayStatus = 'paid';
                    }
                    if($selectedBooking->status == 'cancelled') {
                        $asidePayStatus = 'failed';
                    }
                @endphp
                <div class="aside-main-container bg-white border border-neutral-200 shadow-sm p-6 space-y-6 flex flex-col font-sans">
                    
                    <div class="flex justify-between items-center border-b border-neutral-100 pb-4">
                        <h3 class="font-serif text-base text-neutral-900 tracking-wide font-medium">Reservation Details</h3>
                        <a href="{{ request()->fullUrlWithQuery(['selected_id' => null]) }}" class="text-neutral-400 hover:text-neutral-900 transition-colors cursor-pointer"><i class="fa-solid fa-xmark text-sm"></i></a>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            @if($selectedBooking->status == 'confirmed')
                                <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Confirmed</span>
                            @elseif($selectedBooking->status == 'pending')
                                <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Pending</span>
                            @elseif($selectedBooking->status == 'checked_in')
                                <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Checked In</span>
                            @elseif($selectedBooking->status == 'checked_out')
                                <span class="bg-neutral-100 text-neutral-700 border border-neutral-200 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Checked Out</span>
                            @else
                                <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[9px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Cancelled</span>
                            @endif
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-neutral-900 font-mono block">#OA-{{ str_pad($selectedBooking->id, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="text-[9px] text-neutral-400 block mt-0.5 font-semibold uppercase tracking-wider">System Operational</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Guest Information</h4>
                        <div class="flex items-center gap-4 p-3 bg-neutral-50/60 border border-neutral-100">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1200" class="w-10 h-10 object-cover border border-neutral-200" alt="Avatar">
                            <div class="overflow-hidden">
                                <span class="text-xs font-bold text-neutral-900 block truncate">{{ $selectedBooking->user->name ?? 'N/A' }}</span>
                                <span class="text-[10px] text-neutral-500 font-medium block mt-0.5 truncate">{{ $selectedBooking->user->email ?? 'N/A' }}</span>
                                <span class="text-[10px] text-neutral-400 font-mono block mt-0.5">{{ $selectedBooking->user->phone ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-[10px] font-medium text-neutral-500 px-1">
                            <i class="fa-solid fa-location-dot text-neutral-400 shrink-0"></i>
                            <span class="truncate">{{ $selectedBooking->user->address ?? 'No permanent address recorded' }}</span>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2 border-t border-neutral-100">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Reservation Information</h4>
                        <div class="grid grid-cols-2 gap-y-3.5 gap-x-4 text-xs font-medium text-neutral-600 px-1">
                            <div><span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Room Type</span><span class="text-neutral-900 font-bold block">{{ $selectedBooking->room->roomType->name ?? '-' }}</span></div>
                            <div><span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Room Number</span><span class="text-amber-800 font-mono font-bold block">Room {{ $selectedBooking->room->room_number ?? 'TBD' }}</span></div>
                            <div><span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Check-In</span><span class="text-neutral-900 font-bold block">{{ \Carbon\Carbon::parse($selectedBooking->check_in)->format('d M Y') }}</span></div>
                            <div><span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Check-Out</span><span class="text-neutral-900 font-bold block">{{ \Carbon\Carbon::parse($selectedBooking->check_out)->format('d M Y') }}</span></div>
                            <div>
                                <span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Duration</span>
                                <span class="text-neutral-900 font-bold block">
                                    {{ \Carbon\Carbon::parse($selectedBooking->check_in)->diffInDays(\Carbon\Carbon::parse($selectedBooking->check_out)) }} Nights
                                </span>
                            </div>
                            <div><span class="block text-[9px] text-neutral-400 tracking-wider mb-0.5 uppercase">Guests</span><span class="text-neutral-900 font-bold block">{{ $selectedBooking->guests_count }} Registered</span></div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-3 border-t border-neutral-100">
                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Payment Information</h4>
                        <div class="grid grid-cols-2 gap-y-2 text-xs font-medium text-neutral-600 px-1">
                            <div><span class="text-neutral-400">Method</span></div>
                            <div class="text-right text-neutral-900 font-bold uppercase font-mono">{{ str_replace('_', ' ', $asidePayment->payment_method ?? 'transfer') }}</div>
                            <div><span class="text-neutral-400">Status</span></div>
                            <div class="text-right">
                                @if($asidePayStatus == 'paid')
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.2 font-bold uppercase">Paid</span>
                                @elseif($asidePayStatus == 'failed')
                                    <span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-2 py-0.2 font-bold uppercase">Failed</span>
                                @else
                                    <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.2 font-bold uppercase">Unpaid</span>
                                @endif
                            </div>
                            <div class="col-span-2 border-t border-neutral-100 my-1"></div>
                            <div><span class="text-neutral-900 font-bold">Grand Total Amount</span></div>
                            <div class="text-right font-mono text-amber-950 font-bold text-sm">Rp {{ number_format($selectedBooking->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="aside-action-buttons pt-4 border-t border-neutral-100 space-y-2.5">
                        <button onclick="window.print()" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-3 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-print mr-1"></i> Print Registration Form</button>
                        
                        @if(auth()->user()->role !== 'manager')
                            @if($selectedBooking->status !== 'cancelled')
                                <form action="{{ route('admin.reservations.update', $selectedBooking->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?')">
                                    @csrf
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="w-full bg-white border border-neutral-200 hover:bg-neutral-50 text-rose-600 font-bold text-[10px] uppercase tracking-widest py-3 transition-colors cursor-pointer">Cancel Reservation</button>
                                </form>
                            @else
                                <div class="p-3 bg-rose-50 border border-rose-100 text-center select-none">
                                    <span class="text-[10px] font-bold text-rose-800 uppercase tracking-wider block"><i class="fa-solid fa-ban mr-1"></i> Reservation Has Been Cancelled</span>
                                </div>
                            @endif
                        @else
                            <div class="p-3 bg-neutral-50 border border-neutral-200 text-center select-none">
                                <span class="text-[10px] font-bold text-neutral-400 uppercase tracking-wider block"><i class="fa-solid fa-lock text-amber-700 mr-1"></i> Read-Only Audit Desk</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-neutral-400 text-xs bg-white border border-neutral-200 shadow-sm p-6 w-full">
                    <i class="fa-solid fa-folder-open block text-lg mb-2"></i> No active reservation data selected.
                </div>
            @endif
        </aside>

    </div>

    <div id="statusEditModal" class="fixed inset-0 bg-neutral-950/40 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-xl space-y-4">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                <h4 class="text-xs font-bold uppercase tracking-wider text-neutral-900">Update Manifest Status</h4>
                <button onclick="closeEditStatusModal()" class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-status-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase text-neutral-400 tracking-wider mb-1.5">Reservation Status Matrix</label>
                    <select name="status" id="modal-status-select" class="w-full border border-neutral-200 p-2.5 text-xs font-semibold focus:outline-none focus:border-neutral-900 text-neutral-800 bg-neutral-50">
                        <option value="pending">PENDING</option>
                        <option value="confirmed">CONFIRMED (LUNAS)</option>
                        <option value="checked_in">CHECKED IN</option>
                        <option value="checked_out">CHECKED OUT</option>
                        <option value="cancelled">CANCELLED</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[9px] uppercase tracking-widest py-2.5 transition-colors cursor-pointer">Save Changes</button>
                    <button type="button" onclick="closeEditStatusModal()" class="px-4 border border-neutral-200 text-neutral-600 hover:bg-neutral-50 font-bold text-[9px] uppercase tracking-widest transition-colors cursor-pointer">Abort</button>
                </div>
            </form>
        </div>
    </div>

    <div id="globalViewModal" class="fixed inset-0 bg-neutral-950/40 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white border border-neutral-200 max-w-lg w-full p-6 shadow-2xl flex flex-col font-sans space-y-4">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                <h3 class="font-serif text-sm font-bold text-neutral-900 tracking-wide flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-amber-700"></i> Reservation Audit Log Ledger
                </h3>
                <button onclick="closeGlobalViewModal()" class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <div id="modal-loading-indicator" class="py-12 text-center text-neutral-400 text-xs font-mono animate-pulse">
                <i class="fa-solid fa-spinner fa-spin mr-1"></i> Querying Ledger Data Matrix...
            </div>

            <div id="modal-content-area" class="hidden space-y-4 text-xs text-neutral-700">
                <div class="grid grid-cols-2 gap-4 bg-neutral-50 p-3 border border-neutral-100">
                    <div>
                        <span class="text-[8px] font-bold uppercase tracking-wider text-neutral-400 block">ID Log</span>
                        <span class="font-bold font-mono text-neutral-900 text-sm" id="md-id">#OA-00</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-bold uppercase tracking-wider text-neutral-400 block">Current Status</span>
                        <span id="md-status" class="inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border mt-0.5">STATUS</span>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Guest Ledger Profile</h5>
                    <div class="border border-neutral-200 p-2.5 space-y-1 bg-white">
                        <p class="font-bold text-neutral-900" id="md-name">-</p>
                        <p id="md-email">-</p>
                        <p class="font-mono text-[11px]" id="md-phone">-</p>
                        <p class="text-neutral-400 text-[11px]" id="md-address">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Enclosure Room</h5>
                        <div class="border border-neutral-200 p-2 bg-white">
                            <span class="font-bold text-neutral-900 block" id="md-type">-</span>
                            <span class="font-mono text-amber-800 font-bold text-[11px]" id="md-room">-</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h5 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Itinerary Manifest</h5>
                        <div class="border border-neutral-200 p-2 bg-white space-y-0.5">
                            <p>Check-In: <span class="font-bold text-neutral-900" id="md-in">-</span></p>
                            <p>Check-Out: <span class="font-bold text-neutral-900" id="md-out">-</span></p>
                            <p class="text-[10px] text-neutral-400"><span id="md-duration">-</span> &bull; <span id="md-guests">-</span></p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-neutral-100 pt-3 flex justify-between items-center">
                    <div>
                        <span class="text-[8px] font-bold uppercase tracking-wider text-neutral-400 block">Financial Summary (<span id="md-method">CASH</span>)</span>
                        <span class="inline-block text-[8px] font-bold uppercase tracking-wide px-1.5 mt-0.5 border" id="md-paystatus">STATUS</span>
                    </div>
                    <div class="text-right">
                        <span class="text-[8px] font-bold uppercase tracking-wider text-neutral-400 block">Total Spent</span>
                        <span class="font-mono font-bold text-neutral-900 text-base text-amber-950" id="md-total">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="border-t border-neutral-100 pt-3">
                <button onclick="closeGlobalViewModal()" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest py-3 transition-colors cursor-pointer">Acknowledge Audit Log</button>
            </div>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function updateRowPerPage(val) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('per_page', val);
        window.location.search = urlParams.toString();
    }

    function openEditStatusModal(bookingId, currentStatus) {
        const modal = document.getElementById('statusEditModal');
        const form = document.getElementById('edit-status-form');
        const select = document.getElementById('modal-status-select');
        
        form.action = `/admin/reservations/${bookingId}/update`;
        select.value = currentStatus;
        
        modal.classList.remove('hidden');
    }

    function closeEditStatusModal() {
        document.getElementById('statusEditModal').classList.add('hidden');
    }

    function openGlobalViewModal(bookingId) {
        const modal = document.getElementById('globalViewModal');
        const loading = document.getElementById('modal-loading-indicator');
        const content = document.getElementById('modal-content-area');
        
        loading.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-1"></i> Querying Ledger Data Matrix...';
        loading.classList.remove('hidden');
        content.classList.add('hidden');
        modal.classList.remove('hidden');

        // SINKRONISASI ROUTE YANG BENAR: Menggunakan /admin/reservations/{id}/json-detail sesuai web.php
        fetch(`/admin/reservations/${bookingId}/json-detail`)
            .then(res => {
                if (!res.ok) throw new Error('Server returned internal error status');
                return res.json();
            })
            .then(data => {
                if(data.success) {
                    document.getElementById('md-id').innerText = `#OA-${String(data.id).padStart(2, '0')}`;
                    document.getElementById('md-name').innerText = data.guest_name;
                    document.getElementById('md-email').innerText = data.guest_email;
                    document.getElementById('md-phone').innerText = data.guest_phone;
                    document.getElementById('md-address').innerText = data.guest_address || 'No permanent address recorded';
                    document.getElementById('md-type').innerText = data.room_type;
                    document.getElementById('md-room').innerText = `Room Number ${data.room_number || 'TBD'}`;
                    document.getElementById('md-in').innerText = data.check_in;
                    document.getElementById('md-out').innerText = data.check_out;
                    document.getElementById('md-duration').innerText = `${data.duration} Nights`;
                    document.getElementById('md-guests').innerText = `${data.guests_count} Registered`;
                    document.getElementById('md-method').innerText = data.payment_method || 'transfer';
                    document.getElementById('md-total').innerText = data.total_price;
                    
                    const statusBadge = document.getElementById('md-status');
                    statusBadge.innerText = data.status.toUpperCase();
                    if(data.status === 'confirmed' || data.status === 'checked_out' || data.status === 'checked_in') {
                        statusBadge.className = "inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-emerald-50 text-emerald-800 border-emerald-200";
                    } else if(data.status === 'pending') {
                        statusBadge.className = "inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-amber-50 text-amber-800 border-amber-200";
                    } else {
                        statusBadge.className = "inline-block text-[8px] font-bold uppercase tracking-wide px-2 py-0.5 border bg-rose-50 text-rose-800 border-rose-200";
                    }

                    const payBadge = document.getElementById('md-paystatus');
                    let payStatusText = data.payment_status || 'pending';
                    if(data.status === 'confirmed' || data.status === 'checked_in' || data.status === 'checked_out') {
                        payStatusText = 'paid';
                    }
                    if(data.status === 'cancelled') {
                        payStatusText = 'failed';
                    }
                    
                    payBadge.innerText = payStatusText.toUpperCase();
                    if(payStatusText === 'paid') {
                        payBadge.className = "inline-block text-[8px] font-bold uppercase tracking-wide px-1.5 mt-0.5 border bg-emerald-50 text-emerald-800 border-emerald-100";
                    } else {
                        payBadge.className = "inline-block text-[8px] font-bold uppercase tracking-wide px-1.5 mt-0.5 border bg-amber-50 text-amber-800 border-amber-100";
                    }

                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                } else {
                    loading.innerText = "Gagal memproses data manifes internal.";
                }
            })
            .catch(err => {
                loading.innerText = "Gagal memuat manifes data server. Periksa status route web.php.";
            });
    }

    function closeGlobalViewModal() {
        document.getElementById('globalViewModal').classList.add('hidden');
    }
</script>
