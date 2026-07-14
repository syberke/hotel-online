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

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Users</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['total'] }}</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> Live</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Accounts</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-emerald-700">{{ $stats['active'] }}</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">New Users <span class="text-neutral-400 font-normal lowercase">(Month)</span></span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-amber-600">{{ $stats['new'] }}</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Inactive Accounts</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-400">{{ $stats['inactive'] }}</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Distinct Roles</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">{{ $stats['roles'] }}</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-8 items-start w-full">
        <div class="xl:col-span-2 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Users Management</h3>
                    <span class="text-[9px] text-neutral-400 block mt-0.5">Role menentukan portal akses. Account status menentukan apakah akun boleh login.</span>
                </div>

                <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[200px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or role..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button type="submit" class="bg-neutral-900 text-white hover:bg-neutral-800 px-4 py-2 text-xs font-bold uppercase tracking-wider cursor-pointer">Filter</button>

                    @if(auth()->user()->role !== 'manager')
                        <button type="button" onclick="openAddUserModal()" class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-plus text-[10px]"></i> Add User</button>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                            <th class="py-3 px-4 font-semibold">User Identification</th>
                            <th class="py-3 px-4 font-semibold">Email</th>
                            <th class="py-3 px-4 font-semibold">System Role</th>
                            <th class="py-3 px-4 font-semibold">Account Status</th>
                            <th class="py-3 px-4 font-semibold">Creation Date</th>
                            <th class="py-3 px-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        @foreach($users as $u)
                            @php($accountStatus = $u->account_status ?? 'active')
                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4 flex items-center gap-3">
                                    <div class="w-7 h-7 bg-neutral-100 border text-neutral-700 text-[10px] font-bold uppercase flex items-center justify-center font-sans">
                                        {{ substr($u->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-neutral-900 block flex items-center gap-1.5">
                                            {{ $u->name }}
                                            @if($u->id == auth()->id())
                                                <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-[8px] px-1 py-0.1 uppercase font-mono scale-90">You</span>
                                            @endif
                                        </span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-neutral-500">{{ $u->email }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-purple-50 text-purple-800 border border-purple-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">{{ $u->role }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="{{ $accountStatus === 'active' ? 'bg-emerald-50 text-emerald-800 border-emerald-100' : 'bg-rose-50 text-rose-800 border-rose-100' }} border text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">
                                        {{ $accountStatus }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-neutral-400">{{ date('d M Y', strtotime($u->created_at ?? now())) }}</td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center justify-center gap-3">
                                        @if(auth()->user()->role !== 'manager')
                                            <button type="button" onclick="openEditUserModal({{ $u->id }})" class="text-blue-600 hover:text-blue-800 cursor-pointer font-bold uppercase text-[10px]"><i class="fa-regular fa-edit"></i> Edit</button>
                                            @if($u->id != auth()->id())
                                                <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" data-confirm="Hapus user staf ini secara permanen?" data-confirm-title="Hapus Akun Staf">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-800 cursor-pointer font-bold uppercase text-[10px]"><i class="fa-regular fa-trash-can"></i> Del</button>
                                                </form>
                                            @endif
                                        @else
                                            <span class="text-neutral-300 italic text-[10px]">Read-Only</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing entries {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results</span>
                <div class="font-sans text-neutral-800">{{ $users->links() }}</div>
            </div>
        </div>

        <div class="space-y-6 shrink-0 w-full flex flex-col justify-between">
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Roles Inventory</h3>
                        <span class="text-[9px] text-neutral-400 block mt-0.5">Active system users per structural role.</span>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] pb-2">
                                <th class="pb-2 font-semibold">Role Name</th>
                                <th class="pb-2 text-right font-semibold">Active Users</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50 font-medium text-neutral-600 text-[11px]">
                            @foreach($rolesCount as $rc)
                                <tr>
                                    <td class="py-2.5 font-bold text-neutral-900 uppercase font-sans text-[10px]"><i class="fa-solid fa-shield-halved mr-1.5 text-neutral-400"></i> {{ $rc->role }}</td>
                                    <td class="font-mono text-right font-bold text-neutral-900">{{ $rc->total }} Users</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Module Protections Blueprint</h3>
                </div>
                <div class="grid grid-cols-3 gap-2 text-center text-neutral-500 font-medium">
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <i class="fa-regular fa-calendar-check text-purple-700 text-xs"></i>
                        <span class="text-[8px] text-neutral-800 block tracking-tight font-bold uppercase mt-1">Reservations</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-bed text-blue-700 text-xs"></i>
                        <span class="text-[8px] text-neutral-800 block tracking-tight font-bold uppercase mt-1">Rooms Stock</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-file-invoice-dollar text-emerald-700 text-xs"></i>
                        <span class="text-[8px] text-neutral-800 block tracking-tight font-bold uppercase mt-1">Ledger Finance</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalAddUser" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Register System User</h4>
                <button type="button" onclick="closeAddUserModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Full Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Corporate Email Address</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Password Assignment</label>
                    <input type="password" name="password" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">System Role</label>
                        <select name="role" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold uppercase">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="guest">Guest</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Account Status</label>
                        <select name="account_status" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold uppercase">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Publish User Credentials</button>
            </form>
        </div>
    </div>

    <div id="modalEditUser" class="fixed inset-0 bg-neutral-950/50 backdrop-blur-xs flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white border border-neutral-200 max-w-sm w-full p-6 shadow-2xl font-sans flex flex-col">
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3 mb-4">
                <h4 class="text-xs font-bold uppercase tracking-widest text-neutral-900">Modify User Parameters</h4>
                <button type="button" onclick="closeEditUserModal()" class="text-neutral-400 hover:text-neutral-900 text-sm cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="formEditUser" action="" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Full Name</label>
                    <input type="text" name="name" id="edit_user_name" required class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Email (Read-Only)</label>
                    <input type="email" id="edit_user_email" readonly class="w-full px-3 py-2 text-xs border border-neutral-200 bg-neutral-100 text-neutral-400 font-mono outline-none cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Force New Password (Leave empty to keep current)</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">System Role</label>
                        <select name="role" id="edit_user_role" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold uppercase">
                            <option value="admin">Admin</option>
                            <option value="manager">Manager</option>
                            <option value="receptionist">Receptionist</option>
                            <option value="guest">Guest</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-wider text-neutral-400 mb-1">Account Status</label>
                        <select name="account_status" id="edit_user_account_status" class="w-full px-3 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 bg-neutral-50/50 font-semibold uppercase">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full bg-neutral-950 hover:bg-neutral-900 text-white font-bold text-[10px] uppercase tracking-widest py-2.5 cursor-pointer mt-2">Sync Access Modifications</button>
            </form>
        </div>
    </div>

</x-admin-dashboard-layout>

<script type="text/javascript">
    function openAddUserModal() {
        document.getElementById('modalAddUser').classList.remove('hidden');
    }

    function closeAddUserModal() {
        document.getElementById('modalAddUser').classList.add('hidden');
    }

    function openEditUserModal(id) {
        fetch(`/admin/users/${id}/json-detail`)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('edit_user_name').value = res.data.name;
                    document.getElementById('edit_user_email').value = res.data.email;
                    document.getElementById('edit_user_role').value = res.data.role;
                    document.getElementById('edit_user_account_status').value = res.data.account_status || 'active';
                    document.getElementById('formEditUser').action = `/admin/users/${id}/update`;
                    document.getElementById('modalEditUser').classList.remove('hidden');
                } else {
                    OasisDialog.error('Gagal mengambil manifes kredensial akun.');
                }
            })
            .catch(() => OasisDialog.error('Gagal menghubungi ledger akun.'));
    }

    function closeEditUserModal() {
        document.getElementById('modalEditUser').classList.add('hidden');
    }
</script>
