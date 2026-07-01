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
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['total'] }}</span>
                        <span class="text-[10px] font-bold text-neutral-400 font-mono">Units</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Pending Queue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-600">{{ $stats['pending'] }}</span>
                        <span class="text-[10px] font-bold text-amber-600 font-mono">Ordered</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In Progress</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-600">{{ $stats['in_progress'] }}</span>
                        <span class="text-[10px] font-bold text-blue-600 font-mono">Kitchen</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Completed</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['completed'] }}</span>
                        <span class="text-[10px] font-bold text-emerald-600 font-mono">Settle</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Cancelled Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-red-600">{{ $stats['cancelled'] }}</span>
                        <span class="text-[10px] font-bold text-red-600 font-mono">Void</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-neutral-100 pb-2">
                    
                    <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-5">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="{{ $currentTab === 'all' ? 'text-neutral-900 border-b-2 border-neutral-900' : 'hover:text-neutral-900' }} pb-3 px-0.5">All Orders</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'pending']) }}" class="{{ $currentTab === 'pending' ? 'text-neutral-900 border-b-2 border-neutral-900' : 'hover:text-neutral-900' }} pb-3 px-0.5">Pending</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'in_progress']) }}" class="{{ $currentTab === 'in_progress' ? 'text-neutral-900 border-b-2 border-neutral-900' : 'hover:text-neutral-900' }} pb-3 px-0.5">In Progress</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'completed']) }}" class="{{ $currentTab === 'completed' ? 'text-neutral-900 border-b-2 border-neutral-900' : 'hover:text-neutral-900' }} pb-3 px-0.5">Completed</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'cancelled']) }}" class="{{ $currentTab === 'cancelled' ? 'text-neutral-900 border-b-2 border-neutral-900' : 'hover:text-neutral-900' }} pb-3 px-0.5">Cancelled</a>
                    </div>

                    <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="tab" value="{{ $currentTab }}">
                        <div class="relative flex-1 lg:flex-none lg:min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order ID or Guest..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">Apply</button>
                    </form>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left text-xs whitespace-nowrap">
        <thead>
            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                <th class="py-3 px-4 font-semibold">Order ID</th>
                <th class="py-3 px-4 font-semibold">Guest</th>
                <th class="py-3 px-4 font-semibold">Room</th>
                <th class="py-3 px-4 font-semibold">Order Time</th>
                <th class="py-3 px-4 font-semibold">Status</th>
                <th class="py-3 px-4 font-semibold">Amount</th>
                <th class="py-3 px-4 text-center font-semibold">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
            @forelse($orders as $order)
                @php
                    // Validasi pencocokan ID untuk memberikan highlight emas pada baris aktif
                    $isActiveRow = (isset($selectedOrderId) && $selectedOrderId == $order->id);
                @endphp
                <tr class="hover:bg-neutral-50/70 transition-all duration-150 {{ $isActiveRow ? 'bg-amber-50/40 border-l-4 border-amber-600 font-semibold shadow-xs' : '' }} cursor-pointer" onclick="window.location='{{ request()->fullUrlWithQuery(['selected_id' => $order->id]) }}'">
                    <td class="py-3.5 px-4">
                        <span class="font-bold text-neutral-900 block font-mono">#RS-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ date('d M Y', strtotime($order->created_at)) }}</span>
                    </td>
                    <td class="py-3.5 px-4 flex items-center gap-2.5">
                        <img src="{{ $order->guest_avatar ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100' }}" class="w-6 h-6 object-cover border border-neutral-200 rounded-full">
                        <div>
                            <span class="font-bold text-neutral-900 block">{{ $order->guest_name }}</span>
                            <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ $order->guest_phone ?? 'N/A' }}</span>
                        </div>
                    </td>
                    <td class="py-3.5 px-4">
                        <span class="text-neutral-900 font-bold block">{{ $order->room_number ?? 'TBD' }}</span>
                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">{{ $order->room_type_name ?? 'In-House Service' }}</span>
                    </td>
                    <td class="py-3.5 px-4 text-neutral-700 font-mono">
                        {{ date('d M Y', strtotime($order->created_at)) }}
                        <span class="block text-[9px] text-neutral-400 font-normal mt-0.5">{{ date('h:i A', strtotime($order->created_at)) }}</span>
                    </td>
                    <td class="py-3.5 px-4">
                        @if($order->status === 'ordered')
                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Pending</span>
                        @elseif($order->status === 'preparing')
                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">In Progress</span>
                        @elseif($order->status === 'paid')
                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Completed</span>
                        @else
                            <span class="bg-red-50 text-red-800 border border-red-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Cancelled</span>
                        @endif
                    </td>
                    <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td class="py-3.5 px-4 text-center" onclick="event.stopPropagation();">
                        @if(auth()->user()->role !== 'manager')
                            <button type="button" onclick="openFloatingActions(event, {{ $order->id }}, '{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}')" class="dropdown-trigger-btn w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-500 cursor-pointer flex items-center justify-center shadow-xs">
                                <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                            </button>
                        @else
                            <button type="button" class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-neutral-400 font-sans italic">No kitchen gastronomy logs compiled in our matrix registry.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex justify-between items-center text-[11px] text-neutral-400 pt-2 font-medium">
    <span>Showing entries {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results</span>
    <div class="font-sans text-neutral-800">
        {{ $orders->links() }}
    </div>
</div>
            </div>
        </div>

        <aside class="w-full xl:w-96 bg-white border border-neutral-200 shadow-sm p-6 space-y-5 shrink-0 relative">
            @if($selectedOrder)
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3.5">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-serif text-sm text-neutral-900 tracking-wide font-medium">Order Details</h3>
                            <span class="bg-amber-800 text-white font-mono text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide">Selected</span>
                        </div>
                        <span class="text-neutral-500 font-mono text-[10px] block mt-1">#RS-{{ str_pad($selectedOrder->id, 4, '0', STR_PAD_LEFT) }} &bull; {{ date('d M Y', strtotime($selectedOrder->created_at)) }}</span>
                    </div>
                </div>

                <div class="space-y-2.5">
                    <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Guest Information</h4>
                    <div class="flex items-center gap-3.5 p-3 bg-neutral-50/60 border border-neutral-100">
                        <img src="{{ $selectedOrder->guest_avatar ?? 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=100' }}" class="w-9 h-9 object-cover border border-neutral-200 rounded-full">
                        <div>
                            <span class="text-xs font-bold text-neutral-900 block flex items-center gap-1.5">
                                {{ $selectedOrder->guest_name }} 
                                @if($selectedOrder->is_vip)
                                    <span class="bg-amber-100 text-amber-900 border border-amber-200 font-mono font-bold text-[7px] px-1.5 py-0.1 tracking-normal uppercase">VIP</span>
                                @endif
                            </span>
                            <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">Room {{ $selectedOrder->room_number ?? 'TBD / Walk-In' }} &bull; {{ $selectedOrder->room_type_name ?? 'General Room' }}</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2.5 pt-1.5 border-t border-neutral-100 text-xs font-medium text-neutral-600">
                    <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Order Information</h4>
                    <div class="grid grid-cols-2 gap-y-2 px-1">
                        <div><span class="text-neutral-400">Order Status</span></div>
                        <div class="text-right font-bold capitalize">
                            @if($selectedOrder->status === 'ordered')
                                <span class="text-amber-700">Pending</span>
                            @elseif($selectedOrder->status === 'preparing')
                                <span class="text-blue-700">In Progress</span>
                            @elseif($selectedOrder->status === 'paid')
                                <span class="text-emerald-700">Completed</span>
                            @else
                                <span class="text-neutral-400 line-through">Cancelled</span>
                            @endif
                        </div>
                        <div><span class="text-neutral-400">Placement Time</span></div>
                        <div class="text-right font-mono text-neutral-900">{{ date('d M Y • h:i A', strtotime($selectedOrder->created_at)) }}</div>
                        <div><span class="text-neutral-400">Contact Line</span></div>
                        <div class="text-right text-neutral-800 font-mono">{{ $selectedOrder->guest_phone ?? 'N/A' }}</div>
                    </div>
                </div>

                <div class="space-y-2 pt-2 border-t border-neutral-100 text-xs">
                    <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Order Items</h4>
                    <div class="divide-y divide-neutral-50 px-1 font-medium text-neutral-700">
                        @forelse($orderItems as $item)
                            <div class="flex justify-between py-1.5">
                                <span>{{ $item->quantity }}x <span class="text-neutral-900 ml-1">{{ $item->menu_name }}</span></span>
                                <span class="font-mono text-neutral-500">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <div class="text-center py-2 text-neutral-400 italic">No detailed items recorded.</div>
                        @endforelse
                    </div>
                    
                    <div class="bg-neutral-50/50 border border-neutral-100 p-3 flex justify-between pt-2 text-xs font-bold text-neutral-900 mt-2">
                        <span>Grand Total Bill</span>
                        <span class="font-mono text-amber-950 text-sm">Rp {{ number_format($selectedOrder->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-neutral-100">
                    @if(auth()->user()->role !== 'manager')
                        @if($selectedOrder->status !== 'paid' && $selectedOrder->status !== 'cancelled')
                            <div class="grid grid-cols-3 gap-2.5 text-center font-bold text-[9px] uppercase tracking-wider">
                                <form action="{{ route('admin.restaurant.update-status', $selectedOrder->id) }}" method="POST" class="m-0">
                                    @csrf 
                                    <button name="status" value="cancelled" type="submit" class="w-full bg-white border border-neutral-200 hover:bg-rose-50 text-rose-600 py-2.5 shadow-sm cursor-pointer transition-colors font-bold uppercase tracking-wider">Cancel</button>
                                </form>
                                
                                @if($selectedOrder->status !== 'preparing')
                                    <form action="{{ route('admin.restaurant.update-status', $selectedOrder->id) }}" method="POST" class="m-0 col-span-1">
                                        @csrf 
                                        <button name="status" value="preparing" type="submit" class="w-full bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 py-2.5 shadow-sm cursor-pointer transition-colors font-bold uppercase tracking-wider">Prepare</button>
                                    </form>
                                @else
                                    <div class="bg-blue-50/60 border border-blue-200 text-blue-700 py-2.5 text-center flex items-center justify-center gap-1 font-bold">
                                        <i class="fa-solid fa-fire-burner text-[9px]"></i> In Kitchen
                                    </div>
                                @endif

                                <form action="{{ route('admin.restaurant.update-status', $selectedOrder->id) }}" method="POST" class="m-0">
                                    @csrf 
                                    <button name="status" value="paid" type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white py-2.5 shadow-sm cursor-pointer transition-colors font-bold uppercase tracking-wider">Complete</button>
                                </form>
                            </div>
                        @else
                            <div class="bg-neutral-50 border border-neutral-200 p-3 text-center rounded-sm select-none">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-circle-minus text-neutral-400"></i> No Actions Available (Order Finalized)
                                </span>
                            </div>
                        @endif
                    @else
                        <div class="bg-neutral-50 border border-neutral-200 p-3 text-center select-none">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-lock text-neutral-400 text-xs"></i> Locked View Mode (Read-Only)
                            </span>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-12 text-neutral-400 italic text-xs font-sans">
                    Select an invoice item entry to display core operational timelines.
                </div>
            @endif
        </aside>

    </div>

    <div id="floating-action-overlay" class="hidden fixed w-44 bg-white border border-neutral-200 shadow-2xl z-50 text-left font-sans text-xs">
        <div class="p-2 border-b border-neutral-100 bg-neutral-50 text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Update Order status <span id="drop-id-title"></span></div>
        <form id="form-update-status-overlay" action="" method="POST" class="m-0">
            @csrf
            <button name="status" value="preparing" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-blue-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Move to Kitchen</button>
            <button name="status" value="paid" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-emerald-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Settle & Complete</button>
            <button name="status" value="cancelled" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-rose-600 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Void / Cancel</button>
        </form>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function openFloatingActions(event, orderId, orderNumber) {
        event.stopPropagation();
        
        const overlay = document.getElementById('floating-action-overlay');
        const triggerBtn = event.currentTarget;
        
        document.getElementById('drop-id-title').innerText = '(#' + orderNumber + ')';
        document.getElementById('form-update-status-overlay').action = `/restaurant-order/${orderId}/update-status`;
        
        const rect = triggerBtn.getBoundingClientRect();
        
        overlay.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        overlay.style.left = (rect.left + window.scrollX - 140) + 'px';
        
        overlay.classList.remove('hidden');
    }

    document.addEventListener('click', function(event) {
        const overlay = document.getElementById('floating-action-overlay');
        if (overlay && !overlay.contains(event.target) && !event.target.closest('.dropdown-trigger-btn')) {
            overlay.classList.add('hidden');
        }
    });
</script>