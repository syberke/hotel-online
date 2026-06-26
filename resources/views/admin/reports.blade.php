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
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate (Avg)</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">64.3%</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 8.2% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">128</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 16.4% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">236</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 12.1% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">ADR (Average Daily Rate)</span>
            <div class="mt-2">
                <span class="text-base font-bold text-neutral-900 block font-mono">Rp 1.125.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 11.3% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">RevPAR</span>
            <div class="mt-2">
                <span class="text-base font-bold text-neutral-900 block font-mono">Rp 722.175</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 20.2% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 mt-8">
        <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-5">
            <button class="text-neutral-900 border-b-2 border-neutral-900 pb-1 px-0.5">Overview</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Revenue</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Occupancy</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Bookings</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Guests</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">F&B</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Room Service</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Facilities</button>
            <button class="hover:text-neutral-900 transition-colors pb-1 px-0.5">Finance</button>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <button class="border border-neutral-200 hover:bg-neutral-50 px-3.5 py-1.5 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1.5"><i class="fa-solid fa-filter text-[11px] text-neutral-400"></i> Filters</button>
            
            @if(auth()->user()->role !== 'manager')
                <button class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-wider px-4 py-1.5 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-wand-magic-sparkles text-[11px]"></i> Custom Report</button>
            @else
                <button class="bg-neutral-100 border border-neutral-200 text-neutral-400 font-bold text-xs uppercase tracking-wider px-4 py-1.5 flex items-center gap-1.5 transition-colors shadow-none cursor-not-allowed" title="Report Compilation Locked (Read-Only Mode)"><i class="fa-solid fa-lock text-[11px]"></i> Custom Report</button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        
        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Trend</h3>
                <div class="flex items-center gap-3 text-[9px] font-mono font-bold uppercase text-neutral-400">
                    <span class="flex items-center gap-1"><span class="w-2 h-0.5 bg-emerald-600 inline-block"></span> This Week</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-0.5 bg-neutral-300 inline-block"></span> Last Week</span>
                    <span class="bg-neutral-50 border px-1.5 py-0.5 text-neutral-500 font-bold ml-1">Daily <i class="fa-solid fa-chevron-down text-[8px] ml-0.5"></i></span>
                </div>
            </div>
            
            <div class="relative w-full h-40 flex flex-col justify-between pt-2">
                <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                    <path d="M 0,95 L 100,65 L 200,100 L 300,80 L 400,100 L 500,60 L 600,75" fill="none" stroke="#d4d4d8" stroke-width="1.5" stroke-dasharray="4" />
                    <path d="M 0,85 L 100,40 L 200,80 L 300,65 L 400,90 L 500,45 L 600,60" fill="none" stroke="#059669" stroke-width="2.5" />
                    <circle cx="100" cy="40" r="3.5" fill="#059669" />
                    <circle cx="500" cy="45" r="3.5" fill="#059669" />
                </svg>
                <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                    <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span><span>21 Jun</span><span>22 Jun</span><span>23 Jun</span>
                </div>
            </div>

            <div class="mt-4 pt-3 border-t border-neutral-100 grid grid-cols-3 text-center">
                <div class="text-left"><span class="text-[9px] text-neutral-400 uppercase tracking-wider block font-bold">This Week (17 - 23 Jun)</span><span class="text-xs font-bold text-neutral-900 font-mono mt-0.5 block">Rp 152.450.000</span></div>
                <div><span class="text-[9px] text-neutral-400 uppercase tracking-wider block font-bold">Last Week (10 - 16 Jun)</span><span class="text-xs font-bold text-neutral-500 font-mono mt-0.5 block">Rp 128.690.000</span></div>
                <div class="text-right"><span class="text-[9px] text-neutral-400 uppercase tracking-wider block font-bold">Change</span><span class="text-xs font-bold text-emerald-600 font-mono mt-0.5 block"><i class="fa-solid fa-arrow-up text-[9px]"></i> 18.7%</span></div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue by Department</h3>
            </div>
            
            <div class="flex items-center gap-5 my-auto">
                <div class="relative w-28 h-28 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#6b7280" stroke-width="4.5" stroke-dasharray="3 97" stroke-dashoffset="-97"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#a855f7" stroke-width="4.5" stroke-dasharray="4.1 95.9" stroke-dashoffset="-92.9"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="5.7 94.3" stroke-dashoffset="-87.2"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#f59e0b" stroke-width="4.5" stroke-dasharray="18.8 81.2" stroke-dashoffset="-68.4"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="68.4 31.6" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-base font-medium text-neutral-900 font-mono block leading-none">Rp 152.4M</span>
                        <span class="text-[8px] text-neutral-400 uppercase tracking-wider font-bold mt-1 block">Total Revenue</span>
                    </div>
                </div>
                <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Room Revenue</span><span class="text-neutral-900 font-mono">68.4%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-500 inline-block mr-1.5"></span>F&B Revenue</span><span class="text-neutral-900 font-mono">18.8%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>Room Service</span><span class="text-neutral-900 font-mono">5.7%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-purple-500 inline-block mr-1.5"></span>Facilities Revenue</span><span class="text-neutral-900 font-mono">4.1%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-gray-500 inline-block mr-1.5"></span>Other Revenue</span><span class="text-neutral-900 font-mono">3.0%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Occupancy Rate</h3>
                <span class="text-[9px] bg-neutral-50 border border-neutral-200 px-1.5 py-0.5 text-neutral-500 font-mono font-bold uppercase">Daily <i class="fa-solid fa-chevron-down text-[8px] ml-0.5"></i></span>
            </div>
            
            <div class="h-40 flex items-end justify-between px-2 relative border-b border-neutral-100 pb-1">
                <div class="absolute inset-x-0 bottom-1/4 border-b border-neutral-50 border-dashed pointer-events-none"></div>
                <div class="absolute inset-x-0 bottom-2/4 border-b border-neutral-50 border-dashed pointer-events-none"></div>
                <div class="absolute inset-x-0 bottom-3/4 border-b border-neutral-50 border-dashed pointer-events-none"></div>
                
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">62.1%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 62px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">68.7%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 68px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">61.3%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 61px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">57.9%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 57px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">71.4%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 71px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">66.2%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 66px"></div></div>
                <div class="flex flex-col items-center gap-1.5 w-8 group"><span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">62.4%</span><div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: 62px"></div></div>
            </div>
            <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-1.5">
                <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span><span>21 Jun</span><span>22 Jun</span><span>23 Jun</span>
            </div>

            <div class="mt-3 pt-2.5 border-t border-neutral-100 flex justify-between items-center text-[10px] font-semibold text-neutral-500">
                <div><span>This Week (Avg):</span> <span class="text-neutral-900 font-mono font-bold ml-0.5">64.3%</span></div>
                <div><span>Last Week (Avg):</span> <span class="text-neutral-500 font-mono ml-0.5">56.1%</span></div>
                <div><span>Change:</span> <span class="text-emerald-600 font-mono font-bold ml-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> +8.2%</span></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <div class="lg:col-span-2 bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Top 5 Room Types by Revenue</h3>
                </div>
            </div>
            
            <table class="w-full text-left text-xs flex-1 whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] pb-2">
                        <th class="pb-3 font-semibold">No.</th>
                        <th class="pb-3 font-semibold">Room Type</th>
                        <th class="pb-3 font-semibold">Rooms Sold</th>
                        <th class="pb-3 font-semibold">Room Revenue</th>
                        <th class="pb-3 text-right font-semibold">% of Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    <tr class="hover:bg-neutral-50/40 transition-colors">
                        <td class="py-3 font-mono">1</td>
                        <td class="py-3 text-neutral-900 font-bold">Deluxe Ocean View</td>
                        <td>45</td>
                        <td class="font-mono text-neutral-900 font-bold">Rp 45.000.000</td>
                        <td class="text-right font-mono text-neutral-400">43.2%</td>
                    </tr>
                    <tr class="hover:bg-neutral-50/40 transition-colors">
                        <td class="py-3 font-mono">2</td>
                        <td class="py-3 text-neutral-900 font-bold">Premier Suite</td>
                        <td>28</td>
                        <td class="font-mono text-neutral-900 font-bold">Rp 28.000.000</td>
                        <td class="text-right font-mono text-neutral-400">26.9%</td>
                    </tr>
                    <tr class="hover:bg-neutral-50/40 transition-colors">
                        <td class="py-3 font-mono">3</td>
                        <td class="py-3 text-neutral-900 font-bold">Executive Suite</td>
                        <td>18</td>
                        <td class="font-mono text-neutral-900 font-bold">Rp 18.900.000</td>
                        <td class="text-right font-mono text-neutral-400">18.1%</td>
                    </tr>
                    <tr class="hover:bg-neutral-50/40 transition-colors">
                        <td class="py-3 font-mono">4</td>
                        <td class="py-3 text-neutral-900 font-bold">Deluxe Room</td>
                        <td>12</td>
                        <td class="font-mono text-neutral-900 font-bold">Rp 8.400.000</td>
                        <td class="text-right font-mono text-neutral-400">8.1%</td>
                    </tr>
                    <tr class="hover:bg-neutral-50/40 transition-colors">
                        <td class="py-3 font-mono">5</td>
                        <td class="py-3 text-neutral-900 font-bold">Superior Room</td>
                        <td>7</td>
                        <td class="font-mono text-neutral-900 font-bold">Rp 3.950.000</td>
                        <td class="text-right font-mono text-neutral-400">3.7%</td>
                    </tr>
                </tbody>
            </table>
            
            <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline block pt-3 border-t text-right">View Full Report &rarr;</a>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3.5 mb-3.5">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Quick Reports</h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View All Types</a>
            </div>
            
            <div class="space-y-2.5 flex-1 flex flex-col justify-between">
                <a href="#" class="flex items-center justify-between p-2 hover:bg-neutral-50/70 border border-neutral-100/50 group transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-neutral-50 group-hover:bg-white border border-neutral-100 text-neutral-400 text-xs flex items-center justify-center"><i class="fa-regular fa-file-lines text-emerald-700"></i></div>
                        <div><span class="text-xs font-bold text-neutral-900 block">Daily Report</span><span class="text-[9px] text-neutral-400 block mt-0.5">Summary of daily operations and revenue</span></div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-neutral-300 group-hover:text-neutral-900 transition-colors"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-2 hover:bg-neutral-50/70 border border-neutral-100/50 group transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-neutral-50 group-hover:bg-white border border-neutral-100 text-neutral-400 text-xs flex items-center justify-center"><i class="fa-solid fa-bed text-blue-700"></i></div>
                        <div><span class="text-xs font-bold text-neutral-900 block">Occupancy Report</span><span class="text-[9px] text-neutral-400 block mt-0.5">Room occupancy and availability analysis</span></div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-neutral-300 group-hover:text-neutral-900 transition-colors"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-2 hover:bg-neutral-50/70 border border-neutral-100/50 group transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-neutral-50 group-hover:bg-white border border-neutral-100 text-neutral-400 text-xs flex items-center justify-center"><i class="fa-solid fa-chart-pie text-amber-700"></i></div>
                        <div><span class="text-xs font-bold text-neutral-900 block">Revenue Report</span><span class="text-[9px] text-neutral-400 block mt-0.5">Detailed revenue breakdown and analysis</span></div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-neutral-300 group-hover:text-neutral-900 transition-colors"></i>
                </a>
                <a href="#" class="flex items-center justify-between p-2 hover:bg-neutral-50/70 border border-neutral-100/50 group transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-neutral-50 group-hover:bg-white border border-neutral-100 text-neutral-400 text-xs flex items-center justify-center"><i class="fa-solid fa-utensils text-purple-700"></i></div>
                        <div><span class="text-xs font-bold text-neutral-900 block">F&B Report</span><span class="text-[9px] text-neutral-400 block mt-0.5">Restaurant and bar sales report</span></div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[10px] text-neutral-300 group-hover:text-neutral-900 transition-colors"></i>
                </a>
            </div>
        </div>
    </div>

</x-admin-dashboard-layout>