<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-b border-neutral-200 pb-6 mb-8">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-neutral-500">Front Desk Hub</p>
                <h1 class="text-2xl font-serif mt-1">Receptionist Terminal Desk</h1>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Expected Check-Ins Today</h4>
                    <p class="text-3xl font-light font-serif text-neutral-900">14 Rooms</p>
                </div>
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Expected Check-Outs Today</h4>
                    <p class="text-3xl font-light font-serif text-neutral-900">9 Rooms</p>
                </div>
                <div class="bg-white border border-neutral-200 p-6 rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Housekeeping Queue</h4>
                    <p class="text-3xl font-light font-serif text-amber-800">4 Rooms Pending</p>
                </div>
            </div>
            <div class="bg-white border border-neutral-200 p-6 rounded-none">
                <h3 class="text-xs font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-100 pb-3 mb-4">Realtime Keycard Room Tracker</h3>
                <p class="text-neutral-500 text-xs">Manage quick passenger check-ins, verify digital receipts, assign physical key matrix allocations, and monitor live room sanitization signals.</p>
            </div>
        </div>
        @include('layouts.footer')
    </div>
</x-guest-layout>