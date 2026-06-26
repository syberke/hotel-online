<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">48</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 16.7%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-600">15</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 7.1%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Completed Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">31</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 24.0%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-lg font-bold text-neutral-900">Rp 18.750.000</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 19.3%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Avg Order Value</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-sm font-bold text-neutral-900 font-mono">Rp 390.625</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 2.8%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 pb-3">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5">Order Management</button>
                    <button class="hover:text-neutral-900 transition-colors pb-1.5 px-0.5">Table Overview</button>
                    <button class="hover:text-neutral-900 transition-colors pb-1.5 px-0.5">Today's Menu</button>
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
                    <div class="flex flex-wrap text-[11px] font-bold uppercase tracking-wider text-neutral-400 gap-4">
                        <button class="text-neutral-900 bg-neutral-100 px-3 py-1.5 rounded-none font-bold">All Orders (48)</button>
                        <button class="hover:text-neutral-900 transition-colors px-3 py-1.5">Dine In (20)</button>
                        <button class="hover:text-neutral-900 transition-colors px-3 py-1.5">Room Service (18)</button>
                        <button class="hover:text-neutral-900 transition-colors px-3 py-1.5">Take Away (10)</button>
                    </div>

                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative flex-1 lg:flex-none lg:min-w-[200px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" placeholder="Search by order ID, guest, table..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 flex items-center gap-1.5 bg-white"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                                <th class="py-3 px-4 font-semibold">Order ID</th>
                                <th class="py-3 px-4 font-semibold">Type</th>
                                <th class="py-3 px-4 font-semibold">Guest / Table</th>
                                <th class="py-3 px-4 font-semibold">Order Time</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Total Amount</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">REST-250617-0048</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500 text-xs"><i class="fa-solid fa-chair text-neutral-400 mr-1.5 text-[10px]"></i> Dine In</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block">Table 12</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">2 Guests</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">12:30 PM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Preparing</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 425.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">REST-250617-0047</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500 text-xs"><i class="fa-solid fa-bowl-food text-neutral-400 mr-1.5 text-[10px]"></i> Room Service</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block">David Thompson</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Room 1205</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">12:15 PM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">In Progress</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 680.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">REST-250617-0045</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500 text-xs"><i class="fa-solid fa-bag-shopping text-neutral-400 mr-1.5 text-[10px]"></i> Take Away</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block">Walk-in Customer</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">+62 812 3456 7890</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">11:50 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2.5 py-0.5 font-bold uppercase tracking-wide">Ready</span>
                                </td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 210.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing 1 to 3 of 48 results</span>
                    <div class="flex items-center gap-1 font-mono text-neutral-800">
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">3</button>
                        <span class="px-0.5 text-neutral-300">...</span>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">6</button>
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Overview</h3>
                        <span class="text-[9px] text-emerald-600 block font-bold mt-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 19.3% <span class="text-neutral-400 font-normal">vs last week</span></span>
                    </div>
                    <span class="text-[9px] bg-neutral-50 border border-neutral-200 px-2.5 py-1 text-neutral-500 font-mono font-bold uppercase tracking-wider">This Week <i class="fa-solid fa-chevron-down ml-1"></i></span>
                </div>
                
                <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f3f4f6" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f3f4f6" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f3f4f6" stroke-width="1" />
                        <path d="M 0,110 L 100,60 L 200,90 L 300,50 L 400,80 L 500,40 L 600,25 L 600,140 L 0,140 Z" fill="#fef3c7" fill-opacity="0.3"/>
                        <path d="M 0,110 L 100,60 L 200,90 L 300,50 L 400,80 L 500,40 L 600,25" fill="none" stroke="#d97706" stroke-width="2.5" />
                        <circle cx="300" cy="50" r="4" fill="#d97706" />
                        <circle cx="500" cy="40" r="4" fill="#d97706" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                        <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span><span>21 Jun</span><span>22 Jun</span><span>23 Jun</span>
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-80 space-y-6 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Order Status Overview</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View Report</a>
                </div>
                
                <div class="flex items-center gap-4 my-2">
                    <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                        <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4" stroke-dasharray="4.2 95.8" stroke-dashoffset="0"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4" stroke-dasharray="16.7 83.3" stroke-dashoffset="-4.2"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#d97706" stroke-width="4" stroke-dasharray="31.3 68.7" stroke-dashoffset="-20.9"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4" stroke-dasharray="64.6 35.4" stroke-dashoffset="-52.2"></circle>
                        </svg>
                        <div class="absolute text-center">
                            <span class="text-xl font-light font-serif text-neutral-900 block leading-none">48</span>
                            <span class="text-[8px] text-neutral-400 uppercase tracking-wider font-bold mt-0.5 block">Orders</span>
                        </div>
                    </div>
                    <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Completed</span><span class="text-neutral-800 font-mono">31</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>In Progress</span><span class="text-neutral-800 font-mono">15</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-500 inline-block mr-1.5"></span>Preparing</span><span class="text-neutral-800 font-mono">8</span></div>
                        <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>Cancelled</span><span class="text-neutral-800 font-mono">2</span></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Top Selling Items</h3>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All</a>
                </div>
                
                <div class="space-y-3.5 flex-1">
                    <div class="flex items-center justify-between text-xs font-semibold text-neutral-700">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Grilled Salmon Medallion</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">32 Orders</span></div>
                        </div>
                        <span class="font-mono text-neutral-900">Rp 5.120.000</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-neutral-700">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1544025162-d76694265947?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Wagyu Beef Tenderloin</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">28 Orders</span></div>
                        </div>
                        <span class="font-mono text-neutral-900">Rp 4.480.000</span>
                    </div>
                    <div class="flex items-center justify-between text-xs font-semibold text-neutral-700">
                        <div class="flex items-center gap-3">
                            <img src="https://images.unsplash.com/photo-1550304943-4f24f54ddde9?q=80&w=100" class="w-8 h-8 object-cover border">
                            <div><span>Classic Caesar Salad</span><span class="block text-[9px] text-neutral-400 font-normal mt-0.5">26 Orders</span></div>
                        </div>
                        <span class="font-mono text-neutral-900">Rp 2.340.000</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                @if(auth()->user()->role !== 'manager')
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2">Quick Actions</h3>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-plus"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">New Order</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-chair"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Table Resv</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-utensils"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Menu Mgmt</span>
                        </button>
                        <button class="bg-neutral-50 border border-neutral-200 hover:border-neutral-900 p-4 flex flex-col justify-center items-center gap-2 group cursor-pointer transition-all">
                            <div class="text-neutral-700 group-hover:text-amber-800 text-sm"><i class="fa-solid fa-print"></i></div>
                            <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-800">Print KOT</span>
                        </button>
                    </div>
                @else
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide border-b border-neutral-100 pb-2"><i class="fa-solid fa-chart-pie text-amber-700 mr-1"></i> Menu Analytics</h3>
                    <div class="grid grid-cols-1 gap-2 text-[10px] font-bold uppercase tracking-wider">
                        <button class="w-full bg-neutral-950 hover:bg-neutral-900 text-white p-3 flex items-center justify-center gap-2 cursor-pointer transition-colors"><i class="fa-solid fa-file-pdf"></i> Export Gastronomy P&L</button>
                        <button class="w-full bg-white border border-neutral-200 hover:border-neutral-900 text-neutral-800 p-3 flex items-center justify-center gap-2 cursor-pointer transition-all"><i class="fa-solid fa-wheat-awn"></i> Food Cost Percentage Sheet</button>
                    </div>
                @endif
            </div>
        </aside>

    </div>

</x-admin-dashboard-layout>