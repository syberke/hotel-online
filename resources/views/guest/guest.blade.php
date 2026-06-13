

<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-b border-neutral-200 pb-6 mb-8 flex justify-between items-end">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-800">Guest Sanctuary</p>
                    <h1 class="text-2xl font-serif mt-1">Welcome back, {{ auth()->user()->name }}</h1>
                </div>
                <div class="text-[10px] font-bold uppercase tracking-widest bg-neutral-900 text-white px-3 py-1.5 rounded-none">
                    Membership Tier: Elite Gold
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white border border-neutral-200 p-6 rounded-none">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-100 pb-3 mb-4">
                            <i class="fa-solid fa-bag-shopping text-amber-800 mr-2"></i> Your Selection Cart (0)
                        </h3>
                        <p class="text-neutral-400 text-xs italic py-4 text-center">Your luxury cart is currently empty. Explore our rooms or culinary menus to add selections.</p>
                    </div>

                    <div class="bg-white border border-neutral-200 p-6 rounded-none">
                        <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-100 pb-3 mb-4">
                            <i class="fa-solid fa-calendar-check text-amber-800 mr-2"></i> Active Itinerary & Orders
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-neutral-600">
                                <thead class="bg-neutral-50 text-[10px] font-bold uppercase tracking-wider text-neutral-400 border-b border-neutral-200">
                                    <tr>
                                        <th class="p-3">Reference ID</th>
                                        <th class="p-3">Service / Room</th>
                                        <th class="p-3">Schedule Date</th>
                                        <th class="p-3">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100 font-medium">
                                    <tr>
                                        <td class="p-3 font-bold text-neutral-900">#OA-99212</td>
                                        <td class="p-3">Ocean Horizon Suite (Room 402)</td>
                                        <td class="p-3">June 24 - June 28, 2026</td>
                                        <td class="p-3"><span class="bg-amber-50 text-amber-800 text-[9px] font-bold uppercase tracking-wider px-2 py-0.5 border border-amber-200">Confirmed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white border border-neutral-200 p-6 rounded-none text-center">
                        <div class="w-16 h-16 bg-neutral-100 border border-neutral-200 flex items-center justify-center mx-auto mb-4 rounded-none text-neutral-400 text-xl font-serif">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <h4 class="text-sm font-bold uppercase tracking-wider text-neutral-900">{{ auth()->user()->name }}</h4>
                        <p class="text-[11px] text-neutral-400 font-medium mt-0.5">{{ auth()->user()->email }}</p>
                        <div class="border-t border-neutral-100 mt-5 pt-4">
                            <a href="{{ route('profile.edit') }}" class="block text-[10px] font-bold uppercase tracking-widest border border-neutral-300 py-2.5 hover:border-neutral-900 transition-colors">
                                Edit Sanctuary Settings
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @include('layouts.footer')
    </div>
</x-guest-layout>