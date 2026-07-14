<x-admin-layout>
    @php
        $isManager = auth()->user()->role === 'manager';
        $portalPrefix = $isManager ? 'manager' : 'admin';
        $currentRouteName = request()->route()?->getName();
        $managerReportSections = [
            'manager.dashboard' => 'overview',
            'manager.reservation' => 'reservations',
            'manager.frontdesk' => 'frontdesk',
            'manager.rooms' => 'rooms',
            'manager.roomservice' => 'roomservice',
            'manager.restaurant' => 'restaurant',
            'manager.facilities' => 'facilities',
            'manager.finance' => 'finance',
            'manager.reports' => 'reports',
            'manager.userandrole' => 'users',
        ];
        $managerReportSection = $managerReportSections[$currentRouteName] ?? null;
    @endphp

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #faf9f6; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e5e5; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #a3a3a3; }
    </style>

    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex selection:bg-amber-100 selection:text-amber-900 w-full relative">
        <aside class="w-64 bg-neutral-950 text-neutral-400 flex flex-col justify-between shrink-0 border-r border-neutral-900 z-30 relative h-screen sticky top-0 overflow-y-auto custom-scrollbar">
            <div>
                <div class="p-8 border-b border-neutral-900 text-center relative group">
                    <h2 class="text-3xl font-serif tracking-[0.2em] text-white uppercase select-none transition-colors group-hover:text-amber-400">Oasis</h2>
                    <p class="text-[9px] uppercase tracking-[0.4em] text-amber-500 font-bold mt-1">
                        {{ $isManager ? 'Manager Portal' : 'Admin Portal' }}
                    </p>
                    <div class="w-6 h-px bg-amber-500/30 mx-auto mt-4 transition-all group-hover:w-16"></div>
                </div>

                <nav class="p-4 pt-6 space-y-1">
                    <span class="px-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Main Ledger</span>
                    <a href="{{ route($portalPrefix . '.dashboard') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.dashboard') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-square-poll-horizontal text-sm w-5"></i> Dashboard
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Management & Front Desk</span>
                    <a href="{{ route($portalPrefix . '.reservation') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.reservation') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-calendar-check text-sm w-5"></i> Reservations
                    </a>
                    <a href="{{ route($portalPrefix . '.rooms') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.rooms') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-bed text-sm w-5"></i> Rooms & Inventory
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">In-House Services</span>
                    <a href="{{ route($portalPrefix . '.roomservice') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.roomservice') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-bowl-food text-sm w-5"></i> Room Service Orders
                    </a>
                    <a href="{{ route($portalPrefix . '.restaurant') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.restaurant') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-utensils text-sm w-5"></i> Restaurant Gastronomy
                    </a>
                    <a href="{{ route($portalPrefix . '.facilities') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.facilities') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-spa text-sm w-5"></i> Facilities & Wellness
                    </a>

                    <span class="px-4 pt-4 text-[9px] uppercase tracking-[0.2em] font-bold text-neutral-600 block mb-2">Reports & Controls</span>
                    <a href="{{ route($portalPrefix . '.finance') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.finance') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-file-invoice-dollar text-sm w-5"></i> Finance & Billing Matrix
                    </a>
                    <a href="{{ route($portalPrefix . '.reports') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.reports') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-chart-line text-sm w-5"></i> Operational Reports
                    </a>
                    <a href="{{ route($portalPrefix . '.userandrole') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs($portalPrefix . '.userandrole') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-users-gear text-sm w-5"></i> User & Role Control
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3.5 px-4 py-3 text-xs {{ request()->routeIs('profile.edit') ? 'font-bold bg-neutral-900/80 text-amber-400 border-l-2 border-amber-500' : 'font-medium hover:bg-neutral-900/40 hover:text-white border-l-2 border-transparent hover:border-neutral-700' }} transition-all">
                        <i class="fa-solid fa-sliders text-sm w-5"></i> Account Settings
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-neutral-900 space-y-4">
                <div class="p-4 bg-neutral-900/40 border border-neutral-900/60 flex items-center justify-between select-none">
                    <div>
                        <span class="text-[9px] uppercase tracking-widest font-bold text-neutral-500 block">Property Node</span>
                        <span class="text-xs text-neutral-400 font-medium mt-0.5 inline-block">Nusa Dua, Bali</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-light font-serif text-white tracking-wide">Live</div>
                        <span class="text-amber-500 text-[10px]"><i class="fa-solid fa-circle-check mr-0.5"></i> Secure</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 text-xs uppercase tracking-widest font-bold text-red-400/80 hover:text-red-400 hover:bg-red-950/20 transition-all text-left cursor-pointer border-none bg-transparent">
                        <i class="fa-solid fa-arrow-right-from-bracket text-sm w-5"></i> Logout Portal
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-y-auto custom-scrollbar relative bg-[#f5f5f3]">
            <header class="bg-white border-b border-neutral-200/70 px-10 py-4 sticky top-0 z-20 flex justify-between items-center h-20 shrink-0">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">System Staff Management</span>
                    <h1 class="text-xl font-serif text-neutral-900 font-normal tracking-wide mt-0.5">Control Center</h1>
                </div>
                <div class="flex items-center space-x-6">
                    <span class="border border-amber-700/20 bg-amber-50/60 text-amber-900 text-[9px] font-bold uppercase tracking-[0.15em] px-3 py-1.5 flex items-center gap-1.5 select-none">
                        <i class="fa-solid fa-shield-halved text-amber-700 text-xs"></i> Security Cleared: {{ strtoupper(auth()->user()->role ?? 'Admin') }}
                    </span>
                    <div class="h-6 w-px bg-neutral-200"></div>
                    <a href="{{ route('home') }}" class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 hover:text-neutral-900 transition-colors flex items-center gap-1.5 group">
                        Exit Portal <i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-neutral-400 group-hover:text-neutral-900 transition-colors"></i>
                    </a>
                </div>
            </header>

            <div class="p-10 space-y-8 flex-1 bg-[#f5f5f3]">
                @if($isManager && $managerReportSection)
                    <div class="bg-white border border-neutral-200 shadow-sm px-5 py-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-700">Manager Report Export</span>
                            <p class="text-xs text-neutral-500 mt-1">Export laporan modul <strong class="text-neutral-900">{{ ucwords(str_replace(['_', '-'], ' ', $managerReportSection)) }}</strong> dari data database saat ini.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('manager.section-report.excel', ['section' => $managerReportSection]) }}" class="bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-800 px-4 py-2 text-[10px] font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-file-excel text-emerald-600"></i> Excel Report
                            </a>
                            <a href="{{ route('manager.section-report.pdf', ['section' => $managerReportSection]) }}" class="bg-neutral-950 hover:bg-neutral-800 text-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-rose-400"></i> PDF Report
                            </a>
                        </div>
                    </div>
                @endif

                @if(request()->routeIs('admin.facilities') && !$isManager)
                    <div class="bg-white border border-neutral-200 shadow-sm px-5 py-4 flex items-center justify-between gap-4">
                        <div>
                            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-neutral-400">Facility Master Data</span>
                            <p class="text-xs text-neutral-600 mt-1">Tambahkan area fasilitas baru ke inventory hotel.</p>
                        </div>
                        <button type="button" onclick="openCreateFacilityModal()" class="bg-amber-800 hover:bg-amber-900 text-white px-4 py-2.5 text-[10px] font-bold uppercase tracking-wider cursor-pointer">
                            <i class="fa-solid fa-plus mr-1.5"></i> Add Facility
                        </button>
                    </div>
                @endif

                {{ $slot }}

                @if(request()->routeIs('admin.restaurant') && isset($menus))
                    @include('admin.partials.restaurant-menu-inline')
                @endif
            </div>
        </div>
    </div>

    @if(request()->routeIs('admin.restaurant') && isset($menus))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const links = [...document.querySelectorAll('a')];
                const orderTab = links.find((link) => link.textContent.trim() === 'Order Management');
                const menuTab = links.find((link) => link.textContent.trim() === "Today's Menu");
                const menuPanel = document.getElementById('restaurant-menu-inline-panel');

                if (!orderTab || !menuTab || !menuPanel) return;

                const tabHeader = orderTab.parentElement;
                const card = tabHeader.parentElement;
                card.appendChild(menuPanel);
                const orderNodes = [...card.children].filter((node) => node !== tabHeader && node !== menuPanel);

                const setActiveTab = (view) => {
                    const showMenu = view === 'menu';
                    orderNodes.forEach((node) => node.classList.toggle('hidden', showMenu));
                    menuPanel.classList.toggle('hidden', !showMenu);

                    orderTab.className = showMenu
                        ? 'hover:text-neutral-900 transition-colors pb-1.5 px-0.5'
                        : 'text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5 font-bold';
                    menuTab.className = showMenu
                        ? 'text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5 font-bold cursor-pointer'
                        : 'hover:text-neutral-900 transition-colors pb-1.5 px-0.5 cursor-pointer';

                    const url = new URL(window.location.href);
                    url.searchParams.set('view', showMenu ? 'menu' : 'orders');
                    window.history.replaceState({}, '', url);
                };

                orderTab.addEventListener('click', (event) => {
                    event.preventDefault();
                    setActiveTab('orders');
                });

                menuTab.addEventListener('click', (event) => {
                    event.preventDefault();
                    setActiveTab('menu');
                });

                setActiveTab(@json(request('view', 'orders')));
            });
        </script>
    @endif

    @if($isManager && request()->routeIs('manager.reports'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('a').forEach((link) => {
                    if (link.textContent.includes('Export Spreadsheet')) {
                        link.href = @json(route('manager.reports.export.excel'));
                    }
                    if (link.textContent.includes('Executive Print / PDF')) {
                        link.href = @json(route('manager.reports.export.pdf'));
                        link.removeAttribute('target');
                    }
                });
            });
        </script>
    @endif

    @if(request()->routeIs('admin.userandrole', 'manager.userandrole'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('span').forEach((node) => {
                    if (node.textContent.trim() === 'Inactive Roles') {
                        node.textContent = 'Inactive Accounts';
                    }
                });
            });
        </script>
    @endif
</x-admin-layout>
