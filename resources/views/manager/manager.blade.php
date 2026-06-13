<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-b border-neutral-200 pb-6 mb-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-800">Corporate Intelligence Matrix</p>
                <h1 class="text-2xl font-serif mt-1">General Manager Analytics</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Monthly Gross Revenue</h4>
                    <p class="text-3xl font-light font-serif text-neutral-900">Rp 1.42B</p>
                </div>
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Average Resort Occupancy</h4>
                    <p class="text-3xl font-light font-serif text-neutral-900">84.2%</p>
                </div>
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">YTD Financial Growth</h4>
                    <p class="text-3xl font-light font-serif text-amber-800">+12.4%</p>
                </div>
            </div>
            <div class="bg-white border border-neutral-200 p-6 rounded-none">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-100 pb-3 mb-4">Strategic Auditing Logs</h3>
                <p class="text-neutral-500 text-xs">Financial summary metrics, seasonal pricing indexes, and department budgeting evaluations are accessible inside this executive layout portal.</p>
            </div>
        </div>
        @include('layouts.footer')
    </div>
</x-guest-layout>