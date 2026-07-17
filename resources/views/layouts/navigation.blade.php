<nav x-data="{ open: false }" class="public-navigation sticky top-0 z-50 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-20 items-center justify-between py-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" aria-label="Oasis Hotel home">
                <x-brand-logo class="h-10 w-auto" />
                <span class="hidden border-l border-slate-200 pl-3 sm:block">
                    <span class="block text-xs font-semibold text-slate-700">Hotel & Resort</span>
                    <span class="block text-[11px] text-slate-500">Nusa Dua, Bali</span>
                </span>
            </a>

            <div class="hidden items-center gap-1 lg:flex">
                @foreach([
                    ['route' => 'home', 'label' => 'Home', 'active' => 'home'],
                    ['route' => 'rooms', 'label' => 'Rooms', 'active' => 'rooms*'],
                    ['route' => 'facilities', 'label' => 'Facilities', 'active' => 'facilities'],
                    ['route' => 'restaurant', 'label' => 'Restaurant', 'active' => 'restaurant*'],
                    ['route' => 'contact', 'label' => 'Contact', 'active' => 'contact'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="rounded-xl px-4 py-2.5 text-sm font-medium transition {{ request()->routeIs($item['active']) ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </div>

            <div class="hidden items-center gap-3 lg:flex">
                @guest
                    <a href="{{ route('login') }}" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100 hover:text-slate-900">
                        Sign in
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-200/60 transition hover:bg-blue-700">
                        Create account
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                @endguest

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                                <span class="grid h-8 w-8 place-items-center rounded-lg bg-blue-50 text-blue-600">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                                <span class="max-w-32 truncate">{{ auth()->user()->name }}</span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white py-1 shadow-xl">
                                <x-dropdown-link :href="route('dashboard')" class="text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Dashboard
                                </x-dropdown-link>
                                <x-dropdown-link :href="route('profile.edit')" class="text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Profile settings
                                </x-dropdown-link>
                                <div class="my-1 border-t border-slate-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm font-medium text-rose-600 hover:bg-rose-50">
                                        Sign out
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <button type="button" @click="open = ! open" class="grid h-11 w-11 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 shadow-sm lg:hidden" aria-label="Toggle navigation">
                <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
            </button>
        </div>
    </div>

    <div x-show="open" x-transition x-cloak class="border-t border-slate-200 bg-white lg:hidden">
        <div class="mx-auto max-w-7xl space-y-1 px-4 py-4 sm:px-6">
            @foreach([
                ['route' => 'home', 'label' => 'Home', 'active' => 'home'],
                ['route' => 'rooms', 'label' => 'Rooms', 'active' => 'rooms*'],
                ['route' => 'facilities', 'label' => 'Facilities', 'active' => 'facilities'],
                ['route' => 'restaurant', 'label' => 'Restaurant', 'active' => 'restaurant*'],
                ['route' => 'contact', 'label' => 'Contact', 'active' => 'contact'],
            ] as $item)
                <a href="{{ route($item['route']) }}" class="block rounded-xl px-4 py-3 text-sm font-medium {{ request()->routeIs($item['active']) ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="mt-3 border-t border-slate-100 pt-3">
                @guest
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('login') }}" class="rounded-xl border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700">Sign in</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-blue-600 px-4 py-3 text-center text-sm font-semibold text-white">Register</a>
                    </div>
                @endguest

                @auth
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                        <p class="mt-0.5 text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>
                    <a href="{{ route('dashboard') }}" class="mt-2 block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="block rounded-xl px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-50">Profile settings</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full rounded-xl px-4 py-3 text-left text-sm font-medium text-rose-600 hover:bg-rose-50">Sign out</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</nav>
