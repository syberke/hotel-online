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
                        <span class="text-[9px] text-neutral-400 font-medium">Units</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-600">{{ $stats['active'] }}</span>
                        <span class="text-[9px] text-amber-600 font-bold font-mono">Live</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Completed Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['completed'] }}</span>
                        <span class="text-[9px] text-emerald-600 font-bold font-mono">Settle</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-md font-bold text-neutral-900 font-mono">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Avg Order Value</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-xs font-bold text-neutral-900 font-mono">Rp {{ number_format($stats['avg_value'], 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 pb-3">
                    <a href="{{ url()->current() }}" class="text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5">Order Management</a>
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.restaurant.menu') }}" class="hover:text-neutral-900 transition-colors pb-1.5 px-0.5">Today's Menu</a>
                    @endif
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
                    <div class="flex flex-wrap text-[11px] font-bold uppercase tracking-wider text-neutral-400 gap-4">
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="px-3 py-1.5 {{ $currentTab === 'all' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">All Orders</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'dine_in']) }}" class="px-3 py-1.5 {{ $currentTab === 'dine_in' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">Dine In Class</a>
                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'room_service']) }}" class="px-3 py-1.5 {{ $currentTab === 'room_service' ? 'text-neutral-900 bg-neutral-100 font-bold' : '' }}">Room Service Log</a>
                    </div>

                    <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full lg:w-auto">
                        <input type="hidden" name="tab" value="{{ $currentTab }}">
                        <div class="relative flex-1 lg:flex-none lg:min-w-[200px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Order ID, Guest..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto custom-scrollbar pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                                <th class="py-3 px-4 font-semibold">Order ID</th>
                                <th class="py-3 px-4 font-semibold">Type Class</th>
                                <th class="py-3 px-4 font-semibold">Guest Target Assignment</th>
                                <th class="py-3 px-4 font-semibold">Order Time</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Total Amount</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($orders as $order)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-neutral-900 font-mono">#RS-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-3.5 px-4 text-neutral-500 text-xs">
                                        @if($order->room_number)
                                            <i class="fa-solid fa-bowl-food text-blue-600 mr-1.5 text-[10px]"></i> Room Service
                                        @else
                                            <i class="fa-solid fa-chair text-amber-700 mr-1.5 text-[10px]"></i> Dine In / Cafe
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="font-bold text-neutral-900 block">{{ $order->guest_name }}</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal font-mono mt-0.5">
                                            {{ $order->room_number ? 'Room ' . $order->room_number : 'Table Walk-in' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-neutral-700 font-mono">
                                        {{ date('d M Y', strtotime($order->created_at)) }}
                                        <span class="block text-[9px] text-neutral-400 font-normal mt-0.5">{{ date('h:i A', strtotime($order->created_at)) }}</span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($order->status === 'ordered')
                                            <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Pending</span>
                                        @elseif($order->status === 'preparing')
                                            <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Preparing</span>
                                        @elseif($order->status === 'paid')
                                            <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Ready</span>
                                        @else
                                            <span class="bg-red-50 text-red-800 border border-red-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Void</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <button type="button"
                                                data-restaurant-order-detail
                                                data-detail-url="{{ route('admin.restaurant.order.json', $order->id) }}"
                                                class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-amber-700 cursor-pointer flex items-center justify-center shadow-xs" title="Lihat detail pesanan">
                                                <i class="fa-solid fa-eye text-xs"></i>
                                            </button>
                                            @if(auth()->user()->role !== 'manager')
                                                <button type="button" 
                                                        onclick="openOrderDropdown(event, {{ $order->id }}, '{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}')"
                                                        class="dropdown-trigger-btn w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer flex items-center justify-center shadow-xs" title="Ubah status pesanan">
                                                    <i class="fa-solid fa-ellipsis-vertical text-xs"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-neutral-400 font-sans italic">No gastronomy billing records matched criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing entries {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} results</span>
                    <div class="font-sans text-neutral-800">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Overview</h3>
                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Real-time daily financial matrix trendlines</span>
                    </div>
                </div>
                
                <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f3f4f6" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f3f4f6" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f3f4f6" stroke-width="1" />
                        <path d="M 0,140 L {{ $polylineCoordinates }} L 600,140 Z" fill="#fef3c7" fill-opacity="0.25"/>
                        <path d="M {{ $polylineCoordinates }}" fill="none" stroke="#d97706" stroke-width="2.5" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                        @foreach($chartLabels as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Order Status Shares</h3>
                </div>
                
                <div class="flex items-center gap-4 my-2">
                    <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                        <div class="absolute text-center">
                            <span class="text-xl font-light font-serif text-neutral-900 block leading-none">{{ $stats['total'] }}</span>
                            <span class="text-[7px] text-neutral-400 uppercase tracking-wider font-bold block mt-0.5">Total</span>
                        </div>
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e5e7eb" stroke-width="3.5"></circle>
                        </svg>
                    </div>
                    <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Completed</span><span class="text-neutral-800 font-mono font-bold">{{ $statusCounts['completed'] }}</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-500 inline-block mr-1.5"></span>Preparing</span><span class="text-neutral-800 font-mono font-bold">{{ $statusCounts['progress'] }}</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Pending</span><span class="text-neutral-800 font-mono font-bold">{{ $statusCounts['pending'] }}</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Cancelled</span><span class="text-neutral-800 font-mono font-bold">{{ $statusCounts['cancelled'] }}</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Top Selling Items</h3>
                </div>
                
                <div class="space-y-4 flex-1">
                    @forelse($topSellingItems as $item)
                        <div class="flex items-center justify-between text-xs font-semibold text-neutral-700">
                            <div class="flex items-center gap-3 truncate max-w-[170px]">
                                <img src="{{ $item->foto_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100' }}" class="w-8 h-8 object-cover border border-neutral-200 rounded-xs shrink-0">
                                <div class="truncate">
                                    <span class="block truncate text-neutral-900">{{ $item->name }}</span>
                                    <span class="block text-[9px] text-neutral-400 font-normal mt-0.5">{{ $item->total_qty }} Units Sold</span>
                                </div>
                            </div>
                            <span class="font-mono text-neutral-900 text-[11px] font-bold">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="text-center text-neutral-400 italic py-4 text-[11px]">No sales items compiled yet.</div>
                    @endforelse
                </div>
            </div>
        </aside>

    </div>

    <div id="restaurant-order-detail-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
        <button type="button" data-close-restaurant-detail class="absolute inset-0 cursor-default" aria-label="Tutup detail pesanan"></button>
        <div class="relative bg-white max-w-md w-full border border-neutral-200 p-6 shadow-2xl flex flex-col text-left font-sans text-neutral-900">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-800">Gastronomy Manifest Audit</h4>
                <button type="button" data-close-restaurant-detail class="text-neutral-400 hover:text-neutral-900 transition-colors cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="grid grid-cols-2 gap-3 text-[11px] font-medium text-neutral-500 mb-4 pb-3 border-b border-neutral-50">
                <div>
                    <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Order ID</span>
                    <span id="restaurant-detail-id" class="font-mono text-neutral-900 font-bold">-</span>
                </div>
                <div>
                    <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Destination Assignment</span>
                    <span id="restaurant-detail-room" class="text-neutral-900 font-bold">-</span>
                </div>
                <div>
                    <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Guest Profile</span>
                    <span id="restaurant-detail-guest" class="text-neutral-900 font-bold">-</span>
                </div>
                <div>
                    <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Timestamp</span>
                    <span id="restaurant-detail-time" class="text-neutral-700 font-mono text-[10px]">-</span>
                </div>
            </div>

            <div class="text-[11px] w-full mb-4">
                <div class="bg-neutral-50 p-2 flex justify-between font-bold text-[8px] text-neutral-400 uppercase tracking-wider">
                    <span>Menu Item</span>
                    <span class="w-12 text-center">Qty</span>
                    <span class="w-24 text-right">Price</span>
                </div>
                <div id="restaurant-detail-items" class="divide-y divide-neutral-100 max-h-40 overflow-y-auto custom-scrollbar">
                </div>
            </div>

            <div class="flex justify-between items-baseline border-t border-neutral-100 pt-3 font-sans mt-2">
                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-400">Total Charged Invoice:</span>
                <span id="restaurant-detail-total" class="text-md font-mono font-bold text-neutral-900">Rp 0</span>
            </div>

            <div class="mt-5 flex gap-2">
                <div class="flex-1 bg-neutral-50 border p-2 text-center">
                    <span class="text-[8px] font-bold text-neutral-400 uppercase tracking-wider block">Workflow Matrix</span>
                    <span id="restaurant-detail-status" class="text-[10px] font-bold uppercase font-mono tracking-wide mt-0.5 inline-block text-amber-700 animate-pulse">-</span>
                </div>
                <button type="button" data-close-restaurant-detail class="bg-neutral-950 hover:bg-neutral-800 text-white font-bold text-[9px] uppercase tracking-widest px-6 py-3 transition-colors shadow-sm cursor-pointer">
                    Dismiss View
                </button>
            </div>
        </div>
    </div>

    <div id="order-status-dropdown" class="hidden fixed w-48 bg-white border border-neutral-200 shadow-2xl z-50 text-left font-sans text-xs">
        <div class="p-2 border-b border-neutral-100 bg-neutral-50 text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Update Order <span id="drop-order-id" class="font-mono text-neutral-900"></span></div>
        <form id="form-update-order-status" action="" method="POST" class="m-0" data-confirm="Ubah status operasional pesanan ini?" data-confirm-title="Perbarui Status Pesanan">
            @csrf
            <input type="hidden" name="prev_tab" value="{{ $currentTab }}">
            <input type="hidden" name="prev_search" value="{{ request('search') }}">
            <button name="status" value="ordered" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-amber-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span> Set ordered (Pending)</button>
            <button name="status" value="preparing" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-blue-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Set Preparing</button>
            <button name="status" value="paid" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-emerald-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Set paid (Ready)</button>
            <button name="status" value="cancelled" class="w-full text-left px-4 py-2 hover:bg-neutral-50 flex items-center text-rose-700 font-semibold cursor-pointer"><span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Set Cancelled</button>
        </form>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function openOrderDropdown(event, orderId, orderString) {
        event.stopPropagation();
        const dropdown = document.getElementById('order-status-dropdown');
        const triggerBtn = event.currentTarget;
        
        document.getElementById('drop-order-id').innerText = '#RS-' + orderString;
        document.getElementById('form-update-order-status').action = `/admin/restaurant-order/${orderId}/update-status`;
        
        const rect = triggerBtn.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + window.scrollY + 4) + 'px';
        dropdown.style.left = (rect.left + window.scrollX - 160) + 'px';
        dropdown.classList.remove('hidden');
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('order-status-dropdown');
        if (dropdown && !dropdown.contains(event.target) && !event.target.closest('.dropdown-trigger-btn')) {
            dropdown.classList.add('hidden');
        }
    });
</script>
