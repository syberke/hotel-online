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
        $navGroups = [
            'Overview' => [
                [$portalPrefix . '.dashboard', 'fa-table-columns', 'Dashboard'],
            ],
            'Operations' => array_values(array_filter([
                [Route::has($portalPrefix . '.reservation') ? $portalPrefix . '.reservation' : null, 'fa-calendar-check', 'Reservations'],
                [Route::has($portalPrefix . '.frontdesk') ? $portalPrefix . '.frontdesk' : null, 'fa-bell-concierge', 'Front Desk'],
                [Route::has($portalPrefix . '.rooms') ? $portalPrefix . '.rooms' : null, 'fa-bed', 'Rooms & Inventory'],
                [Route::has($portalPrefix . '.roomservice') ? $portalPrefix . '.roomservice' : null, 'fa-bowl-food', 'Room Service'],
                [Route::has($portalPrefix . '.restaurant') ? $portalPrefix . '.restaurant' : null, 'fa-utensils', 'Restaurant'],
                [Route::has($portalPrefix . '.facilities') ? $portalPrefix . '.facilities' : null, 'fa-spa', 'Facilities'],
            ], fn ($item) => filled($item[0]))),
            'Reports & access' => array_values(array_filter([
                [Route::has($portalPrefix . '.finance') ? $portalPrefix . '.finance' : null, 'fa-file-invoice-dollar', 'Finance'],
                [Route::has($portalPrefix . '.reports') ? $portalPrefix . '.reports' : null, 'fa-chart-column', 'Reports'],
                [Route::has($portalPrefix . '.contact-messages') ? $portalPrefix . '.contact-messages' : null, 'fa-inbox', 'Contact Inbox'],
                [Route::has($portalPrefix . '.userandrole') ? $portalPrefix . '.userandrole' : null, 'fa-users-gear', 'Users & Roles'],
                [Route::has('profile.edit') ? 'profile.edit' : null, 'fa-user-gear', 'Profile Settings'],
            ], fn ($item) => filled($item[0]))),
        ];
    @endphp

    <div x-data="{ mobileNavOpen: false }" class="staff-portal-shell fixed inset-0 z-10 flex overflow-hidden bg-slate-100 text-slate-900">
        <div x-show="mobileNavOpen" x-transition.opacity x-cloak class="fixed inset-0 z-40 bg-slate-950/45 backdrop-blur-sm lg:hidden" @click="mobileNavOpen = false"></div>

        <aside class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[16rem] flex-col overflow-hidden border-r border-slate-800 bg-slate-950 text-slate-300 shadow-2xl transition-transform duration-300 lg:static lg:z-30 lg:translate-x-0 lg:shadow-none" :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex h-[4.5rem] shrink-0 items-center justify-between border-b border-slate-800 px-5">
                <a href="{{ route($portalPrefix . '.dashboard') }}" class="oasis-logo-transparent inline-flex">
                    <x-brand-logo class="h-9 w-auto brightness-0 invert" />
                </a>
                <button type="button" class="grid h-9 w-9 place-items-center rounded-xl text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden" @click="mobileNavOpen = false" aria-label="Close navigation"><i class="fa-solid fa-xmark"></i></button>
            </div>

            <div class="custom-scrollbar min-h-0 flex-1 overflow-y-auto px-3 py-4" data-preserve-scroll="{{ $isManager ? 'manager-sidebar' : 'admin-sidebar' }}">
                <div class="mb-4 rounded-xl border border-slate-800 bg-slate-900/70 p-3">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-500">{{ $isManager ? 'Manager workspace' : 'Admin workspace' }}</p>
                    <p class="mt-1 text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="mt-1 text-xs text-slate-400">Live hotel operations</p>
                </div>

                <nav class="space-y-5">
                    @foreach($navGroups as $groupLabel => $items)
                        <div>
                            <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-500">{{ $groupLabel }}</p>
                            <div class="space-y-1">
                                @foreach($items as [$routeName, $icon, $label])
                                    @php($isActive = request()->routeIs($routeName))
                                    <a href="{{ route($routeName) }}" @if($isActive) aria-current="page" @endif class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-blue-600 text-white shadow-lg shadow-blue-950/30' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-lg {{ $isActive ? 'bg-white/15' : 'bg-slate-900 text-slate-400' }}"><i class="fa-solid {{ $icon }} text-xs"></i></span>
                                        <span class="truncate">{{ $label }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>
            </div>

            <div class="shrink-0 border-t border-slate-800 p-4">
                <div class="mb-3 flex items-center justify-between rounded-xl bg-slate-900 p-3 text-xs"><div><p class="font-semibold text-slate-200">Nusa Dua, Bali</p><p class="mt-1 text-slate-500">Property node</p></div><span class="inline-flex items-center gap-1.5 font-semibold text-emerald-400"><span class="h-2 w-2 rounded-full bg-emerald-400"></span>Live</span></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-300 transition hover:bg-rose-500/10 hover:text-rose-200"><span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-500/10"><i class="fa-solid fa-arrow-right-from-bracket text-xs"></i></span>Sign out</button></form>
            </div>
        </aside>

        <section class="flex h-dvh min-w-0 flex-1 flex-col overflow-hidden">
            <header class="staff-topbar flex h-[4.5rem] shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 md:px-6">
                <div class="flex min-w-0 items-center gap-3"><button type="button" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" @click="mobileNavOpen = true" aria-label="Open navigation"><i class="fa-solid fa-bars"></i></button><div class="min-w-0"><p class="text-xs font-medium text-slate-500">{{ $isManager ? 'Business management' : 'Hotel administration' }}</p><h1 class="truncate text-lg font-semibold tracking-tight text-slate-900">{{ $isManager ? 'Manager Portal' : 'Admin Portal' }}</h1></div></div>
                <div class="flex items-center gap-2 sm:gap-3"><span class="hidden items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 sm:inline-flex"><span class="h-2 w-2 rounded-full bg-emerald-500"></span>Online</span><a href="{{ route('home') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50"><span class="hidden sm:inline">Hotel website</span><i class="fa-solid fa-arrow-up-right-from-square text-xs"></i></a></div>
            </header>

            <main class="staff-main-content custom-scrollbar min-h-0 flex-1 overflow-x-hidden overflow-y-auto bg-slate-100 p-4 md:p-5 xl:p-6">
                <div class="mx-auto w-full max-w-[1600px] space-y-5">
                    @if($isManager && $managerReportSection)
                        <section class="flex min-w-0 flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between"><div class="min-w-0"><p class="text-sm font-semibold text-blue-600">Manager report export</p><p class="mt-1 text-sm text-slate-500">Export {{ ucwords(str_replace(['_', '-'], ' ', $managerReportSection)) }} using current database records.</p></div><div class="flex flex-wrap gap-2"><a href="{{ route('manager.section-report.excel', ['section' => $managerReportSection]) }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50"><i class="fa-solid fa-file-excel text-emerald-600"></i>Excel</a><a href="{{ route('manager.section-report.pdf', ['section' => $managerReportSection]) }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-file-pdf"></i>PDF</a></div></section>
                    @endif

                    @if(request()->routeIs('admin.facilities') && !$isManager)
                        <section class="flex min-w-0 flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between"><div class="min-w-0"><p class="text-sm font-semibold text-blue-600">Facility master data</p><p class="mt-1 text-sm text-slate-500">Add a new facility area to the hotel inventory.</p></div><button type="button" onclick="openCreateFacilityModal()" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-plus text-xs"></i>Add facility</button></section>
                    @endif

                    @if(request()->routeIs('admin.rooms', 'manager.rooms'))
                        @include('admin.partials.room-status-guide')
                    @endif

                    @if(request()->routeIs('admin.finance', 'manager.finance'))
                        @include('admin.partials.finance-runtime-fixes')
                    @endif

                    {{ $slot }}

                    @if(request()->routeIs('admin.restaurant', 'manager.restaurant') && isset($menus))
                        @include('admin.partials.restaurant-menu-inline')
                        <x-restaurant-venue-manager />
                    @endif
                </div>
            </main>
        </section>
    </div>

    @if($isManager && request()->routeIs('manager.reports'))
        <script>document.addEventListener('DOMContentLoaded', () => { document.querySelectorAll('a').forEach((link) => { if (link.textContent.includes('Export Spreadsheet')) link.href = @json(route('manager.reports.export.excel')); if (link.textContent.includes('Executive Print / PDF')) { link.href = @json(route('manager.reports.export.pdf')); link.removeAttribute('target'); } }); });</script>
    @endif

    @if(request()->routeIs('admin.userandrole', 'manager.userandrole'))
        <script>document.addEventListener('DOMContentLoaded', () => { document.querySelectorAll('span').forEach((node) => { if (node.textContent.trim() === 'Inactive Roles') node.textContent = 'Inactive Accounts'; }); });</script>
    @endif
</x-admin-layout>
