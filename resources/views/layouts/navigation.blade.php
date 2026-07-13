<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 12"
     :class="scrolled ? 'bg-white/95 shadow-[0_12px_35px_-22px_rgba(28,25,23,.45)] border-neutral-200/80' : 'bg-[#fffefa]/90 border-neutral-200/50'"
     class="sticky top-0 z-50 border-b backdrop-blur-xl transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-[4.75rem] justify-between">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="block select-none transition-transform duration-300 hover:scale-[1.04]" aria-label="Oasis Hotel home">
                        <img src="{{ asset('logo.svg') }}" alt="Oasis Hotel" class="h-10 w-auto object-contain">
                    </a>
                </div>

                <div class="hidden sm:flex sm:space-x-8 sm:ms-12">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-xs uppercase tracking-widest font-bold">
                        Home
                    </x-nav-link>

                    <x-nav-link :href="route('rooms')" :active="request()->routeIs('rooms')" class="text-xs uppercase tracking-widest font-bold">
                        Rooms
                    </x-nav-link>

                    <x-nav-link :href="route('facilities')" :active="request()->routeIs('facilities')" class="text-xs uppercase tracking-widest font-bold">
                        Facilities
                    </x-nav-link>

                    <x-nav-link :href="route('restaurant')" :active="request()->routeIs('restaurant')" class="text-xs uppercase tracking-widest font-bold">
                        Restaurant
                    </x-nav-link>

                    <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-xs uppercase tracking-widest font-bold">
                        Contact
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">
                
               

                @guest
                    <div class="flex items-center space-x-6">
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-neutral-900 hover:underline transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="oasis-button-primary px-5 py-2.5">
                            Register
                        </a>
                    </div>
                @endguest

                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center rounded-none border border-neutral-300 bg-white px-4 py-2.5 text-xs font-bold uppercase tracking-widest text-neutral-800 hover:bg-neutral-50 transition-all focus:outline-none">
                                <div class="w-1.5 h-1.5 rounded-full bg-amber-600 me-2 animate-pulse"></div>
                                <div>{{ auth()->user()->name }}</div>
                                <div class="ms-2 text-neutral-400">
                                    <svg class="h-3 w-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="rounded-none border border-neutral-200 bg-white shadow-lg py-1">
                                <x-dropdown-link :href="route('dashboard')" class="text-xs uppercase tracking-wider font-bold text-neutral-700 py-2.5 hover:bg-neutral-50">
                                    Dashboard
                                </x-dropdown-link>

                                <x-dropdown-link :href="route('profile.edit')" class="text-xs uppercase tracking-wider font-bold text-neutral-700 py-2.5 hover:bg-neutral-50">
                                    Profile Settings
                                </x-dropdown-link>

                                <div class="border-t border-neutral-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs uppercase tracking-wider font-bold text-red-700 py-2.5 hover:bg-red-50/50">
                                        Logout
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                @endauth
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center rounded-none p-2 text-neutral-400 hover:bg-neutral-50 hover:text-neutral-900 transition-colors focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="sm:hidden bg-white/95 backdrop-blur-xl border-t border-neutral-100 shadow-xl">
        <div class="space-y-1 pt-3 pb-4 px-3">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')" class="text-xs uppercase tracking-wider font-bold py-2.5">
                Home
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('rooms')" :active="request()->routeIs('rooms')" class="text-xs uppercase tracking-wider font-bold py-2.5">
                Rooms
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('facilities')" :active="request()->routeIs('facilities')" class="text-xs uppercase tracking-wider font-bold py-2.5">
                Facilities
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('restaurant')" :active="request()->routeIs('restaurant')" class="text-xs uppercase tracking-wider font-bold py-2.5">
                Restaurant
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')" class="text-xs uppercase tracking-wider font-bold py-2.5">
                Contact
            </x-responsive-nav-link>
        </div>

        @auth
            <div class="border-t border-neutral-100 pt-4 pb-4 px-4 bg-neutral-50/50">
                <div class="flex items-center px-2 mb-3">
                    <div>
                        <div class="text-sm font-bold uppercase tracking-wide text-neutral-800">{{ auth()->user()->name }}</div>
                        <div class="text-xs font-medium text-neutral-400 mt-0.5">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-xs uppercase tracking-wider font-bold">
                        Dashboard
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')" class="text-xs uppercase tracking-wider font-bold">
                        Profile Settings
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-xs uppercase tracking-wider font-bold text-red-600">
                            Logout
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
            <div class="border-t border-neutral-100 pt-4 pb-6 px-4 bg-neutral-50/50 flex flex-col gap-3">
                <a href="{{ route('login') }}" class="flex justify-center w-full border border-neutral-300 px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-neutral-900 bg-white hover:bg-neutral-50 transition-colors rounded-none">
                    Login
                </a>
                <a href="{{ route('register') }}" class="flex justify-center w-full bg-neutral-900 px-4 py-3 text-center text-xs font-bold uppercase tracking-widest text-white hover:bg-neutral-800 transition-colors rounded-none">
                    Register
                </a>
            </div>
        @endguest
    </div>
</nav>
