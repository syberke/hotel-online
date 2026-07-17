<section id="restaurant-menu-inline-panel" class="hidden space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
    @php($menuCategories = ['Appetizers', 'Main Courses', 'Seafood', 'Steak Selection', 'Desserts', 'Beverages'])

    <div class="flex flex-col gap-5 border-b border-slate-100 pb-5 xl:flex-row xl:items-start xl:justify-between">
        <div>
            <p class="text-sm font-semibold text-blue-600">Restaurant master data</p>
            <h3 class="mt-1 text-xl font-semibold tracking-tight text-slate-900">Menu catalog</h3>
            <p class="mt-2 text-sm text-slate-500">Category and availability are stored in the database and drive the public menu filters.</p>
        </div>

        @if(auth()->user()->role === 'admin')
            <form action="{{ route('admin.restaurant.menu.store') }}" method="POST" class="grid w-full gap-3 rounded-2xl bg-slate-50 p-4 sm:grid-cols-2 xl:max-w-5xl xl:grid-cols-6">
                @csrf
                <input type="text" name="name" required placeholder="Menu name" class="px-3 py-2.5 text-sm xl:col-span-2">
                <select name="category" required class="px-3 py-2.5 text-sm"><option value="">Category</option>@foreach($menuCategories as $category)<option value="{{ $category }}">{{ $category }}</option>@endforeach</select>
                <input type="number" name="price" min="0" step="0.01" required placeholder="Price" class="px-3 py-2.5 text-sm">
                <input type="url" name="foto_url" placeholder="Image URL" class="px-3 py-2.5 text-sm xl:col-span-2">
                <textarea name="description" rows="2" placeholder="Description" class="px-3 py-2.5 text-sm sm:col-span-2 xl:col-span-4"></textarea>
                <label class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 text-sm text-slate-600"><input type="checkbox" name="is_available" value="1" checked>Available</label>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-plus text-xs"></i>Add menu</button>
            </form>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-[1100px] text-left text-sm">
            <thead class="bg-slate-50 text-xs font-semibold text-slate-500"><tr><th class="px-4 py-3">Menu item</th><th class="px-4 py-3">Category</th><th class="px-4 py-3">Description</th><th class="px-4 py-3">Price</th><th class="px-4 py-3">Availability</th>@if(auth()->user()->role === 'admin')<th class="px-4 py-3 text-right">Actions</th>@endif</tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($menus as $menu)
                    @php($updateFormId = 'inline-menu-update-' . $menu->id)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-4 align-top">
                            <div class="flex min-w-72 items-start gap-3">
                                <img src="{{ $menu->foto_url ?: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=300' }}" alt="{{ $menu->name }}" class="h-16 w-20 rounded-xl object-cover">
                                <div class="min-w-0">
                                    @if(auth()->user()->role === 'admin')
                                        <input form="{{ $updateFormId }}" type="text" name="name" value="{{ $menu->name }}" required class="w-full px-3 py-2 text-sm font-semibold">
                                        <input form="{{ $updateFormId }}" type="url" name="foto_url" value="{{ $menu->foto_url }}" class="mt-2 w-full px-3 py-2 text-xs" placeholder="Image URL">
                                    @else
                                        <p class="font-semibold text-slate-900">{{ $menu->name }}</p>
                                        <p class="mt-1 max-w-xs truncate text-xs text-slate-400" title="{{ $menu->foto_url }}">{{ $menu->foto_url }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <select form="{{ $updateFormId }}" name="category" required class="w-44 px-3 py-2 text-sm">@foreach($menuCategories as $category)<option value="{{ $category }}" {{ $menu->category === $category ? 'selected' : '' }}>{{ $category }}</option>@endforeach</select>
                            @else
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $menu->category }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <textarea form="{{ $updateFormId }}" name="description" rows="3" class="w-72 px-3 py-2 text-sm">{{ $menu->description }}</textarea>
                            @else
                                <p class="max-w-sm whitespace-normal text-sm leading-6 text-slate-500">{{ $menu->description ?: '-' }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <input form="{{ $updateFormId }}" type="number" name="price" value="{{ $menu->price }}" min="0" step="0.01" required class="w-36 px-3 py-2 text-sm font-semibold">
                            @else
                                <span class="font-semibold text-slate-900">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            @if(auth()->user()->role === 'admin')
                                <label class="flex items-center gap-2 text-sm text-slate-600"><input form="{{ $updateFormId }}" type="checkbox" name="is_available" value="1" {{ $menu->is_available ? 'checked' : '' }}>Available</label>
                            @else
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $menu->is_available ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $menu->is_available ? 'Available' : 'Hidden' }}</span>
                            @endif
                        </td>
                        @if(auth()->user()->role === 'admin')
                            <td class="px-4 py-4 text-right align-top">
                                <form id="{{ $updateFormId }}" action="{{ route('admin.restaurant.menu.update', $menu->id) }}" method="POST" class="hidden">@csrf</form>
                                <div class="flex justify-end gap-2"><button form="{{ $updateFormId }}" type="submit" class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Save</button><form action="{{ route('admin.restaurant.menu.delete', $menu->id) }}" method="POST" data-confirm="Hapus menu {{ $menu->name }}?" data-confirm-title="Hapus Menu">@csrf @method('DELETE')<button type="submit" class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button></form></div>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-12 text-center text-sm text-slate-500">No menu items are stored yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-xl bg-slate-50 px-4 py-3 text-xs text-slate-500">Total menu items: <strong class="text-slate-900">{{ $menus->count() }}</strong>. Public menu only shows items marked available.</div>
</section>
