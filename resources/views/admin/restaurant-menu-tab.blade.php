<x-admin-dashboard-layout>
    @php
        $restaurantRoute = auth()->user()->role === 'manager' ? 'manager.restaurant' : 'admin.restaurant';
    @endphp

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex min-w-0 flex-col gap-4 border-b border-slate-100 pb-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-blue-600">Restaurant operations</p>
                <h2 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">Today’s menu, venues, and table reservations</h2>
                <p class="mt-2 text-sm text-slate-500">All sections below use real database records. Admin can manage data, while Manager receives read-only access.</p>
            </div>
            <div class="flex max-w-full flex-wrap items-center gap-2">
                <a href="{{ route($restaurantRoute, ['view' => 'orders']) }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50">Order management</a>
                <a href="#restaurant-menu-inline-panel" class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700">Today’s menu</a>
                <a href="#restaurant-venue-management" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-500 hover:bg-slate-50">Venues & reservations</a>
            </div>
        </div>
    </section>
</x-admin-dashboard-layout>
