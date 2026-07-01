<x-admin-dashboard-layout>

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

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-6">
            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
                
                <div class="flex text-xs font-bold uppercase tracking-wider text-neutral-400 gap-6 border-b border-neutral-100 pb-3">
                    <a href="{{ route('admin.restaurant') }}" class="hover:text-neutral-900 transition-colors pb-1.5 px-0.5">Order Management</a>
                    <a href="{{ route('admin.restaurant.menu') }}" class="text-neutral-900 border-b-2 border-neutral-900 pb-1.5 px-0.5">Today's Menu</a>
                </div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pt-1">
                    <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative min-w-[240px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search food or beverage..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider transition-colors">Filter</button>
                    </form>

                    @if(auth()->user()->role !== 'manager')
                        <button type="button" onclick="openCreateModal()" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2.5 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer">
                            <i class="fa-solid fa-plus text-[10px]"></i> Add New Menu
                        </button>
                    @endif
                </div>

                <div class="overflow-x-auto custom-scrollbar pt-2">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/30">
                                <th class="py-3 px-4 font-semibold">Menu Picture</th>
                                <th class="py-3 px-4 font-semibold">Item Name</th>
                                <th class="py-3 px-4 font-semibold">Description</th>
                                <th class="py-3 px-4 font-semibold">Unit Price</th>
                                @if(auth()->user()->role !== 'manager')
                                    <th class="py-3 px-4 text-center font-semibold">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            @forelse($menus as $menu)
                                <tr class="hover:bg-neutral-50/40 transition-colors">
                                    <td class="py-3 px-4">
                                        <img src="{{ $menu->foto_url ?? 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=100' }}" class="w-12 h-10 object-cover border border-neutral-200 rounded-xs">
                                    </td>
                                    <td class="py-3 px-4 font-bold text-neutral-900 text-xs">{{ $menu->name }}</td>
                                    <td class="py-3 px-4 text-neutral-400 max-w-xs truncate" title="{{ $menu->description }}">{{ $menu->description ?? '-' }}</td>
                                    <td class="py-3 px-4 font-mono font-bold text-neutral-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                                    
                                    @if(auth()->user()->role !== 'manager')
                                        <td class="py-3 px-4 text-center space-x-2">
                                            <button type="button" onclick="openEditModal({{ json_encode($menu) }})" class="text-blue-600 hover:text-blue-800 text-xs font-bold uppercase cursor-pointer"><i class="fa-regular fa-pen-to-square"></i> Edit</button>
                                            <form action="{{ route('admin.restaurant.menu.delete', $menu->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus menu ini secara permanen?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-bold uppercase cursor-pointer"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-neutral-400 italic">No food/beverage menus found in database records.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                    <span>Showing entries {{ $menus->firstItem() ?? 0 }} to {{ $menus->lastItem() ?? 0 }} of {{ $menus->total() }} items</span>
                    <div class="font-sans text-neutral-800">
                        {{ $menus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="createModal" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl flex flex-col font-sans">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Publish Gastronomy Menu</h4>
                <button type="button" onclick="closeCreateModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.restaurant.menu.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Item Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Unit Price (Rupiah)</label>
                    <input type="number" name="price" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Photo URL Address</label>
                    <input type="url" name="foto_url" placeholder="https://..." class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer">Save Menu</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl flex flex-col font-sans">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Modify Menu Configuration</h4>
                <button type="button" onclick="closeEditModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="editForm" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Item Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Unit Price (Rupiah)</label>
                    <input type="number" name="price" id="edit_price" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Description</label>
                    <textarea name="description" id="edit_description" rows="2" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50"></textarea>
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Photo URL Address</label>
                    <input type="url" name="foto_url" id="edit_foto_url" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div class="flex gap-2 pt-2">
                    <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer">Update Menu</button>
                </div>
            </form>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function openCreateModal() { document.getElementById('createModal').classList.remove('hidden'); }
    function openEditModal(menu) {
        document.getElementById('edit_name').value = menu.name;
        document.getElementById('edit_price').value = Math.round(menu.price);
        document.getElementById('edit_description').value = menu.description ?? '';
        document.getElementById('edit_foto_url').value = menu.foto_url ?? '';
        document.getElementById('editForm').action = `/admin/restaurant/menu/${menu.id}/update`;
        document.getElementById('editModal').classList.remove('hidden');
    }
    function closeCreateModal() { document.getElementById('createModal').classList.add('hidden'); }
    function closeEditModal() { document.getElementById('editModal').classList.add('hidden'); }
</script>