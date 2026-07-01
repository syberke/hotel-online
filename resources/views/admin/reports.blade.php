<x-admin-dashboard-layout>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Gross Revenue</span>
            <div class="mt-2">
                <span class="text-md font-bold text-neutral-900 block font-mono">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5 mt-1">
                    <i class="fa-solid fa-database text-[8px]"></i> Realtime Live DB
                </span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Occupancy Rate (Avg)</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">{{ $occupancyRate }}%</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Bookings</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">{{ $totalBookingsCount }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Guests Matrix</span>
            <div class="mt-2">
                <span class="text-2xl font-light font-serif text-neutral-900 block">{{ $totalGuestsCount }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">ADR (Average Daily Rate)</span>
            <div class="mt-2">
                <span class="text-xs font-bold text-neutral-900 block font-mono">Rp {{ number_format($adr, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">RevPAR Performance</span>
            <div class="mt-2">
                <span class="text-xs font-bold text-neutral-900 block font-mono">Rp {{ number_format($revpar, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="bg-white border border-neutral-200 shadow-sm p-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4 mt-8">
        <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6">
            <button onclick="switchReportSection('overview')" id="btn_overview" class="report-tab-trigger text-neutral-900 border-b-2 border-neutral-900 pb-1 px-0.5 cursor-pointer">Overview Report</button>
            <button onclick="switchReportSection('rooms')" id="btn_rooms" class="report-tab-trigger hover:text-neutral-900 pb-1 px-0.5 cursor-pointer">Rooms Inventory</button>
            <button onclick="switchReportSection('gastronomy')" id="btn_gastronomy" class="report-tab-trigger hover:text-neutral-900 pb-1 px-0.5 cursor-pointer">Gastronomy F&B</button>
            <button onclick="switchReportSection('facilities')" id="btn_facilities" class="report-tab-trigger hover:text-neutral-900 pb-1 px-0.5 cursor-pointer">Facilities & Wellness</button>
        </div>

        <div class="flex items-center gap-3 self-end lg:self-auto">
            <a href="{{ route('admin.reports.export.excel') }}" class="bg-white hover:bg-neutral-50 border border-neutral-200 text-neutral-700 font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
                <i class="fa-solid fa-file-excel text-emerald-600"></i> Export Excel
            </a>
            <a href="{{ route('admin.reports.export.pdf') }}" target="_blank" class="bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                <i class="fa-solid fa-file-pdf text-rose-500"></i> Export PDF / Print
            </a>
        </div>
    </div>

    <div id="section_overview" class="report-view-panel space-y-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Gross Weekly Revenue Revenue</h3>
                </div>
                <div class="relative w-full h-40 flex flex-col justify-between pt-2">
                    <svg viewBox="0 0 600 140" class="w-full h-full overflow-visible">
                        <line x1="0" y1="20" x2="600" y2="20" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="60" x2="600" y2="60" stroke="#f4f4f5" stroke-width="1" />
                        <line x1="0" y1="100" x2="600" y2="100" stroke="#f4f4f5" stroke-width="1" />
                        <path d="M {{ $polylineCoordinates }}" fill="none" stroke="#059669" stroke-width="2.5" />
                    </svg>
                    <div class="flex justify-between text-[9px] text-neutral-400 font-mono font-bold pt-2 border-t border-neutral-100">
                        @foreach($chartLabels as $label) <span>{{ $label }}</span> @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Revenue Share Breakdown</h3>
                </div>
                <div class="space-y-2 w-full text-[10px] font-semibold text-neutral-500 my-auto">
                    <div class="flex justify-between items-center border-b pb-1.5"><span>Room Segment</span><span class="text-neutral-900 font-mono font-bold">{{ $shares['room'] }}%</span></div>
                    <div class="flex justify-between items-center border-b pb-1.5"><span>Gastronomy Sektor</span><span class="text-neutral-900 font-mono font-bold">{{ $shares['fb'] }}%</span></div>
                    <div class="flex justify-between items-center border-b pb-1.5"><span>Wellness Facilities</span><span class="text-neutral-900 font-mono font-bold">{{ $shares['other'] }}%</span></div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-4 mb-4">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Live Timeline Occupancy</h3>
                </div>
                <div class="h-40 flex items-end justify-between px-2 relative border-b border-neutral-100 pb-1">
                    @foreach($barHeights as $height)
                        <div class="flex flex-col items-center gap-1.5 w-8 group">
                            <span class="text-[9px] font-mono font-bold text-neutral-800 opacity-0 group-hover:opacity-100 transition-opacity">{{ $height }}%</span>
                            <div class="w-6 bg-emerald-600/90 group-hover:bg-emerald-700 transition-colors" style="height: {{ $height }}px"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div id="section_rooms" class="report-view-panel space-y-8 mt-8 hidden">
        <div class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-4 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Room Types Performance Matrix</h3>
                <span class="text-[10px] text-neutral-400 block mt-0.5">Analitis volume penjualan bermalam serta kontribusi laba kotor per kategori kamar.</span>
            </div>
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/50">
                        <th class="py-2 px-3">No</th>
                        <th class="py-2 px-3">Room Type Name</th>
                        <th class="py-2 px-3">Room Nights Sold</th>
                        <th class="py-2 px-3">Gross Revenue Value</th>
                        <th class="py-2 px-3 text-right">Revenue Contribution Share Ratio</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @foreach($topRoomTypesReport as $row)
                        <tr class="hover:bg-neutral-50/30 transition-colors">
                            <td class="py-3 px-3 font-mono">{{ $row['index'] }}</td>
                            <td class="py-3 px-3 text-neutral-900 font-bold">{{ $row['name'] }}</td>
                            <td class="py-3 px-3 font-sans font-bold text-neutral-700">{{ $row['sold'] }} Nights</td>
                            <td class="py-3 px-3 font-mono text-neutral-900 font-bold">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td>
                            <td class="py-3 px-3 text-right font-mono text-neutral-400">{{ $row['pct'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="section_gastronomy" class="report-view-panel space-y-6 mt-8 hidden">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white border border-neutral-200 p-5 shadow-xs">
                <span class="text-[9px] font-bold text-neutral-400 uppercase block">Total Culinary Orders</span>
                <span class="text-2xl font-bold text-neutral-900 font-mono block mt-1">{{ $totalFbOrders }}</span>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-xs">
                <span class="text-[9px] font-bold text-neutral-400 uppercase block">Gross Culinary Revenue</span>
                <span class="text-2xl font-bold text-emerald-800 font-mono block mt-1">Rp {{ number_format($fbRevenue, 0, ',', '.') }}</span>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-xs">
                <span class="text-[9px] font-bold text-neutral-400 uppercase block">Average Check Value / Ticket Size</span>
                <span class="text-2xl font-bold text-neutral-900 font-mono block mt-1">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-3 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Top 5 Best-Selling Food & Beverage Menus</h3>
            </div>
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/50">
                        <th class="py-2 px-3">Rank No</th>
                        <th class="py-2 px-3">Menu Name Item</th>
                        <th class="py-2 px-3">Quantity Volumes Sold</th>
                        <th class="py-2 px-3 text-right">Accumulated Gross Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @foreach($topSellingMenus as $index => $item)
                        <tr class="hover:bg-neutral-50/30 transition-colors">
                            <td class="py-3 px-3 font-mono">#0{{ $index + 1 }}</td>
                            <td class="py-3 px-3 text-neutral-900 font-bold">{{ $item->name }}</td>
                            <td class="py-3 px-3 font-sans font-bold text-neutral-700">{{ $item->qty_sold }} Portions</td>
                            <td class="py-3 px-3 text-right font-mono text-neutral-900 font-bold">Rp {{ number_format($item->gross_rev, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="section_facilities" class="report-view-panel space-y-6 mt-8 hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white border border-neutral-200 p-5 shadow-xs">
                <span class="text-[9px] font-bold text-neutral-400 uppercase block">Total Facilities Reservations</span>
                <span class="text-2xl font-bold text-neutral-900 font-mono block mt-1">{{ $totalFacBookings }}</span>
            </div>
            <div class="bg-white border border-neutral-200 p-5 shadow-xs">
                <span class="text-[9px] font-bold text-neutral-400 uppercase block">Gross Wellness Revenue</span>
                <span class="text-2xl font-bold text-emerald-800 font-mono block mt-1">Rp {{ number_format($facRevenue, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-white border border-neutral-200 p-6 shadow-sm">
            <div class="border-b border-neutral-100 pb-3 mb-4">
                <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Facilities Allocation & Sessions Performance Ledger</h3>
            </div>
            <table class="w-full text-left text-xs whitespace-nowrap">
                <thead>
                    <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/50">
                        <th class="py-2 px-3">Facility Area</th>
                        <th class="py-2 px-3">Total Secured Sessions</th>
                        <th class="py-2 px-3 text-right">Total Guest Traffic Volume</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                    @foreach($popularFacilities as $fac)
                        <tr class="hover:bg-neutral-50/30 transition-colors">
                            <td class="py-3 px-3 text-neutral-900 font-bold"><i class="fa-solid fa-spa text-neutral-400 mr-2"></i> {{ $fac->facility_name }}</td>
                            <td class="py-3 px-3 font-mono font-bold text-neutral-700">{{ $fac->total_sessions }} Booked Sessions</td>
                            <td class="py-3 px-3 text-right font-sans font-bold text-neutral-900">{{ $fac->total_guests }} Visitors Headcount</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function switchReportSection(targetSectionId) {
        // Sembunyikan seluruh workspace segmen laporan yang ada di layar
        document.querySelectorAll('.report-view-panel').forEach(panel => {
            panel.classList.add('hidden');
        });

        // Matikan garis highlight tebal hitam pada seluruh deretan tombol tab nav
        document.querySelectorAll('.report-tab-trigger').forEach(btn => {
            btn.className = "report-tab-trigger hover:text-neutral-900 pb-1 px-0.5 cursor-pointer";
        });

        // Munculkan workspace panel laporan target pilihan pengguna
        document.getElementById('section_' + targetSectionId).classList.remove('hidden');

        // Nyalakan tanda aktif border-b-2 pada tombol yang diklik oleh kursor
        document.getElementById('btn_' + targetSectionId).className = "report-tab-trigger text-neutral-900 border-b-2 border-neutral-900 pb-1 px-0.5 cursor-pointer font-bold";
    }
</script>