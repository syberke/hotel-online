<x-admin-dashboard-layout>
    @php
        $restaurantRoute = auth()->user()->role === 'manager'
            ? 'manager.restaurant'
            : 'admin.restaurant';
    @endphp

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-100 pb-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">Restaurant operations</p>
                <h2 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">Menu, venues, and reservations</h2>
                <p class="mt-2 text-sm text-slate-500">The menu tab uses the same staff shell and database-backed venue workspace.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route($restaurantRoute, ['view' => 'orders']) }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50">Order Management</a>
                <a href="{{ route($restaurantRoute, ['view' => 'menu']) }}" class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">Today's Menu</a>
            </div>
        </div>
    </section>
</x-admin-dashboard-layout>
