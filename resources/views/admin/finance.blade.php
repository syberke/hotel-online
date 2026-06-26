<x-admin-dashboard-layout>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp 152.450.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 18.7% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Room Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp 104.250.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 16.3% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">F&B Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp 28.650.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 22.1% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Other Revenue</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp 19.550.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 15.4% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Expenses</span>
            <div class="mt-2">
                <span class="text-lg font-bold text-neutral-900 block font-mono">Rp 48.230.000</span>
                <span class="text-[10px] font-bold text-red-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-down text-[8px]"></i> 6.8% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm bg-emerald-50/10 border-emerald-500/20">
            <span class="text-[9px] font-bold text-emerald-800 uppercase tracking-wider block">Net Profit</span>
            <div class="mt-2">
                <span class="text-xl font-bold text-emerald-950 block font-mono">Rp 104.220.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 24.1% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Overview</h3>
                </div>
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-4 text-[9px] font-bold uppercase tracking-wider text-neutral-400 mr-2">
                        <span class="flex items-center gap-1.5"><span class="w-2 h-0.5 bg-emerald-600 inline-block"></span> This Week</span>
                        <span class="flex items-center gap-1.5"><span class="w-2 h-0.5 bg-neutral-300 inline-block"></span> Last Week</span>
                    </div>
                    <span class="text-[9px] bg-neutral-50 border border-neutral-200 px-2 py-1 text-neutral-500 font-mono font-bold uppercase">Daily <i class="fa-solid fa-chevron-down ml-0.5"></i></span>
                </div>
            </div>
            
            <div class="relative w-full h-48 flex flex-col justify-between pt-2">
                <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="50" x2="600" y2="50" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="80" x2="600" y2="80" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="110" x2="600" y2="110" stroke="#f4f4f5" stroke-width="1" />
                    <path d="M 0,90 L 100,60 L 200,95 L 300,75 L 400,105 L 500,85 L 600,60" fill="none" stroke="#d4d4d8" stroke-width="1.5" stroke-dasharray="4" />
                    <path d="M 0,80 L 100,35 L 200,75 L 300,45 L 400,85 L 500,65 L 600,40" fill="none" stroke="#059669" stroke-width="2.5" />
                    <circle cx="100" cy="35" r="3.5" fill="#059669" />
                    <circle cx="300" cy="45" r="3.5" fill="#059669" />
                </svg>
                <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                    <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span><span>21 Jun</span><span>22 Jun</span><span>23 Jun</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue by Category</h3>
            </div>
            <div class="flex items-center gap-4 my-auto">
                <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#9333ea" stroke-width="4.5" stroke-dasharray="12 88" stroke-dashoffset="-88"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="18.8 81.2" stroke-dashoffset="-69.2"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#d97706" stroke-width="4.5" stroke-dasharray="68.4 31.6" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-base font-medium text-neutral-900 font-mono block leading-none">Rp 152.4M</span>
                        <span class="text-[8px] text-neutral-400 uppercase tracking-wider font-bold mt-1 block">Revenue</span>
                    </div>
                </div>
                <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-600 inline-block mr-1.5"></span>Room</span><span class="text-neutral-900 font-mono">68.4%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>F&B</span><span class="text-neutral-900 font-mono">18.8%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-600 inline-block mr-1.5"></span>Other</span><span class="text-neutral-900 font-mono">12.8%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-2">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Summary</h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View Report</a>
            </div>
            <div class="divide-y divide-neutral-50 text-xs font-semibold text-neutral-600 flex-1 flex flex-col justify-between py-1">
                <div class="flex justify-between py-1.5"><span>Today</span><span class="font-mono text-neutral-900">Rp 21.350.000</span></div>
                <div class="flex justify-between py-1.5 bg-neutral-50 px-1 font-bold text-neutral-900"><span>This Week</span><span class="font-mono">Rp 152.450.000</span></div>
                <div class="flex justify-between py-1.5"><span>This Month</span><span class="font-mono text-neutral-900">Rp 652.870.000</span></div>
                <div class="flex justify-between py-1.5"><span>Last Month</span><span class="font-mono text-neutral-900">Rp 598.430.000</span></div>
                <div class="flex justify-between py-1.5 pt-2 border-t font-bold text-neutral-900"><span>This Year (YTD)</span><span class="font-mono text-amber-950">Rp 3.245.750.000</span></div>
            </div>
            <div class="text-[9px] font-bold text-emerald-600 font-mono pt-1 text-right border-t border-neutral-50"><i class="fa-solid fa-arrow-up"></i> +21.6% <span class="text-neutral-400 font-normal font-sans">vs Last Year YTD</span></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mt-8">
        <div class="lg:col-span-3 bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-3">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Recent Transactions</h3>
                <div class="flex items-center gap-2.5 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[200px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" placeholder="Search transaction ID, guest..." class="w-full pl-9 pr-4 py-1.5 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white"><i class="fa-solid fa-filter text-[10px] mr-1 text-neutral-400"></i> Filter</button>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white"><i class="fa-solid fa-download text-[10px] mr-1 text-neutral-400"></i> Export</button>
                </div>
            </div>

            <div class="flex flex-wrap text-[11px] font-bold uppercase tracking-wider text-neutral-400 gap-5 border-b border-neutral-50 pb-1">
                <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2 px-0.5">All Transactions</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5">Payments</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5">Invoices</button>
                <button class="hover:text-neutral-900 pb-2 px-0.5">Refunds</button>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                            <th class="py-3 px-3 font-semibold">Transaction ID</th>
                            <th class="py-3 px-3 font-semibold">Date & Time</th>
                            <th class="py-3 px-3 font-semibold">Type</th>
                            <th class="py-3 px-3 font-semibold">Description</th>
                            <th class="py-3 px-3 font-semibold">Reference</th>
                            <th class="py-3 px-3 font-semibold">Category</th>
                            <th class="py-3 px-3 font-semibold">Amount</th>
                            <th class="py-3 px-3 font-semibold">Payment Method</th>
                            <th class="py-3 px-3 font-semibold">Status</th>
                            <th class="py-3 px-3 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">TRX-250617-0001</td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">17 Jun 2026<span class="block text-[9px] mt-0.5">10:15 AM</span></td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-1.5 py-0.2 font-bold uppercase">Payment</span></td>
                            <td class="py-3.5 px-3 text-neutral-800 font-bold">Room Payment - Booking<span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">#RB-250617-1256</span></td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">INV-250617-0001</td>
                            <td class="py-3.5 px-3">Room Revenue</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 2.450.000</td>
                            <td class="py-3.5 px-3">Credit Card</td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-2 py-0.5 uppercase">Paid</span></td>
                            <td class="py-3.5 px-3 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">TRX-250617-0002</td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">17 Jun 2026<span class="block text-[9px] mt-0.5">11:32 AM</span></td>
                            <td class="py-3.5 px-3"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-1.5 py-0.2 font-bold uppercase">Invoice</span></td>
                            <td class="py-3.5 px-3 text-neutral-800 font-bold">Room Service Order<span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">#RS-250617-0086</span></td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">INV-250617-0002</td>
                            <td class="py-3.5 px-3">Room Service</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 415.000</td>
                            <td class="py-3.5 px-3 text-neutral-400">Charge to Room</td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-2 py-0.5 uppercase">Paid</span></td>
                            <td class="py-3.5 px-3 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">TRX-250617-0003</td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">17 Jun 2026<span class="block text-[9px] mt-0.5">12:05 PM</span></td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-1.5 py-0.2 font-bold uppercase">Payment</span></td>
                            <td class="py-3.5 px-3 text-neutral-800 font-bold">Restaurant Order<span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">#REST-250617-0047</span></td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">INV-250617-0003</td>
                            <td class="py-3.5 px-3">F&B Revenue</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 680.000</td>
                            <td class="py-3.5 px-3">Cash</td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-2 py-0.5 uppercase">Paid</span></td>
                            <td class="py-3.5 px-3 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">TRX-250617-0004</td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">17 Jun 2026<span class="block text-[9px] mt-0.5">02:20 PM</span></td>
                            <td class="py-3.5 px-3"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-1.5 py-0.2 font-bold uppercase">Invoice</span></td>
                            <td class="py-3.5 px-3 text-neutral-800 font-bold">Spa Treatment - Booking<span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">#FW-250617-0077</span></td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">INV-250617-0004</td>
                            <td class="py-3.5 px-3">Facility Revenue</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">Rp 850.000</td>
                            <td class="py-3.5 px-3">Credit Card</td>
                            <td class="py-3.5 px-3"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-2 py-0.5 uppercase">Paid</span></td>
                            <td class="py-3.5 px-3 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors bg-rose-50/10">
                            <td class="py-3.5 px-3 font-mono font-bold text-neutral-900">TRX-250617-0005</td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">17 Jun 2026<span class="block text-[9px] mt-0.5">03:10 PM</span></td>
                            <td class="py-3.5 px-3"><span class="bg-rose-50 text-rose-800 border border-rose-100 text-[8px] px-1.5 py-0.2 font-bold uppercase">Refund</span></td>
                            <td class="py-3.5 px-3 text-red-950 font-bold">Refund - Booking Cancellation<span class="block text-[9px] text-neutral-400 font-mono font-normal mt-0.5">#RB-250616-1122</span></td>
                            <td class="py-3.5 px-3 font-mono text-neutral-500">REF-250617-0001</td>
                            <td class="py-3.5 px-3">Room Revenue</td>
                            <td class="py-3.5 px-3 font-mono font-bold text-rose-700">-Rp 1.200.000</td>
                            <td class="py-3.5 px-3">Bank Transfer</td>
                            <td class="py-3.5 px-3"><span class="bg-rose-50 text-rose-800 text-[8px] font-bold px-2 py-0.5 uppercase">Refunded</span></td>
                            <td class="py-3.5 px-3 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing 1 to 5 of 25 results</span>
                <div class="flex items-center gap-1 font-mono text-neutral-800">
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                    <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">2</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">3</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                </div>
            </div>
        </div>

        <div class="space-y-6 shrink-0 w-full lg:w-auto">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Payment Method Breakdown</h3>
                </div>
                <div class="space-y-3.5 text-xs font-semibold text-neutral-600">
                    <div class="flex justify-between items-center"><span><i class="fa-regular fa-credit-card text-neutral-400 w-5"></i> Credit Card</span><span class="font-mono text-neutral-900">Rp 72.450.000 <span class="text-[9px] text-neutral-400 font-normal">(47.6%)</span></span></div>
                    <div class="flex justify-between items-center"><span><i class="fa-solid fa-money-bill-wave text-neutral-400 w-5"></i> Cash</span><span class="font-mono text-neutral-900">Rp 35.120.000 <span class="text-[9px] text-neutral-400 font-normal">(23.0%)</span></span></div>
                    <div class="flex justify-between items-center"><span><i class="fa-solid fa-building-columns text-neutral-400 w-5"></i> Bank Transfer</span><span class="font-mono text-neutral-900">Rp 28.370.000 <span class="text-[9px] text-neutral-400 font-normal">(18.6%)</span></span></div>
                    <div class="flex justify-between items-center"><span><i class="fa-solid fa-hotel text-neutral-400 w-5"></i> Charge to Room</span><span class="font-mono text-neutral-900">Rp 16.510.000 <span class="text-[9px] text-neutral-400 font-normal">(10.8%)</span></span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                @if(auth()->user()->role !== 'manager')
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-file-circle-plus"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">New Invoice</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-cash-register"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Record Pmt</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-rotate-left"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Process Rfd</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-print"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Financial Rpt</span>
                        </button>
                    </div>
                @else
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2"><i class="fa-solid fa-vault text-amber-700 mr-1"></i> Audit Desk</h3>
                    <div class="grid grid-cols-1 gap-2 text-[10px] font-bold uppercase tracking-wider">
                        <button class="w-full bg-neutral-950 hover:bg-neutral-900 text-white p-3 flex items-center justify-center gap-2 cursor-pointer transition-colors"><i class="fa-solid fa-file-excel"></i> Export General Ledger (XLS)</button>
                        <button class="w-full bg-white border border-neutral-200 hover:border-neutral-900 text-neutral-800 p-3 flex items-center justify-center gap-2 cursor-pointer transition-all"><i class="fa-solid fa-calculator"></i> Tax Compliance Sheet</button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Expense Overview</h3>
                    <span class="text-[9px] text-red-600 block font-bold mt-0.5"><i class="fa-solid fa-arrow-down text-[8px]"></i> -6.8% <span class="text-neutral-400 font-normal">vs last week</span></span>
                </div>
                <span class="text-lg font-bold font-mono text-neutral-900">Rp 48.230.000</span>
            </div>
            
            <div class="space-y-4 flex-1 flex flex-col justify-center">
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Payroll & Staff Salaries</span><span class="font-mono text-neutral-900">Rp 21.450.000 <span class="text-[9px] text-neutral-400 font-normal">(44.5%)</span></span></div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden"><div class="h-full bg-neutral-900" style="width: 44.5%"></div></div>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Property Utilities (Water/Electricity)</span><span class="font-mono text-neutral-900">Rp 8.970.000 <span class="text-[9px] text-neutral-400 font-normal">(18.6%)</span></span></div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden"><div class="h-full bg-neutral-900" style="width: 18.6%"></div></div>
                </div>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs font-semibold text-neutral-700"><span>Hotel Maintenance & Repairs</span><span class="font-mono text-neutral-900">Rp 6.320.000 <span class="text-[9px] text-neutral-400 font-normal">(13.1%)</span></span></div>
                    <div class="w-full h-1.5 bg-neutral-100 rounded-none overflow-hidden"><div class="h-full bg-neutral-900" style="width: 13.1%"></div></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Expense by Category</h3>
            </div>
            <div class="flex items-center gap-4 my-auto">
                <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="13.1 86.9" stroke-dashoffset="-63.1"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="18.6 81.4" stroke-dashoffset="-44.5"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="44.5 55.5" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xs font-bold text-neutral-900 font-mono block leading-none">Rp 48.2M</span>
                        <span class="text-[7px] text-neutral-400 uppercase tracking-wider font-bold mt-1 block">Spent</span>
                    </div>
                </div>
                <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Payroll</span><span class="text-neutral-800 font-mono">44.5%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>Utilities</span><span class="text-neutral-800 font-mono">18.6%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Maint.</span><span class="text-neutral-800 font-mono">13.1%</span></div>
                </div>
            </div>
        </div>
    </div>

</x-admin-dashboard-layout>