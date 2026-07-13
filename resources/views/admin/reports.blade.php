<x-admin-dashboard-layout>
    @php
        $topRoomType = $topRoomTypesReport[0] ?? null;
        $topMenu = $topSellingMenus[0] ?? null;
        $topFacility = $popularFacilities[0] ?? null;
    @endphp

    <div class="relative overflow-hidden border border-neutral-800 bg-neutral-950 p-8 text-white shadow-lg">
        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-emerald-500/10 blur-3xl"></div>
        <div class="absolute -bottom-16 left-1/3 h-48 w-48 rounded-full bg-blue-500/10 blur-3xl"></div>
        
        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl space-y-2">
                <div class="inline-flex items-center gap-2 bg-neutral-900 border border-neutral-800 px-2.5 py-1 text-[9px] font-bold uppercase tracking-[0.25em] text-emerald-400">
                    <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-emerald-400"></span>
                    Executive Reporting Suite
                </div>
                <h2 class="font-serif text-3xl font-light tracking-wide text-neutral-50">
                    Hotel Operations & Performance Overview
                </h2>
                <p class="text-xs leading-relaxed text-neutral-400">
                    Consolidated analytical dashboard combining room revenue vectors, gastronomy operations, wellness utilization data, and occupancy velocity indexes curated for executive leadership assessment.
                </p>
            </div>
            <div class="flex items-center gap-3 border border-neutral-800 bg-neutral-900/60 backdrop-blur-md px-4 py-3 text-xs font-mono text-neutral-300 self-start lg:self-center">
                <i class="fa-solid fa-server text-emerald-400"></i>
                <span>Status: <span class="text-emerald-400 font-bold">Synchronized</span> • Export Grid Engine Ready</span>
            </div>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs group">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Gross Revenue</span>
            <div class="mt-4">
                <span class="text-xs font-semibold text-neutral-400 block font-mono">IDR</span>
                <span class="text-base font-bold text-neutral-900 block font-mono tracking-tight mt-0.5">
                    {{ number_format($totalRevenue, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate</span>
            <div class="mt-4 flex items-baseline gap-1">
                <span class="text-3xl font-light font-serif text-neutral-950">{{ $occupancyRate }}</span>
                <span class="text-xs font-bold text-neutral-400 font-mono">%</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings</span>
            <div class="mt-4 flex items-baseline gap-1">
                <span class="text-3xl font-light font-serif text-neutral-950">{{ $totalBookingsCount }}</span>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Folios</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests Matrix</span>
            <div class="mt-4 flex items-baseline gap-1">
                <span class="text-3xl font-light font-serif text-neutral-950">{{ $totalGuestsCount }}</span>
                <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider">Heads</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Average Daily Rate</span>
            <div class="mt-4">
                <span class="text-xs font-semibold text-neutral-400 block font-mono">ADR</span>
                <span class="text-sm font-bold text-neutral-900 block font-mono tracking-tight mt-0.5">
                    Rp {{ number_format($adr, 0, ',', '.') }}
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/70 hover:border-neutral-400 transition-all flex flex-col justify-between shadow-xs">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">RevPAR Performance</span>
            <div class="mt-4">
                <span class="text-xs font-semibold text-neutral-400 block font-mono">RevPAR</span>
                <span class="text-sm font-bold text-neutral-900 block font-mono tracking-tight mt-0.5">
                    Rp {{ number_format($revpar, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 tracking-wide uppercase">Revenue Stream Mix</h3>
                    <span class="text-[8px] font-bold bg-neutral-100 px-2 py-0.5 text-neutral-500 uppercase font-mono tracking-wider">Share</span>
                </div>
                <div class="mt-4 space-y-3.5 text-xs font-medium text-neutral-600">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 bg-neutral-950"></span>Rooms Segment</span>
                        <span class="font-mono font-bold text-neutral-900">{{ $shares['room'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 bg-emerald-600"></span>Gastronomy Sector</span>
                        <span class="font-mono font-bold text-neutral-900">{{ $shares['fb'] }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><span class="h-2 w-2 bg-blue-500"></span>Wellness Facilities</span>
                        <span class="font-mono font-bold text-neutral-900">{{ $shares['other'] }}%</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex h-2 w-full bg-neutral-100 overflow-hidden">
                <div class="bg-neutral-950" style="width: {{ $shares['room'] }}%"></div>
                <div class="bg-emerald-600" style="width: {{ $shares['fb'] }}%"></div>
                <div class="bg-blue-500" style="width: {{ $shares['other'] }}%"></div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 tracking-wide uppercase">Top Room Performer</h3>
                    <span class="text-[8px] font-bold bg-neutral-900 text-white px-2 py-0.5 uppercase font-mono tracking-wider">Priority Alpha</span>
                </div>
                <div class="mt-4">
                    <p class="text-base font-serif font-bold text-neutral-950">{{ $topRoomType['name'] ?? 'No Room Manifest Data Logged' }}</p>
                    <div class="mt-3 flex flex-wrap gap-2 text-[10px] font-semibold text-neutral-500">
                        <span class="bg-neutral-50 border border-neutral-200 px-2 py-1 font-mono"><i class="fa-solid fa-moon mr-1 text-neutral-400"></i>{{ $topRoomType['sold'] ?? 0 }} Room Nights</span>
                        <span class="bg-emerald-50 border border-emerald-100 px-2 py-1 font-mono text-emerald-800"><i class="fa-solid fa-wallet mr-1 text-emerald-600"></i>Rp {{ number_format($topRoomType['revenue'] ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            <span class="text-[9px] font-medium text-neutral-400 block mt-4 border-t border-neutral-50 pt-2">Calculated from total checked_out folios matrix</span>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 tracking-wide uppercase">Top Menu / Facility</h3>
                    <span class="text-[8px] font-bold bg-neutral-100 px-2 py-0.5 text-neutral-500 uppercase font-mono tracking-wider">Ancillary</span>
                </div>
                <div class="mt-4">
                    <p class="text-base font-serif font-bold text-neutral-950 truncate">
                        {{ $topMenu?->name ?? ($topFacility?->facility_name ?? 'No active auxiliary logs found') }}
                    </p>
                    <div class="mt-3 inline-flex items-center gap-1.5 bg-blue-50 border border-blue-100 px-2.5 py-1 text-[10px] font-bold font-mono text-blue-800">
                        <i class="fa-solid fa-chart-line"></i>
                        @if(isset($topMenu) && $topMenu?->qty_sold)
                            {{ $topMenu->qty_sold }} Portions Dispatched
                        @elif(isset($topFacility) && $topFacility?->total_sessions)
                            {{ $topFacility->total_sessions }} Active Sessions Secured
                        @else
                            Idle Baseline Mode
                        @endif
                    </div>
                </div>
            </div>
            <span class="text-[9px] font-medium text-neutral-400 block mt-4 border-t border-neutral-50 pt-2">Evaluates top velocity products per billing transaction</span>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 shadow-xs p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 mt-8">
        <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-6">
            <button onclick="switchReportSection('overview')" id="btn_overview" class="report-tab-trigger text-neutral-900 border-b-2 border-neutral-950 pb-1.5 px-0.5 cursor-pointer transition-all">Overview Analysis</button>
            <button onclick="switchReportSection('rooms')" id="btn_rooms" class="report-tab-trigger hover:text-neutral-900 pb-1.5 px-0.5 cursor-pointer transition-all">Rooms Inventory Matrix</button>
            <button onclick="switchReportSection('gastronomy')" id="btn_gastronomy" class="report-tab-trigger hover:text-neutral-900 pb-1.5 px-0.5 cursor-pointer transition-all">Gastronomy Ledger (F&B)</button>
            <button onclick="switchReportSection('facilities')" id="btn_facilities" class="report-tab-trigger hover:text-neutral-900 pb-1.5 px-0.5 cursor-pointer transition-all">Facilities & Wellness Grid</button>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <a href="{{ route(auth()->user()->role === 'manager' ? 'manager.reports.export.excel' : 'admin.reports.export.excel') }}" class="bg-white hover:bg-neutral-50 border border-neutral-200 text-neutral-700 font-bold text-[11px] uppercase tracking-wider px-4 py-2 flex items-center gap-2 transition-all shadow-xs cursor-pointer">
                <i class="fa-solid fa-file-excel text-emerald-600 text-xs"></i> Export Spreadsheet
            </a>
            <a href="{{ route(auth()->user()->role === 'manager' ? 'manager.reports.export.pdf' : 'admin.reports.export.pdf') }}" target="_blank" rel="noopener" class="bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[11px] uppercase tracking-wider px-4 py-2 flex items-center gap-2 transition-colors shadow-sm cursor-pointer">
                <i class="fa-solid fa-print text-rose-400 text-xs"></i> Executive Print / PDF
            </a>
        </div>
    </div>

    <div id="section_overview" class="report-view-panel space-y-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
                <div class="border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wider">Gross Weekly Revenue Velocity</h3>
                </div>
                <div class="relative w-full h-44 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                        <path d="M {{ $polylineCoordinates }}" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100 mt-2">
                        @foreach($chartLabels as $label) <span>{{ $label }}</span> @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
                <div class="border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wider">Department Net Contribution</h3>
                </div>
                <div class="space-y-3.5 w-full text-[11px] font-semibold text-neutral-500 my-auto">
                    <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                        <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 bg-neutral-950 rounded-full"></span>Room Sub-Ledger</span>
                        <span class="text-neutral-950 font-mono font-bold">{{ $shares['room'] }}%</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                        <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 bg-emerald-600 rounded-full"></span>Food & Beverage outlets</span>
                        <span class="text-neutral-950 font-mono font-bold">{{ $shares['fb'] }}%</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-neutral-50 pb-2">
                        <span class="flex items-center gap-2"><span class="h-1.5 w-1.5 bg-blue-500 rounded-full"></span>Recreation & Spa Hub</span>
                        <span class="text-neutral-950 font-mono font-bold">{{ $shares['other'] }}%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-xs flex flex-col justify-between">
                <div class="border-b border-neutral-100 pb-3 mb-4">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wider">Timeline Occupancy Curve</h3>
                </div>
                <div class="h-40 flex items-end justify-between px-2 relative border-b border-neutral-100 pb-1 mt-4">
                    @foreach($barHeights as $height)
                        <div class="flex flex-col items-center gap-1.5 w-8 group">
                            <span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity absolute -top-4">{{ $height }}%</span>
                            <div class="w-6 bg-neutral-950/90 group-hover:bg-neutral-800 transition-all rounded-t-xs" style="height: {{ max(10, $height * 1.2) }}px"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="section_rooms" class="report-view-panel space-y-8 mt-8 hidden">
        <div class="bg-white border border-neutral-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-neutral-100 bg-neutral-50/50">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide uppercase">Room Kategori Performance Ledger</h3>
                <span class="text-[11px] text-neutral-400 block mt-1">Cross-sectional analysis mapping night volumes sold against aggregate gross margins per suite category classification.</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs whitespace-nowrap table-fixed">
                    <thead>
                        <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50">
                            <th class="py-3 px-6 w-16">Rank</th>
                            <th class="py-3 px-4">Room Category Identity</th>
                            <th class="py-3 px-4">Volume Nights Dispatched</th>
                            <th class="py-3 px-4">Gross Revenue Yield</th>
                            <th class="py-3 px-6 text-right w-44">Contribution Ratio</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @foreach($topRoomTypesReport as $row)
                            <tr class="hover:bg-neutral-50/50 transition-colors">
                                <td class="py-3.5 px-6 font-mono text-neutral-400">#0{{ $row['index'] }}</td>
                                <td class="py-3.5 px-4 text-neutral-900 font-bold">{{ $row['name'] }}</td>
                                <td class="py-3.5 px-4 font-sans font-bold text-neutral-700"><i class="fa-solid fa-moon text-neutral-300 mr-2 text-[10px]"></i>{{ $row['sold'] }} Nights</td>
                                <td class="py-3.5 px-4 font-mono text-neutral-950 font-bold">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                                <td class="py-3.5 px-6 text-right font-mono font-bold text-neutral-500">{{ $row['pct'] }}%</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="section_gastronomy" class="report-view-panel space-y-6 mt-8 hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-neutral-200/70 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase block tracking-wider">Total Culinary Orders</span>
                    <span class="text-xl font-bold text-neutral-950 font-mono block mt-1.5">{{ $totalFbOrders }} Ticket Manifests</span>
                </div>
                <div class="w-9 h-9 bg-neutral-50 border flex items-center justify-center text-neutral-400 text-xs"><i class="fa-solid fa-utensils"></i></div>
            </div>
            <div class="bg-white border border-neutral-200/70 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase block tracking-wider">Gross Culinary Ledger</span>
                    <span class="text-xl font-bold text-emerald-800 font-mono block mt-1.5">Rp {{ number_format($fbRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="w-9 h-9 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fa-solid fa-wallet"></i></div>
            </div>
            <div class="bg-white border border-neutral-200/70 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase block tracking-wider">Average Ticket Size Size</span>
                    <span class="text-xl font-bold text-neutral-950 font-mono block mt-1.5">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
                </div>
                <div class="w-9 h-9 bg-neutral-50 border flex items-center justify-center text-neutral-400 text-xs"><i class="fa-solid fa-calculator"></i></div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-neutral-100 bg-neutral-50/50">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide uppercase">Top 5 Best-Selling Food & Beverage Menus</h3>
            </div>
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50">
                        <th class="py-3 px-6 w-20">Rank No</th>
                        <th class="py-3 px-4">Menu Asset Identity</th>
                        <th class="py-3 px-4">Quantity Volumes Transacted</th>
                        <th class="py-3 px-6 text-right">Accumulated Gross Income</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @foreach($topSellingMenus as $index => $item)
                        <tr class="hover:bg-neutral-50/50 transition-colors">
                            <td class="py-3 px-6 font-mono font-bold text-neutral-400">#0{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-neutral-900 font-bold"><i class="fa-solid fa-plate-wheat text-neutral-300 mr-2 text-[10px]"></i>{{ $item->name }}</td>
                            <td class="py-3 px-4 font-sans font-bold text-neutral-700">{{ $item->qty_sold }} Portions Dispatched</td>
                            <td class="py-3 px-6 text-right font-mono text-neutral-950 font-bold">Rp {{ number_format($item->gross_rev, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="section_facilities" class="report-view-panel space-y-6 mt-8 hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white border border-neutral-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase block tracking-wider">Total Facilities Reservations</span>
                    <span class="text-xl font-bold text-neutral-950 font-mono block mt-1.5">{{ $totalFacBookings }} Confirmed Slotted</span>
                </div>
                <div class="w-9 h-9 bg-neutral-50 border flex items-center justify-center text-neutral-400 text-xs"><i class="fa-solid fa-calendar-check"></i></div>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <span class="text-[9px] font-bold text-neutral-400 uppercase block tracking-wider">Gross Wellness Revenue Yield</span>
                    <span class="text-xl font-bold text-emerald-800 font-mono block mt-1.5">Rp {{ number_format($facRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="w-9 h-9 bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 text-xs"><i class="fa-solid fa-spa"></i></div>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 shadow-xs overflow-hidden">
            <div class="p-6 border-b border-neutral-100 bg-neutral-50/50">
                <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide uppercase">Facilities Allocation & Sessions Performance Ledger</h3>
            </div>
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-200 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50">
                        <th class="py-3 px-6">Facility Venue Sector</th>
                        <th class="py-3 px-4">Total Secured Sessions</th>
                        <th class="py-3 px-6 text-right">Total Guest Traffic Volume</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @foreach($popularFacilities as $fac)
                        <tr class="hover:bg-neutral-50/50 transition-colors">
                            <td class="py-3 px-6 text-neutral-900 font-bold"><i class="fa-solid fa-square-poll text-neutral-300 mr-2"></i> {{ $fac->facility_name }}</td>
                            <td class="py-3 px-4 font-mono font-bold text-neutral-700">{{ $fac->total_sessions }} Active Sessions</td>
                            <td class="py-3 px-6 text-right font-sans font-bold text-neutral-950">{{ $fac->total_guests }} Visitors Headcount</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin-dashboard-layout>

<script type="text/javascript">
    function switchReportSection(targetSectionId) {
        document.querySelectorAll('.report-view-panel').forEach(panel => { panel.classList.add('hidden'); });
        document.querySelectorAll('.report-tab-trigger').forEach(btn => {
            btn.className = "report-tab-trigger hover:text-neutral-900 pb-1.5 px-0.5 cursor-pointer transition-all";
        });
        document.getElementById('section_' + targetSectionId).classList.remove('hidden');
        document.getElementById('btn_' + targetSectionId).className = "report-tab-trigger text-neutral-900 border-b-2 border-neutral-950 pb-1.5 px-0.5 cursor-pointer font-bold transition-all";
    }
</script>
