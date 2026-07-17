<x-guest-layout>
    <div
        x-data="{ mobileNavOpen: false }"
        class="guest-portal guest-shell fixed inset-0 z-10 flex overflow-hidden bg-slate-100 text-slate-900 antialiased"
    >
        <style>
            [x-cloak] { display: none !important; }
            .guest-scrollbar { scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
            .guest-scrollbar::-webkit-scrollbar { width: 7px; height: 7px; }
            .guest-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .guest-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 9999px; }
            .guest-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        </style>

        <div
            x-show="mobileNavOpen"
            x-transition.opacity
            x-cloak
            class="fixed inset-0 z-40 bg-slate-950/40 backdrop-blur-sm lg:hidden"
            @click="mobileNavOpen = false"
            aria-hidden="true"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 flex h-dvh w-[17rem] flex-col overflow-hidden border-r border-slate-200 bg-white shadow-2xl transition-transform duration-300 lg:static lg:z-30 lg:translate-x-0 lg:shadow-none"
            :class="mobileNavOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="flex h-20 shrink-0 items-center justify-between border-b border-slate-200 px-5">
                <a href="{{ route('guest.dashboard') }}" class="flex min-w-0 items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <span class="flex h-11 min-w-32 items-center justify-center rounded-xl border border-slate-200 bg-white px-3 shadow-sm">
                        <x-brand-logo class="h-8 w-auto" />
                    </span>
                    <span class="sr-only">Oasis Hotel guest portal</span>
                </a>

                <button
                    type="button"
                    class="grid h-9 w-9 place-items-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden"
                    @click="mobileNavOpen = false"
                    aria-label="Close navigation"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="guest-scrollbar min-h-0 flex-1 overflow-y-auto px-3 py-5">
                <nav class="space-y-1">
                    <p class="mb-2 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">Overview</p>

                    @php
                        $guestNav = [
                            ['route' => 'guest.dashboard', 'icon' => 'fa-table-columns', 'label' => 'Dashboard'],
                            ['route' => 'guest.bookings.my', 'icon' => 'fa-calendar-check', 'label' => 'My Bookings'],
                            ['route' => 'guest.stay.my', 'icon' => 'fa-key', 'label' => 'My Stay'],
                        ];
                    @endphp

                    @foreach($guestNav as $item)
                        <a href="{{ route($item['route']) }}"
                           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ Request::routeIs($item['route']) ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs($item['route']) ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500' }}">
                                <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                            </span>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <p class="mb-2 mt-6 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">Hotel services</p>

                    <a href="{{ route('guest.restaurant.orders') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ Request::routeIs('guest.restaurant.orders') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.restaurant.orders') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500' }}">
                            <i class="fa-solid fa-utensils text-xs"></i>
                        </span>
                        Restaurant
                    </a>

                    <a href="{{ route('guest.facilities.booking') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ Request::routeIs('guest.facilities.booking') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.facilities.booking') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500' }}">
                            <i class="fa-solid fa-spa text-xs"></i>
                        </span>
                        Facilities
                    </a>

                    @if(Route::has('guest.room.service'))
                        <a href="{{ route('guest.room.service') }}"
                           class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ Request::routeIs('guest.room.service') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                            <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('guest.room.service') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500' }}">
                                <i class="fa-solid fa-bell-concierge text-xs"></i>
                            </span>
                            Room Service
                        </a>
                    @endif

                    <p class="mb-2 mt-6 px-3 text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-400">Account</p>

                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ Request::routeIs('profile.edit') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        <span class="grid h-8 w-8 place-items-center rounded-lg {{ Request::routeIs('profile.edit') ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 text-slate-500' }}">
                            <i class="fa-solid fa-user-gear text-xs"></i>
                        </span>
                        Profile Settings
                    </a>
                </nav>
            </div>

            <div class="shrink-0 border-t border-slate-200 p-4">
                <div class="mb-3 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-[0.1em] text-slate-400">Nusa Dua, Bali</p>
                        <p class="mt-0.5 text-xs text-slate-600">Hotel services online</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-semibold text-slate-900">{{ $temperature ?? '28°C' }}</p>
                        <p class="text-[10px] font-medium text-emerald-600"><i class="fa-solid fa-circle mr-1 text-[6px]"></i>Live</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-rose-600 transition hover:bg-rose-50">
                        <span class="grid h-8 w-8 place-items-center rounded-lg bg-rose-50">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </span>
                        Sign out
                    </button>
                </form>
            </div>
        </aside>

        <section class="flex h-dvh min-w-0 flex-1 flex-col overflow-hidden bg-slate-100">
            <header class="z-20 flex h-20 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-4 md:px-7 xl:px-9">
                <div class="flex min-w-0 items-center gap-3">
                    <button
                        type="button"
                        class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50 lg:hidden"
                        @click="mobileNavOpen = true"
                        aria-label="Open navigation"
                    >
                        <i class="fa-solid fa-bars"></i>
                    </button>

                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500">Welcome back</p>
                        <h1 class="truncate text-lg font-semibold tracking-tight text-slate-900 md:text-xl">{{ auth()->user()->name }}</h1>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <span class="hidden items-center gap-2 rounded-full border border-emerald-100 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700 sm:inline-flex">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Online
                    </span>
                    <a href="{{ route('home') }}" class="inline-flex h-10 items-center gap-2 rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-medium text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900">
                        <span class="hidden sm:inline">Hotel website</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                    </a>
                </div>
            </header>

            <main class="guest-content guest-scrollbar min-h-0 min-w-0 flex-1 overflow-x-hidden overflow-y-auto overscroll-contain">
                <div class="mx-auto w-full max-w-[1500px] p-4 md:p-6 xl:p-8">
                    {{ $slot }}
                </div>
            </main>
        </section>
    </div>
</x-guest-layout>
