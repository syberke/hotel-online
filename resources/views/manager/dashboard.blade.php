<x-manager-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">64.3%</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 8.2% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">ADR (Average Daily Rate)</span>
            <div class="mt-2">
                <span class="text-base font-bold text-neutral-900 block font-mono">Rp 1.125.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 11.3% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">RevPAR (Revenue Per Room)</span>
            <div class="mt-2">
                <span class="text-base font-bold text-neutral-900 block font-mono">Rp 722.175</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 20.2% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Revenue (This Week)</span>
            <div class="mt-2">
                <span class="text-base font-bold text-neutral-900 block font-mono">Rp 152.450.000</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 18.7% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings (This Week)</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">128</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 16.4% <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>

        <div class="bg-white p-4 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Guest Satisfaction (GSS)</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">4.7 / 5.0</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-arrow-up text-[8px]"></i> 0.2 <span class="text-neutral-400 font-normal">vs last week</span>
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        
        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Performance Overview</h3>
                <div class="flex items-center gap-2 text-[8px] font-mono font-bold uppercase text-neutral-400">
                    <span class="flex items-center gap-1"><span class="w-2 h-0.5 bg-blue-600 inline-block"></span> This Week</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-0.5 bg-neutral-300 inline-block"></span> Last Week</span>
                    <span class="bg-neutral-50 border px-1 py-0.5 text-neutral-500 font-bold ml-1">Daily <i class="fa-solid fa-chevron-down text-[7px] ml-0.5"></i></span>
                </div>
            </div>
            
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-full h-36 flex flex-col justify-between pt-1">
                    <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                        <path d="M 0,95 L 100,65 L 200,100 L 300,80 L 400,100 L 500,60 L 600,75" fill="none" stroke="#d4d4d8" stroke-width="1.5" stroke-dasharray="4" />
                        <path d="M 0,85 L 100,40 L 200,80 L 300,65 L 400,90 L 500,45 L 600,60" fill="none" stroke="#2563eb" stroke-width="2.5" />
                        <circle cx="100" cy="40" r="3" fill="#2563eb" />
                        <circle cx="500" cy="45" r="3" fill="#2563eb" />
                    </svg>
                    <div class="flex justify-between text-[8px] text-neutral-400 font-mono font-bold pt-1.5 border-t border-neutral-100">
                        <span>17 Jun</span><span>18 Jun</span><span>19 Jun</span><span>20 Jun</span><span>21 Jun</span><span>22 Jun</span><span>23 Jun</span>
                    </div>
                </div>
                
                <div class="w-40 border-l border-neutral-100 pl-4 space-y-2.5 shrink-0">
                    <div><span class="text-[8px] text-neutral-400 font-bold uppercase tracking-wider block">Revenue (This Week)</span><span class="text-xs font-bold font-mono text-neutral-900 block mt-0.5">Rp 152.450.000</span><span class="text-[8px] text-emerald-600 font-bold block mt-0.5"><i class="fa-solid fa-arrow-up"></i> 18.7%</span></div>
                    <div><span class="text-[8px] text-neutral-400 font-bold uppercase tracking-wider block">Revenue (This Month)</span><span class="text-xs font-bold font-mono text-neutral-900 block mt-0.5">Rp 652.870.000</span><span class="text-[8px] text-emerald-600 font-bold block mt-0.5"><i class="fa-solid fa-arrow-up"></i> 12.5%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Today's Snapshot</h3>
            </div>
            
            <div class="grid grid-cols-2 gap-y-2.5 gap-x-4 text-[11px] font-semibold text-neutral-600 flex-1 my-auto">
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-right-to-bracket text-neutral-400 text-xs w-4"></i> Check-in</span>
                    <span class="font-mono text-neutral-900 font-bold">18</span>
                </div>
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-right-from-bracket text-neutral-400 text-xs w-4"></i> Check-out</span>
                    <span class="font-mono text-neutral-900 font-bold">22</span>
                </div>
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-users text-neutral-400 text-xs w-4"></i> In House Guests</span>
                    <span class="font-mono text-neutral-900 font-bold">186</span>
                </div>
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-regular fa-calendar-check text-neutral-400 text-xs w-4"></i> Arrivals</span>
                    <span class="font-mono text-neutral-900 font-bold">24</span>
                </div>
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-plane-departure text-neutral-400 text-xs w-4"></i> Departures</span>
                    <span class="font-mono text-neutral-900 font-bold">26</span>
                </div>
                <div class="flex items-center justify-between border-b border-neutral-50 pb-1.5">
                    <span class="flex items-center gap-2"><i class="fa-solid fa-broom text-neutral-400 text-xs w-4"></i> Housekeeping (To Do)</span>
                    <span class="font-mono text-neutral-900 font-bold">34</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Room Status</h3>
            </div>
            
            <div class="flex items-center gap-4 flex-1 my-auto">
                <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#b45309" stroke-width="4.5" stroke-dasharray="4 96" stroke-dashoffset="-96"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="6 94" stroke-dashoffset="-90"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#dc2626" stroke-width="4.5" stroke-dasharray="6 94" stroke-dashoffset="-84"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#10b981" stroke-width="4.5" stroke-dasharray="20 80" stroke-dashoffset="-64"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="64 36" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-sm font-bold text-neutral-900 font-mono block">250</span>
                        <span class="text-[7px] text-neutral-400 uppercase tracking-wider font-bold block mt-0.5">Rooms</span>
                    </div>
                </div>
                
                <div class="space-y-1 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>Occupied</span><span class="text-neutral-900 font-mono">160 (64%)</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-emerald-500 inline-block mr-1.5"></span>Vacant Clean</span><span class="text-neutral-900 font-mono">50 (20%)</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-600 inline-block mr-1.5"></span>Vacant Dirty</span><span class="text-neutral-900 font-mono">15 (6%)</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-400 inline-block mr-1.5"></span>Out of Order</span><span class="text-neutral-900 font-mono">15 (6%)</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-amber-700 inline-block mr-1.5"></span>Reserved</span><span class="text-neutral-900 font-mono">10 (4%)</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Revenue by Department <span class="text-neutral-400 font-sans font-normal text-[10px]">(This Week)</span></h3>
            </div>
            
            <div class="flex items-center gap-4 flex-1 my-auto">
                <div class="relative w-24 h-24 shrink-0 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90">
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#9ca3af" stroke-width="4.5" stroke-dasharray="12.8 87.2" stroke-dashoffset="-87.2"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#ef4444" stroke-width="4.5" stroke-dasharray="18.8 81.2" stroke-dashoffset="-68.4"></circle>
                        <circle cx="18" cy="18" r="15.915" fill="transparent" stroke="#2563eb" stroke-width="4.5" stroke-dasharray="68.4 31.6" stroke-dashoffset="0"></circle>
                    </svg>
                    <div class="absolute text-center">
                        <span class="text-xs font-bold text-neutral-900 font-mono block">Rp 152.4M</span>
                    </div>
                </div>
                
                <div class="space-y-1.5 w-full text-[10px] font-semibold text-neutral-500">
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-blue-600 inline-block mr-1.5"></span>Room Revenue</span><span class="text-neutral-900 font-mono">68.4%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-red-500 inline-block mr-1.5"></span>F&B Revenue</span><span class="text-neutral-900 font-mono">18.8%</span></div>
                    <div class="flex justify-between items-center"><span><span class="w-1.5 h-1.5 bg-gray-400 inline-block mr-1.5"></span>Other Revenue</span><span class="text-neutral-900 font-mono">12.8%</span></div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Top 5 Room Types by Occupancy</h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View Report</a>
            </div>
            
            <table class="w-full text-left text-[11px] font-medium text-neutral-600 flex-1 whitespace-nowrap">
                <thead>
                    <tr class="text-neutral-400 uppercase tracking-wider font-bold text-[8px] border-b border-neutral-50 pb-1.5">
                        <th class="pb-1 font-semibold">Room Type</th>
                        <th class="pb-1 font-semibold text-center">Occupancy</th>
                        <th class="pb-1 text-right font-semibold">ADR</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50/50">
                    <tr><td class="py-1.5 font-bold text-neutral-900">Deluxe Ocean View</td><td class="text-center font-mono">78.4%</td><td class="text-right font-mono text-neutral-900">Rp 1.450.000</td></tr>
                    <tr><td class="py-1.5 font-bold text-neutral-900">Premier Suite</td><td class="text-center font-mono">72.1%</td><td class="text-right font-mono text-neutral-900">Rp 2.350.000</td></tr>
                    <tr><td class="py-1.5 font-bold text-neutral-900">Executive Suite</td><td class="text-center font-mono">65.3%</td><td class="text-right font-mono text-neutral-900">Rp 1.950.000</td></tr>
                    <tr><td class="py-1.5 font-bold text-neutral-900">Deluxe Room</td><td class="text-center font-mono">62.8%</td><td class="text-right font-mono text-neutral-900">Rp 1.150.000</td></tr>
                    <tr><td class="py-1.5 font-bold text-neutral-900">Superior Room</td><td class="text-center font-mono">58.7%</td><td class="text-right font-mono text-neutral-900">Rp 950.000</td></tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-2">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Guest Feedback <span class="text-neutral-400 font-sans font-normal text-[10px]">(This Week)</span></h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View All</a>
            </div>
            
            <div class="space-y-2 flex-1 flex flex-col justify-center">
                <div class="flex items-center justify-between text-xs font-bold text-neutral-900">
                    <span class="font-serif text-lg">4.7 <span class="text-xs text-neutral-400 font-sans font-normal">/ 5.0</span></span>
                    <div class="text-amber-500 text-[10px] flex gap-0.5"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i></div>
                </div>
                <div class="space-y-1 text-[10px] font-semibold text-neutral-500 font-mono">
                    <div class="flex items-center justify-between gap-3"><span>Cleanliness</span><div class="flex-1 h-1 bg-neutral-100 rounded-none overflow-hidden"><div class="bg-blue-600 h-full" style="width: 96%"></div></div><span>4.8</span></div>
                    <div class="flex items-center justify-between gap-3"><span>Service</span><div class="flex-1 h-1 bg-neutral-100 rounded-none overflow-hidden"><div class="bg-blue-600 h-full" style="width: 94%"></div></div><span>4.7</span></div>
                    <div class="flex items-center justify-between gap-3"><span>Location</span><div class="flex-1 h-1 bg-neutral-100 rounded-none overflow-hidden"><div class="bg-blue-600 h-full" style="width: 92%"></div></div><span>4.6</span></div>
                    <div class="flex items-center justify-between gap-3"><span>Amenities</span><div class="flex-1 h-1 bg-neutral-100 rounded-none overflow-hidden"><div class="bg-blue-600 h-full" style="width: 92%"></div></div><span>4.6</span></div>
                    <div class="flex items-center justify-between gap-3"><span>Value for Money</span><div class="flex-1 h-1 bg-neutral-100 rounded-none overflow-hidden"><div class="bg-blue-600 h-full" style="width: 90%"></div></div><span>4.5</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        
        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Operational Summary</h3>
            </div>
            
            <table class="w-full text-left text-[11px] font-medium text-neutral-600 flex-1 whitespace-nowrap">
                <thead>
                    <tr class="text-neutral-400 uppercase tracking-wider font-bold text-[8px] border-b border-neutral-50 pb-1.5">
                        <th class="pb-1 font-semibold">Department</th>
                        <th class="pb-1 font-semibold">Status</th>
                        <th class="pb-1 text-right font-semibold">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-50/50">
                    <tr><td class="py-2 text-neutral-900 font-bold">Front Desk</td><td><span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] px-1 py-0.2 uppercase font-bold tracking-wide">Good</span></td><td class="text-right font-mono text-neutral-500">92%</td></tr>
                    <tr><td class="py-2 text-neutral-900 font-bold">Housekeeping</td><td><span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] px-1 py-0.2 uppercase font-bold tracking-wide">Attention</span></td><td class="text-right font-mono text-neutral-500">78%</td></tr>
                    <tr><td class="py-2 text-neutral-900 font-bold">Maintenance</td><td><span class="bg-amber-50 text-amber-800 border border-amber-200 text-[8px] px-1 py-0.2 uppercase font-bold tracking-wide">Attention</span></td><td class="text-right font-mono text-neutral-500">85%</td></tr>
                    <tr><td class="py-2 text-neutral-900 font-bold">F&B / Restaurant</td><td><span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] px-1 py-0.2 uppercase font-bold tracking-wide">Good</span></td><td class="text-right font-mono text-neutral-500">90%</td></tr>
                    <tr><td class="py-2 text-neutral-900 font-bold">Spa & Wellness</td><td><span class="bg-emerald-50 text-emerald-800 border border-emerald-200 text-[8px] px-1 py-0.2 uppercase font-bold tracking-wide">Good</span></td><td class="text-right font-mono text-neutral-500">88%</td></tr>
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-3">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Revenue Forecast <span class="text-neutral-400 font-sans font-normal text-[10px]">(This Month)</span></h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View Forecast</a>
            </div>
            
            <div class="relative w-full h-32 flex flex-col justify-between pt-1">
                <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                    <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                    <line x1="0" y1="70" x2="600" y2="70" stroke="#f4f4f5" stroke-width="1" />
                    <path d="M 0,110 L 150,95 L 300,105 L 450,75 L 600,45" fill="none" stroke="#2563eb" stroke-width="2.5" />
                    <circle cx="600" cy="45" r="3.5" fill="#2563eb" />
                </svg>
                <div class="flex justify-between text-[8px] text-neutral-400 font-mono font-bold pt-1.5 border-t border-neutral-100">
                    <span>Week 1</span><span>Week 2</span><span>Week 3</span><span>Week 4</span><span>Week 5</span>
                </div>
            </div>
            
            <div class="mt-2 text-[10px] font-semibold text-neutral-500 flex justify-between items-center">
                <span>Forecast: <span class="text-neutral-900 font-mono font-bold">Rp 2.650.000.000</span></span>
                <span class="text-emerald-600 font-mono font-bold"><i class="fa-solid fa-arrow-up"></i> +12.5% vs last month</span>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-2 mb-2">
                <h3 class="font-serif text-xs text-neutral-900 font-medium tracking-wide">Manager Alerts</h3>
                <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-wider hover:underline">View All</a>
            </div>
            
            <div class="space-y-2 flex-1 flex flex-col justify-between text-[11px] font-medium">
                <div class="flex items-start gap-2.5 p-1.5 hover:bg-neutral-50 transition-colors">
                    <i class="fa-solid fa-circle-exclamation text-amber-600 mt-0.5 text-xs"></i>
                    <div class="flex-1"><span class="text-neutral-900 font-bold block">5 rooms are out of order</span><span class="text-[9px] text-neutral-400 block mt-0.5">Require immediate attention</span></div>
                    <span class="text-[8px] text-neutral-400 font-mono">10 min ago</span>
                </div>
                <div class="flex items-start gap-2.5 p-1.5 hover:bg-neutral-50 transition-colors">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5 text-xs"></i>
                    <div class="flex-1"><span class="text-neutral-900 font-bold block">High housekeeping workload</span><span class="text-[9px] text-neutral-400 block mt-0.5">34 rooms pending cleaning</span></div>
                    <span class="text-[8px] text-neutral-400 font-mono">30 min ago</span>
                </div>
                <div class="flex items-start gap-2.5 p-1.5 hover:bg-neutral-50 transition-colors">
                    <i class="fa-solid fa-circle-check text-emerald-600 mt-0.5 text-xs"></i>
                    <div class="flex-1"><span class="text-neutral-900 font-bold block">Daily report available</span><span class="text-[9px] text-neutral-400 block mt-0.5">Hotel daily summary report is ready</span></div>
                    <span class="text-[8px] text-neutral-400 font-mono">2 hours ago</span>
                </div>
            </div>
        </div>
    </div>

</x-manager-dashboard-layout>