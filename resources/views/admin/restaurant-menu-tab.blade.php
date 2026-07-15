<x-admin-dashboard-layout>
    @php
        $restaurantRoute = auth()->user()->role === 'manager'
            ? 'manager.restaurant'
            : 'admin.restaurant';
    @endphp

    @if(session('success'))
        <div class="bg-emerald-900/90 border border-emerald-700 text-emerald-200 p-4 text-xs font-semibold uppercase tracking-wider mb-6 flex items-center shadow-md">
            <i class="fa-solid fa-circle-check mr-2 text-emerald-400 text-sm"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-950/95 border border-rose-800 text-rose-300 p-4 text-xs font-semibold uppercase tracking-wider mb-6 flex items-center shadow-md">
            <i class="fa-solid fa-triangle-exclamation mr-2 text-rose-400 text-sm"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
            <div>
                <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-700">Restaurant Gastronomy</span>
                <h2 class="font-serif text-xl text-neutral-900 mt-1">Menu & Order Control</h2>
                <p class="text-[10px] text-neutral-400 mt-1">Kelola pesanan dan master menu dari modul Restaurant Gastronomy yang sama.</p>
            </div>

            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider">
                <a href="{{ route($restaurantRoute) }}"
                   class="px-4 py-2 border border-neutral-200 text-neutral-500 hover:text-neutral-900 hover:bg-neutral-50">
                    <i class="fa-solid fa-receipt mr-1.5"></i> Order Management
                </a>
                <a href="{{ route($restaurantRoute, ['view' => 'menu']) }}"
                   class="px-4 py-2 bg-neutral-950 text-white border border-neutral-950">
                    <i class="fa-solid fa-utensils mr-1.5 text-amber-400"></i> Today's Menu
                </a>
            </div>
        </div>

        @include('admin.partials.restaurant-menu-inline')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('restaurant-menu-inline-panel')?.classList.remove('hidden');
        });
    </script>
</x-admin-dashboard-layout>
