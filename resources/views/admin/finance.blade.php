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

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 18.7% <span class="text-neutral-400 font-normal">Live DB</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Room Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp {{ number_format($stats['room_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">F&B Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp {{ number_format($stats['fb_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Other Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp {{ number_format($stats['other_revenue'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Expenses</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp {{ number_format($stats['expenses'], 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm bg-emerald-50/10 border-emerald-500/20">
            <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider block">Net Profit</span>
            <div class="mt-2">
                <span class="text-xl font-bold text-emerald-950 block font-mono">Rp {{ number_format($stats['net_profit'], 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Overview</h3>
                <span class="text-[9px] bg-neutral-50 border border-neutral-200 px-2 py-1 text-neutral-500 font-mono font-bold uppercase">Weekly Live Trend</span>
            </div>
            
            <div class="relative w-full h-48 flex flex-col justify-between pt-2">
                <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                    <path d="M {{ $polylineCoordinates }}" fill="none" stroke="#059669" stroke-width="2.5" />
                </svg>
                <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                    @foreach($chartLabels as $label)
                        <span>{{ $label }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue by Category</h3>
            </div>
            <div class="flex items-center gap-4 my-auto">
                <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#e5e7eb" stroke-width="4"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xs font-bold text-neutral-900 font-mono block">Category</span>
                        <span class="text-[7px] text-neutral-400 uppercase tracking-wider font-bold block mt-0.5">Shares</span>
                    </div>
                </div>
                <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-600 inline-block mr-1.5"></span>Room</span><span class="text-neutral-900 font-mono">{{ $shares['room'] }}%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>F&B</span><span class="text-neutral-900 font-mono">{{ $shares['fb'] }}%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-600 inline-block mr-1.5"></span>Other</span><span class="text-neutral-900 font-mono">{{ $shares['other'] }}%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-2">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Rolling Matrix</h3>
            </div>
            <div class="divide-y divide-neutral-50 text-xs font-semibold text-neutral-600 flex-1 flex flex-col justify-between py-1">
                <div class="flex justify-between py-1.5 bg-neutral-50 px-1 font-bold text-neutral-900"><span>Accumulated Volume</span><span class="font-mono text-emerald-700">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</span></div>
                <div class="text-[9px] text-neutral-400 font-sans italic py-2">Data murni diambil dinamis berdasarkan akumulasi transaksi pembayaran berstatus lunas di database hotel.</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-8">
        <div class="lg:col-span-3 bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-3">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Recent Transactions Ledger</h3>
                
                <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2.5 w-full md:w-auto">
                    <div class="relative min-w-[200px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search transaction ID, method..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-1.5 text-xs font-bold uppercase tracking-wider cursor-pointer">Filter</button>
                </form>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                            <th class="py-3 px-3 font-semibold">Transaction ID</th>
                            <th class="py-3 px-3 font-semibold">Date & Time</th>
                            <th class="py-3 px-3 font-semibold">Guest Reference</th>
                            <th class="py-3 px-3 font-semibold">Category Allocation</th>
                            <th class="py-3 px-3 font-semibold">Amount</th>
                            <th class="py-3 px-3 font-semibold">Method</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-3 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">#TRX-{{ str_pad($trx->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td class="py-3.5 px-3 font-mono text-neutral-500">
                                    {{ date('d M Y', strtotime($trx->created_at)) }}
                                    <span class="block text-[9px] mt-0.5">{{ date('H:i A', strtotime($trx->created_at)) }}</span>
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="font-bold text-neutral-900 block">{{ $trx->guest_name ?? 'Outside Customer' }}</span>
                                    <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">Ref ID: #{{ $trx->booking_id ?? ($trx->restaurant_order_id ? 'REST-'.$trx->restaurant_order_id : 'WALK-IN') }}</span>
                                </td>
                                <td class="py-3.5 px-3 text-neutral-500 text-xs">
                                    @if($trx->booking_id) Room Allocation @elseif($trx->restaurant_order_id) Gastronomy F&B @else POS / Other @endif
                                </td>
                                <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp {{ number_format($trx->amount, 0, ',', '.') }}</td>
                                <td class="py-3.5 px-3 uppercase text-[11px] font-semibold text-neutral-700 font-sans">{{ str_replace('_', ' ', $trx->payment_method) }}</td>
                                <td class="py-3.5 px-4">
    @if($trx->payment_status === 'paid' || (isset($trx->b_status) && $trx->b_status === 'confirmed'))
        <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Paid </span>
    @elseif($trx->payment_status === 'pending')
        <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Pending</span>
    @else
        <span class="bg-red-50 text-red-800 border border-red-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Failed</span>
    @endif
</td>
                                <td class="py-3.5 px-3 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button type="button" onclick="openManageTrxModal({{ $trx->id }})" class="text-neutral-500 hover:text-neutral-900 cursor-pointer flex items-center justify-center mx-auto w-6 h-6 border border-neutral-200 bg-white shadow-xs" title="Manage Status Pipeline"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    @else
                                        <button type="button" onclick="viewTrxDetail({{ $trx->id }})" class="text-amber-800 hover:text-amber-950 cursor-pointer flex items-center justify-center mx-auto w-6 h-6 border border-neutral-200 bg-white shadow-xs" title="View Secure Audit Receipt"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-neutral-400 italic">No ledger transaction items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing entries {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} results</span>
                <div class="font-sans text-neutral-800">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>

        <div class="space-y-6 shrink-0 w-full lg:w-auto">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Payment Method Breakdown</h3>
                </div>
                <div class="space-y-3.5 text-xs font-semibold text-neutral-600">
                    <div class="flex justify-between items-center"><span><i class="fa-regular fa-credit-card text-neutral-400 w-5"></i> Credit Card</span><span class="font-mono text-neutral-900">Rp {{ number_format($methodBreakdown['credit_card']['amount'], 0, ',', '.') }} <span class="text-[9px] text-neutral-400 font-normal">({{ $methodBreakdown['credit_card']['pct'] }}%)</span></span></div>
                    <div class="flex justify-between items-center"><span><i class="fa-solid fa-money-bill-wave text-neutral-400 w-5"></i> Cash Ledger</span><span class="font-mono text-neutral-900">Rp {{ number_format($methodBreakdown['cash']['amount'], 0, ',', '.') }} <span class="text-[9px] text-neutral-400 font-normal">({{ $methodBreakdown['cash']['pct'] }}%)</span></span></div>
                    <div class="flex justify-between items-center"><span><i class="fa-solid fa-building-columns text-neutral-400 w-5"></i> Bank Transfer</span><span class="font-mono text-neutral-900">Rp {{ number_format($methodBreakdown['transfer']['amount'], 0, ',', '.') }} <span class="text-[9px] text-neutral-400 font-normal">({{ $methodBreakdown['transfer']['pct'] }}%)</span></span></div>
                </div>
            </div>

        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Expense Overview Allocation</h3>
                </div>
                <span class="text-md font-bold font-mono text-neutral-900">Rp {{ number_format($stats['expenses'], 0, ',', '.') }}</span>
            </div>
            
            <div class="space-y-4 flex-1 flex flex-col justify-center">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Payroll & Staff Salaries</span><span class="font-mono text-neutral-900">44.5%</span></div>
                    <div class="w-full h-1.5 bg-neutral-100 overflow-hidden"><div class="h-full bg-neutral-900" style="width: 44.5%"></div></div>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Property Utilities (Water/Electricity)</span><span class="font-mono text-neutral-900">18.6%</span></div>
                    <div class="w-full h-1.5 bg-neutral-100 overflow-hidden"><div class="h-full bg-neutral-900" style="width: 18.6%"></div></div>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Hotel Maintenance & Repairs</span><span class="font-mono text-neutral-900">13.1%</span></div>
                    <div class="w-full h-1.5 bg-neutral-100 overflow-hidden"><div class="h-full bg-neutral-900" style="width: 13.1%"></div></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Expense Share Matrix</h3>
            </div>
            <div class="flex items-center gap-4 my-auto">
                <div class="relative w-20 h-20 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="none" stroke="#ef4444" stroke-width="4"></circle>
                    </svg>
                </div>
                <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Payroll</span><span class="text-neutral-800 font-mono">44.5%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Utilities</span><span class="text-neutral-800 font-mono">18.6%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Maint.</span><span class="text-neutral-800 font-mono">13.1%</span></div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalTrxDetail" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Financial Audit Receipt</h4>
                <button type="button" onclick="closeTrxDetailModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="space-y-3 text-xs font-medium text-neutral-600">
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Guest Client:</span><span id="lbl_trx_guest" class="text-neutral-900 font-bold"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Gross Amount:</span><span id="lbl_trx_amount" class="text-emerald-700 font-mono font-bold"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Method Channel:</span><span id="lbl_trx_method" class="text-neutral-900 uppercase"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>Settlement Status:</span><span id="lbl_trx_status" class="font-bold uppercase font-sans"></span></div>
                <div class="flex justify-between border-b border-neutral-50 pb-1.5"><span>System Note:</span><span id="lbl_trx_note" class="text-neutral-400 italic"></span></div>
            </div>
        </div>
    </div>

    <div id="modalManageTrx" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Update Settlement Stage</h4>
                <button type="button" onclick="closeManageTrxModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formUpdateTrxStage" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Payment Status Level</label>
                    <select name="payment_status" id="select_trx_status" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                        <option value="paid">Paid / Complete (Settlement)</option>
                        <option value="pending">Pending / Waiting Payment</option>
                        <option value="failed">Failed / Voided / Refunded</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Force Sync Status</button>
            </form>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function viewTrxDetail(id) {
        fetch(`/admin/finance/transaction/${id}/detail`)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('lbl_trx_guest').innerText = res.data.guest_name ?? 'Walk-in Cash Customer';
                    document.getElementById('lbl_trx_amount').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(res.data.amount));
                    document.getElementById('lbl_trx_method').innerText = res.data.payment_method.replace('_', ' ');
                    document.getElementById('lbl_trx_status').innerText = res.data.payment_status;
                    document.getElementById('lbl_trx_note').innerText = res.data.note ?? 'None recorded.';
                    
                    const statusLbl = document.getElementById('lbl_trx_status');
                    statusLbl.className = res.data.payment_status === 'paid' ? 'text-emerald-600 font-bold uppercase' : 'text-amber-600 font-bold uppercase';
                    
                    document.getElementById('modalTrxDetail').classList.remove('hidden');
                }
            });
    }

    function openManageTrxModal(id) {
        fetch(`/admin/finance/transaction/${id}/detail`)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    document.getElementById('select_trx_status').value = res.data.payment_status;
                    document.getElementById('formUpdateTrxStage').action = `/admin/finance/transaction/${id}/update`;
                    document.getElementById('modalManageTrx').classList.remove('hidden');
                }
            });
    }

    function closeTrxDetailModal() { document.getElementById('modalTrxDetail').classList.add('hidden'); }
    function closeManageTrxModal() { document.getElementById('modalManageTrx').classList.add('hidden'); }
</script>