<x-admin-dashboard-layout>
    @php
        $topRoomType = $topRoomTypesReport[0] ?? null;
        $topMenu = $topSellingMenus[0] ?? null;
        $topFacility = $popularFacilities[0] ?? null;
    @endphp

    <div class="space-y-5">
        <section class="overflow-hidden rounded-2xl bg-slate-950 p-6 text-white shadow-lg sm:p-8">
            <div class="flex min-w-0 flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="min-w-0 max-w-3xl">
                    <p class="text-sm font-semibold text-blue-300">Live operations report</p>
                    <h2 class="mt-2 text-3xl font-semibold tracking-tight sm:text-4xl">Hotel performance overview</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-300">Revenue, occupancy, room performance, restaurant sales, and facility reservations are calculated from current database records.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ auth()->user()->role === 'manager' ? route('manager.reports.export.excel') : route('admin.reports.export.excel') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/15 bg-white/10 px-4 py-2.5 text-sm font-semibold text-white hover:bg-white/15"><i class="fa-solid fa-file-excel text-emerald-300"></i>Excel</a>
                    <a href="{{ auth()->user()->role === 'manager' ? route('manager.reports.export.pdf') : route('admin.reports.export.pdf') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-500"><i class="fa-solid fa-file-pdf"></i>PDF</a>
                </div>
            </div>
        </section>

        <section class="grid min-w-0 grid-cols-2 gap-3 md:grid-cols-3 2xl:grid-cols-6">
            @foreach([
                ['Total revenue', 'Rp ' . number_format($totalRevenue, 0, ',', '.'), 'fa-wallet', 'bg-blue-50 text-blue-700'],
                ['Occupancy', number_format($occupancyRate, 1) . '%', 'fa-bed', 'bg-emerald-50 text-emerald-700'],
                ['Bookings', number_format($totalBookingsCount), 'fa-calendar-check', 'bg-violet-50 text-violet-700'],
                ['Guests', number_format($totalGuestsCount), 'fa-users', 'bg-cyan-50 text-cyan-700'],
                ['ADR', 'Rp ' . number_format($adr, 0, ',', '.'), 'fa-money-bill-trend-up', 'bg-amber-50 text-amber-700'],
                ['RevPAR', 'Rp ' . number_format($revpar, 0, ',', '.'), 'fa-chart-line', 'bg-rose-50 text-rose-700'],
            ] as [$label, $value, $icon, $tone])
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                    <div class="flex items-start justify-between gap-3"><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $label }}</p><p class="mt-2 break-words text-lg font-semibold tracking-tight text-slate-900">{{ $value }}</p></div><span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl {{ $tone }}"><i class="fa-solid {{ $icon }} text-sm"></i></span></div>
                </article>
            @endforeach
        </section>

        <section class="grid min-w-0 grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-4">
            <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Revenue contribution</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Revenue mix</h3>
                <div class="mt-5 space-y-4">
                    @foreach([['Rooms', $shares['room'], 'bg-blue-600'], ['Restaurant', $shares['fb'], 'bg-emerald-500'], ['Other', $shares['other'], 'bg-violet-500']] as [$label,$share,$bar])
                        <div><div class="flex items-center justify-between text-sm"><span class="text-slate-600">{{ $label }}</span><strong class="text-slate-900">{{ number_format($share, 1) }}%</strong></div><div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $bar }}" style="width: {{ min(100, max(0, $share)) }}%"></div></div></div>
                    @endforeach
                </div>
            </article>

            <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Best room performance</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Top room type</h3>
                @if($topRoomType)
                    <p class="mt-5 break-words text-xl font-semibold text-slate-900">{{ $topRoomType['name'] }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-3"><div class="rounded-xl bg-slate-50 p-3"><p class="text-xs text-slate-500">Room nights</p><p class="mt-1 font-semibold text-slate-900">{{ $topRoomType['sold'] }}</p></div><div class="rounded-xl bg-emerald-50 p-3"><p class="text-xs text-emerald-700">Revenue</p><p class="mt-1 break-words text-sm font-semibold text-emerald-800">Rp {{ number_format($topRoomType['revenue'], 0, ',', '.') }}</p></div></div>
                @else
                    <p class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No room performance data yet.</p>
                @endif
            </article>

            <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Restaurant sales</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Top menu item</h3>
                @if($topMenu)
                    <p class="mt-5 break-words text-xl font-semibold text-slate-900">{{ $topMenu->name }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-3"><div class="rounded-xl bg-blue-50 p-3"><p class="text-xs text-blue-700">Portions sold</p><p class="mt-1 font-semibold text-blue-800">{{ $topMenu->qty_sold }}</p></div><div class="rounded-xl bg-emerald-50 p-3"><p class="text-xs text-emerald-700">Sales value</p><p class="mt-1 break-words text-sm font-semibold text-emerald-800">Rp {{ number_format($topMenu->gross_rev, 0, ',', '.') }}</p></div></div>
                @else
                    <p class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No paid restaurant orders yet.</p>
                @endif
            </article>

            <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-medium text-slate-500">Facility demand</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Top facility</h3>
                @if($topFacility)
                    <p class="mt-5 break-words text-xl font-semibold text-slate-900">{{ $topFacility->facility_name }}</p>
                    <div class="mt-4 grid grid-cols-2 gap-3"><div class="rounded-xl bg-violet-50 p-3"><p class="text-xs text-violet-700">Reservations</p><p class="mt-1 font-semibold text-violet-800">{{ $topFacility->total_sessions }}</p></div><div class="rounded-xl bg-cyan-50 p-3"><p class="text-xs text-cyan-700">Guest visits</p><p class="mt-1 font-semibold text-cyan-800">{{ $topFacility->total_guests }}</p></div></div>
                @else
                    <p class="mt-5 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">No facility reservations yet.</p>
                @endif
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <nav class="flex max-w-full gap-2 overflow-x-auto border-b border-slate-100 p-3">
                @foreach([['overview','Overview'],['rooms','Room types'],['gastronomy','Restaurant'],['facilities','Facilities']] as [$tab,$label])
                    <button type="button" id="btn_{{ $tab }}" onclick="switchReportSection('{{ $tab }}')" class="report-tab-trigger min-w-max rounded-xl px-3 py-2 text-sm font-semibold {{ $tab === 'overview' ? 'bg-blue-50 text-blue-700' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">{{ $label }}</button>
                @endforeach
            </nav>

            <div id="section_overview" class="report-view-panel p-5 sm:p-6">
                <div class="grid min-w-0 grid-cols-1 gap-5 xl:grid-cols-2">
                    <article class="min-w-0 rounded-2xl bg-slate-50 p-5"><p class="text-xs font-medium text-slate-500">Last seven days</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Daily paid revenue</h3><div class="mt-6 h-48 overflow-hidden"><svg viewBox="0 0 600 140" class="h-full w-full" preserveAspectRatio="none"><line x1="0" y1="120" x2="600" y2="120" stroke="#cbd5e1"/><path d="M {{ $polylineCoordinates }}" fill="none" stroke="#2563eb" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg></div><div class="mt-3 flex justify-between gap-2 overflow-hidden text-[10px] text-slate-400">@foreach($chartLabels as $label)<span class="truncate">{{ $label }}</span>@endforeach</div></article>
                    <article class="min-w-0 rounded-2xl bg-slate-50 p-5"><p class="text-xs font-medium text-slate-500">Last seven days</p><h3 class="mt-1 text-lg font-semibold text-slate-900">Occupancy trend</h3><div class="mt-6 flex h-48 items-end justify-between gap-2 border-b border-slate-200 px-2">@foreach($barHeights as $height)<div class="flex min-w-0 flex-1 flex-col items-center justify-end gap-2"><span class="text-[10px] font-semibold text-slate-500">{{ $height }}%</span><div class="w-full max-w-10 rounded-t-lg bg-blue-600" style="height: {{ max(8, $height * 1.45) }}px"></div></div>@endforeach</div></article>
                </div>
            </div>

            <div id="section_rooms" class="report-view-panel hidden p-5 sm:p-6">
                <div class="max-w-full overflow-x-auto"><table class="min-w-[760px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Rank</th><th class="px-4 py-3">Room type</th><th class="px-4 py-3">Nights sold</th><th class="px-4 py-3">Revenue</th><th class="px-4 py-3 text-right">Share</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($topRoomTypesReport as $index => $row)<tr><td class="px-4 py-4 text-slate-400">{{ $index + 1 }}</td><td class="px-4 py-4 font-semibold text-slate-900">{{ $row['name'] }}</td><td class="px-4 py-4 text-slate-600">{{ $row['sold'] }}</td><td class="px-4 py-4 font-semibold text-slate-900">Rp {{ number_format($row['revenue'], 0, ',', '.') }}</td><td class="px-4 py-4 text-right text-slate-600">{{ number_format($row['pct'], 1) }}%</td></tr>@empty<tr><td colspan="5" class="px-4 py-10 text-center text-slate-500">No room performance data.</td></tr>@endforelse</tbody></table></div>
            </div>

            <div id="section_gastronomy" class="report-view-panel hidden p-5 sm:p-6">
                <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3"><div class="rounded-xl bg-slate-50 p-4"><p class="text-xs text-slate-500">All orders</p><p class="mt-1 text-xl font-semibold text-slate-900">{{ $totalFbOrders }}</p></div><div class="rounded-xl bg-emerald-50 p-4"><p class="text-xs text-emerald-700">Paid orders</p><p class="mt-1 text-xl font-semibold text-emerald-800">{{ $completedFbOrders }}</p></div><div class="rounded-xl bg-blue-50 p-4"><p class="text-xs text-blue-700">Average paid order</p><p class="mt-1 text-lg font-semibold text-blue-800">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p></div></div>
                <div class="max-w-full overflow-x-auto"><table class="min-w-[680px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Rank</th><th class="px-4 py-3">Menu item</th><th class="px-4 py-3">Portions sold</th><th class="px-4 py-3 text-right">Sales value</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($topSellingMenus as $index => $item)<tr><td class="px-4 py-4 text-slate-400">{{ $index + 1 }}</td><td class="px-4 py-4 font-semibold text-slate-900">{{ $item->name }}</td><td class="px-4 py-4 text-slate-600">{{ $item->qty_sold }}</td><td class="px-4 py-4 text-right font-semibold text-slate-900">Rp {{ number_format($item->gross_rev, 0, ',', '.') }}</td></tr>@empty<tr><td colspan="4" class="px-4 py-10 text-center text-slate-500">No paid restaurant sales yet.</td></tr>@endforelse</tbody></table></div>
            </div>

            <div id="section_facilities" class="report-view-panel hidden p-5 sm:p-6">
                <div class="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-2"><div class="rounded-xl bg-slate-50 p-4"><p class="text-xs text-slate-500">All reservations</p><p class="mt-1 text-xl font-semibold text-slate-900">{{ $totalFacBookings }}</p></div><div class="rounded-xl bg-emerald-50 p-4"><p class="text-xs text-emerald-700">Completed sessions</p><p class="mt-1 text-xl font-semibold text-emerald-800">{{ $completedFacSessions }}</p></div></div>
                <div class="max-w-full overflow-x-auto"><table class="min-w-[680px] text-left text-sm"><thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Facility</th><th class="px-4 py-3">Reservations</th><th class="px-4 py-3 text-right">Guest visits</th></tr></thead><tbody class="divide-y divide-slate-100">@forelse($popularFacilities as $facility)<tr><td class="px-4 py-4 font-semibold text-slate-900">{{ $facility->facility_name }}</td><td class="px-4 py-4 text-slate-600">{{ $facility->total_sessions }}</td><td class="px-4 py-4 text-right font-semibold text-slate-900">{{ $facility->total_guests }}</td></tr>@empty<tr><td colspan="3" class="px-4 py-10 text-center text-slate-500">No facility reservation data yet.</td></tr>@endforelse</tbody></table></div>
            </div>
        </section>
    </div>

    <script>
        function switchReportSection(section) {
            document.querySelectorAll('.report-view-panel').forEach((panel) => panel.classList.add('hidden'));
            document.querySelectorAll('.report-tab-trigger').forEach((button) => button.className = 'report-tab-trigger min-w-max rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50 hover:text-slate-900');
            document.getElementById(`section_${section}`)?.classList.remove('hidden');
            const active = document.getElementById(`btn_${section}`);
            if (active) active.className = 'report-tab-trigger min-w-max rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700';
        }
    </script>
</x-admin-dashboard-layout>
