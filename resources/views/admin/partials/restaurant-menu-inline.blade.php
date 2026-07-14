<div id="restaurant-menu-inline-panel" class="hidden space-y-5 pt-1">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
        <div>
            <h3 class="font-serif text-sm text-neutral-900 font-bold tracking-wide">Today's Menu Master Data</h3>
            <p class="text-[10px] text-neutral-400 mt-1">Kelola menu langsung dari halaman Restaurant Gastronomy.</p>
        </div>

        @if(auth()->user()->role !== 'manager')
            <form action="{{ route('admin.restaurant.menu.store') }}" method="POST" class="bg-neutral-50 border border-neutral-200 p-4 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-3 w-full xl:max-w-4xl">
                @csrf
                <input type="text" name="name" required placeholder="Menu name" class="px-3 py-2 text-xs border border-neutral-200 bg-white focus:outline-none focus:border-neutral-900">
                <input type="number" name="price" min="0" step="0.01" required placeholder="Price" class="px-3 py-2 text-xs border border-neutral-200 bg-white focus:outline-none focus:border-neutral-900 font-mono">
                <input type="text" name="description" placeholder="Description" class="px-3 py-2 text-xs border border-neutral-200 bg-white focus:outline-none focus:border-neutral-900">
                <input type="url" name="foto_url" placeholder="Image URL" class="px-3 py-2 text-xs border border-neutral-200 bg-white focus:outline-none focus:border-neutral-900">
                <button type="submit" class="bg-neutral-950 hover:bg-neutral-800 text-white px-4 py-2 text-[10px] font-bold uppercase tracking-wider cursor-pointer">
                    <i class="fa-solid fa-plus mr-1"></i> Add Menu
                </button>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto custom-scrollbar">
        <table class="w-full text-left text-xs whitespace-nowrap">
            <thead>
                <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                    <th class="py-3 px-4">Picture</th>
                    <th class="py-3 px-4">Item Name</th>
                    <th class="py-3 px-4">Description</th>
                    <th class="py-3 px-4">Unit Price</th>
                    @if(auth()->user()->role !== 'manager')
                        <th class="py-3 px-4 text-center">Delete</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                @forelse($menus as $menu)
                    <tr class="hover:bg-neutral-50/40">
                        <td class="py-3 px-4">
                            <img src="{{ $menu->foto_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100' }}" alt="{{ $menu->name }}" class="w-12 h-10 object-cover border border-neutral-200">
                        </td>
                        <td class="py-3 px-4 font-bold text-neutral-900">{{ $menu->name }}</td>
                        <td class="py-3 px-4 text-neutral-400 max-w-sm truncate" title="{{ $menu->description }}">{{ $menu->description ?: '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        @if(auth()->user()->role !== 'manager')
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.restaurant.menu.delete', $menu->id) }}" method="POST" class="inline-block" data-confirm="Hapus menu {{ $menu->name }}?" data-confirm-title="Hapus Menu">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-800 text-[10px] font-bold uppercase cursor-pointer">
                                        <i class="fa-regular fa-trash-can mr-1"></i> Delete
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center text-neutral-400 italic">Belum ada menu pada master data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-neutral-50 border border-neutral-100 px-4 py-3 text-[10px] text-neutral-500">
        Total master menu: <strong class="font-mono text-neutral-900">{{ $menus->count() }}</strong> item.
    </div>
</div>
