<x-guest-layout>
    <div class="min-h-screen bg-[#f8f7f5] text-neutral-900 font-sans antialiased flex">

        <aside class="w-64 bg-[#141414] text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-800 z-30 select-none">
            <div class="overflow-y-auto flex-1 custom-scrollbar">
                <div class="p-6 border-b border-neutral-800 bg-[#0d0d0d]">
                    <h2 class="text-xl font-serif italic tracking-widest text-white">Oasis Hotel</h2>
                    <p class="text-[9px] uppercase tracking-[0.3em] text-amber-500 font-bold mt-1">Business Intelligence</p>
                </div>

                <div class="p-3 space-y-6">
                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Executive Overview</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold bg-neutral-900 text-amber-400 border-l-2 border-amber-500 rounded-none transition-all">
                                <i class="fa-solid fa-chart-pie w-4"></i> Dashboard
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-folder-tree w-4"></i> Bookings & Rooms
                            </a>
                        </nav>
                    </div>

                    <div>
                        <span class="px-3 text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-600 block mb-2">Yield Analytics</span>
                        <nav class="space-y-0.5">
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-chart-line w-4"></i> Reports Matrix</span>
                                <i class="fa-solid fa-chevron-down text-[9px] text-neutral-600"></i>
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <i class="fa-solid fa-users w-4"></i> Staff Performance
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-star-half-stroke w-4"></i> Guest Reviews</span>
                                <span class="bg-amber-600 text-white text-[8px] px-1.5 py-0.2 font-bold font-sans">New</span>
                            </a>
                            <a href="#" class="flex items-center justify-between px-3 py-2 text-xs uppercase tracking-wider font-bold hover:bg-neutral-900 hover:text-white border-l-2 border-transparent transition-all">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-square-check w-4"></i> Approvals</span>
                                <span class="bg-neutral-800 text-neutral-400 text-[9px] px-1.5 py-0.2 font-mono font-bold">5 Pending</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-[#0d0d0d] border-t border-neutral-800 space-y-3">
                <div class="flex items-center gap-3 p-2 bg-neutral-900/40 border border-neutral-900">
                    <div class="w-8 h-8 bg-neutral-800 border border-neutral-700 flex items-center justify-center font-serif text-white text-xs rounded-none">
                        M
                    </div>
                    <div>
                        <span class="text-xs font-bold text-neutral-200 block truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                        <span class="text-[9px] uppercase tracking-widest text-amber-500 font-bold block mt-0.5">Hotel Manager</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-xs uppercase tracking-wider font-bold text-red-400 hover:bg-red-950/20 transition-all text-left">
                        <i class="fa-solid fa-right-from-bracket w-4"></i> End Session
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            
            <header class="bg-white border-b border-neutral-200 px-8 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shadow-sm">
                <div>
                    <span class="text-[9px] font-mono uppercase tracking-widest text-neutral-400">Welcome Back, Executive Console</span>
                    <h1 class="text-base font-serif text-neutral-900 font-bold mt-0.5">Manager Dashboard</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 text-[11px] font-bold uppercase tracking-wider"><i class="fa-regular fa-calendar-days mr-1"></i> Period:</span>
                        <select class="bg-neutral-50 border border-neutral-200 pl-20 pr-8 py-2 text-[11px] font-bold uppercase tracking-wider rounded-none focus:outline-none focus:border-neutral-400 cursor-pointer appearance-none">
                            <option selected>May 24, 2026 - May 30, 2026</option>
                            <option>This Quarter</option>
                            <option>Fiscal Year 2026</option>
                        </select>
                    </div>
                    <button class="bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 px-4 rounded-none transition-colors">
                        <i class="fa-solid fa-arrow-up-from-bracket mr-1"></i> Export Sheet
                    </button>
                </div>
            </header>

            <div class="p-8 space-y-8 flex-1">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                    
                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Total Revenue</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">Rp 125.4M</span>
                            <p class="text-[8px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-caret-up"></i> +12.4% vs lw</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Occupancy Rate</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">87.3%</span>
                            <p class="text-[8px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-caret-up"></i> +8.7% vs lw</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Total Bookings</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">146</span>
                            <p class="text-[8px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-caret-up"></i> +15.2% vs lw</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Avg Daily Rate</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">Rp 1.45M</span>
                            <p class="text-[8px] text-red-600 font-bold mt-1"><i class="fa-solid fa-caret-down"></i> -3.1% vs lw</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">RevPAR Metric</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">Rp 1.26M</span>
                            <p class="text-[8px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-caret-up"></i> +6.3% vs lw</p>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-4 rounded-none flex flex-col justify-between shadow-xs">
                        <span class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Guest Satisfaction</span>
                        <div class="mt-2">
                            <span class="text-lg font-mono font-bold tracking-tight text-neutral-900">4.8 / 5</span>
                            <p class="text-[8px] text-emerald-600 font-bold mt-1"><i class="fa-solid fa-plus"></i> +0.2 point shift</p>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="xl:col-span-2 bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="flex justify-between items-baseline border-b border-neutral-100 pb-3">
                            <div class="flex items-baseline gap-2">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Revenue Evolution Matrix</h3>
                                <span class="text-emerald-600 font-mono text-[10px] font-bold">+12.4% Yield Shift</span>
                            </div>
                            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Scope: Weekly Distribution Cycle</span>
                        </div>
                        
                        <div class="h-64 bg-neutral-50 border border-neutral-100 relative p-4 flex items-end justify-between group overflow-hidden">
                            <div class="absolute top-4 left-4 z-10 text-[10px] font-mono text-neutral-400 space-y-1">
                                <div>Peak Target Allocation: Rp 150M</div>
                                <div>Rolling Operational Floor: Rp 25M</div>
                            </div>
                            <div class="w-1/7 space-y-2 text-center z-10 flex flex-col items-center justify-end h-full">
                                <div class="bg-amber-700/10 hover:bg-amber-700/20 w-12 border-t-2 border-amber-600 transition-all" style="height: 50%"></div>
                                <span class="text-[9px] font-mono font-bold text-neutral-400">May 24</span>
                            </div>
                            <div class="w-1/7 space-y-2 text-center z-10 flex flex-col items-center justify-end h-full">
                                <div class="bg-amber-700/10 hover:bg-amber-700/20 w-12 border-t-2 border-amber-600 transition-all" style="height: 68%"></div>
                                <span class="text-[9px] font-mono font-bold text-neutral-400">May 25</span>
                            </div>
                            <div class="w-1/7 space-y-2 text-center z-10 flex flex-col items-center justify-end h-full">
                                <div class="bg-amber-700/10 hover:bg-amber-700/20 w-12 border-t-2 border-amber-600 transition-all" style="height: 45%"></div>
                                <span class="text-[9px] font-mono font-bold text-neutral-400">May 26</span>
                            </div>
                            <div class="w-1/7 space-y-2 text-center z-10 flex flex-col items-center justify-end h-full">
                                <div class="bg-amber-700/10 hover:bg-amber-700/20 w-12 border-t-2 border-amber-600 transition-all" style="height: 85%"></div>
                                <span class="text-[9px] font-mono font-bold text-neutral-400">May 27</span>
                            </div>
                            <div class="w-1/7 space-y-2 text-center z-10 flex flex-col items-center justify-end h-full">
                                <div class="bg-amber-700/10 hover:bg-amber-700/20 w-12 border-t-2 border-amber-600 transition-all" style="height: 92%"></div>
                                <span class="text-[9px] font-mono font-bold text-neutral-400">May 28</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="border-b border-neutral-100 pb-3 flex justify-between items-baseline">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Yield Share By Department</h3>
                            <span class="text-[9px] font-mono text-neutral-400">MBS Allocation Matrix</span>
                        </div>
                        <div class="space-y-3.5 text-xs">
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-baseline font-bold text-neutral-800">
                                    <span>Rooms & Accommodations</span>
                                    <span class="font-mono text-neutral-900">68.7% <span class="text-neutral-400 font-normal font-sans text-[10px]">(Rp 86.2M)</span></span>
                                </div>
                                <div class="bg-neutral-100 h-1.5 w-full rounded-none overflow-hidden">
                                    <div class="bg-neutral-900 h-1.5" style="width: 68.7%"></div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-baseline font-bold text-neutral-800">
                                    <span>Restaurant & F&B Outlets</span>
                                    <span class="font-mono text-neutral-900">14.9% <span class="text-neutral-400 font-normal font-sans text-[10px]">(Rp 18.7M)</span></span>
                                </div>
                                <div class="bg-neutral-100 h-1.5 w-full rounded-none overflow-hidden">
                                    <div class="bg-neutral-900 h-1.5" style="width: 14.9%"></div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-baseline font-bold text-neutral-800">
                                    <span>Spa & Integrative Wellness</span>
                                    <span class="font-mono text-neutral-900">8.4% <span class="text-neutral-400 font-normal font-sans text-[10px]">(Rp 10.5M)</span></span>
                                </div>
                                <div class="bg-neutral-100 h-1.5 w-full rounded-none overflow-hidden">
                                    <div class="bg-neutral-900 h-1.5" style="width: 8.4%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
                    
                    <div class="xl:col-span-2 bg-white border border-neutral-200 rounded-none shadow-xs">
                        <div class="p-5 border-b border-neutral-100 flex justify-between items-center bg-neutral-50/50">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Recent Operational Bookings</h3>
                            <a href="#" class="text-[9px] uppercase tracking-wider text-neutral-400 font-bold hover:text-neutral-900 underline">View System Log</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs tracking-wide border-collapse">
                                <thead class="bg-neutral-50 text-neutral-400 text-[10px] font-bold uppercase tracking-wider border-b border-neutral-200">
                                    <tr>
                                        <th class="p-4 font-bold">Booking ID</th>
                                        <th class="p-4 font-bold">Guest Account</th>
                                        <th class="p-4 font-bold">Room Category</th>
                                        <th class="p-4 font-bold">Stay Track</th>
                                        <th class="p-4 font-bold">Gross Folio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-700">
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#BK-240530</td>
                                        <td class="p-4 font-bold text-neutral-900">John Anderson</td>
                                        <td class="p-4">Deluxe Ocean View</td>
                                        <td class="p-4 text-neutral-400">May 30 &rarr; Jun 02</td>
                                        <td class="p-4 font-bold text-amber-900">Rp 4.350.000</td>
                                    </tr>
                                    <tr class="hover:bg-neutral-50/50 transition-colors">
                                        <td class="p-4 font-mono font-bold text-neutral-900">#BK-240532</td>
                                        <td class="p-4 font-bold text-neutral-900">Maria Garcia</td>
                                        <td class="p-4">Executive Suite</td>
                                        <td class="p-4 text-neutral-400">May 30 &rarr; Jun 03</td>
                                        <td class="p-4 font-bold text-amber-900">Rp 6.750.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none shadow-xs space-y-4">
                        <div class="border-b border-neutral-100 pb-3 flex justify-between items-baseline">
                            <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Staff Quality Audit</h3>
                            <span class="text-[9px] font-mono text-neutral-400">KPI Rolling Cycle</span>
                        </div>
                        <div class="space-y-4 divide-y divide-neutral-50">
                            <div class="flex items-center justify-between pt-3 first:pt-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 bg-neutral-100 border border-neutral-200 flex items-center justify-center font-bold text-neutral-700 text-[10px] rounded-none">SJ</div>
                                    <div>
                                        <h5 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900 leading-tight">Sarah Johnson</h5>
                                        <span class="text-[9px] text-neutral-400">Front Office Manager</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-mono font-bold text-neutral-900">4.9 / 5.0</span>
                                    <span class="block text-[8px] text-emerald-600 font-bold font-sans"><i class="fa-solid fa-arrow-trend-up"></i> +0.3</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between pt-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 bg-neutral-100 border border-neutral-200 flex items-center justify-center font-bold text-neutral-700 text-[10px] rounded-none">JS</div>
                                    <div>
                                        <h5 class="text-[11px] font-bold uppercase tracking-wide text-neutral-900 leading-tight">James Smith</h5>
                                        <span class="text-[9px] text-neutral-400">Restaurant Lead Steward</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs font-mono font-bold text-neutral-900">4.8 / 5.0</span>
                                    <span class="block text-[8px] text-emerald-600 font-bold font-sans"><i class="fa-solid fa-arrow-trend-up"></i> +0.2</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            @include('layouts.footer')
        </main>

    </div>
</x-guest-layout>