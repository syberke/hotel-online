<x-admin-dashboard-layout>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Users</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">48</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 6.7%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Active Users</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-emerald-700">42</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 7.4%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">New Users <span class="text-neutral-400 font-normal lowercase">(Month)</span></span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-amber-600">6</span>
                <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 20.0%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Inactive Users</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-400">4</span>
                <span class="text-[10px] font-bold text-red-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-down text-[8px]"></i> 20.0%</span>
            </div>
        </div>
        <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
            <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Roles</span>
            <div class="flex items-baseline justify-between mt-2">
                <span class="text-3xl font-light font-serif text-neutral-900">7</span>
                <span class="text-[9px] text-neutral-400 font-normal">Active Roles</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mt-8 items-start w-full">
        
        <div class="xl:col-span-2 bg-white border border-neutral-200 shadow-sm p-6 space-y-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-neutral-100 pb-4">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Users</h3>
                    <span class="text-[9px] text-neutral-400 block mt-0.5">Manage all system users and their access.</span>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none md:min-w-[200px]">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" placeholder="Search by name, email, or role..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                    </div>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                    
                    @if(auth()->user()->role !== 'manager')
                        <button class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 flex items-center gap-1.5 transition-colors shadow-sm cursor-pointer"><i class="fa-solid fa-plus text-[10px]"></i> Add User</button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left text-xs whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                            <th class="py-3 px-4 font-semibold">User</th>
                            <th class="py-3 px-4 font-semibold">Email</th>
                            <th class="py-3 px-4 font-semibold">Role</th>
                            <th class="py-3 px-4 font-semibold">Department</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold">Last Login</th>
                            <th class="py-3 px-4 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100" class="w-7 h-7 object-cover border rounded-none">
                                <div>
                                    <span class="font-bold text-neutral-900 block flex items-center gap-1.5">Admin User <span class="bg-emerald-100 text-emerald-800 border border-emerald-200 text-[8px] px-1 py-0.1 uppercase scale-90 font-mono">You</span></span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">admin@oasishotel.com</td>
                            <td class="py-3.5 px-4"><span class="bg-purple-50 text-purple-800 border border-purple-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Administrator</span></td>
                            <td class="py-3.5 px-4 text-neutral-500">System</td>
                            <td class="py-3.5 px-4"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide">Active</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">23 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">09:15 AM</span></td>
                            <td class="py-3.5 px-4 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=100" class="w-7 h-7 object-cover border rounded-none">
                                <div><span class="font-bold text-neutral-900 block">Sarah Johnson</span></div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">sarah.j@oasishotel.com</td>
                            <td class="py-3.5 px-4"><span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Front Desk</span></td>
                            <td class="py-3.5 px-4 text-neutral-500">Front Office</td>
                            <td class="py-3.5 px-4"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide">Active</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">23 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">08:45 AM</span></td>
                            <td class="py-3.5 px-4 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-7 h-7 object-cover border rounded-none">
                                <div><span class="font-bold text-neutral-900 block">Emily Davis</span></div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-500">emily.d@oasishotel.com</td>
                            <td class="py-3.5 px-4"><span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Housekeeping</span></td>
                            <td class="py-3.5 px-4 text-neutral-500">Housekeeping</td>
                            <td class="py-3.5 px-4"><span class="bg-emerald-50 text-emerald-800 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide">Active</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-700">23 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">07:50 AM</span></td>
                            <td class="py-3.5 px-4 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                        <tr class="hover:bg-neutral-50/40 transition-colors bg-neutral-50/30">
                            <td class="py-3.5 px-4 flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=100" class="w-7 h-7 object-cover border rounded-none">
                                <div><span class="font-bold text-neutral-400 block">Michael Brown</span></div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-neutral-400">michael.b@oasishotel.com</td>
                            <td class="py-3.5 px-4"><span class="bg-neutral-100 text-neutral-500 border border-neutral-200 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Spa Therapist</span></td>
                            <td class="py-3.5 px-4 text-neutral-400">Spa & Wellness</td>
                            <td class="py-3.5 px-4"><span class="bg-neutral-100 text-neutral-400 text-[8px] font-bold px-1.5 py-0.5 uppercase tracking-wide">Inactive</span></td>
                            <td class="py-3.5 px-4 font-mono text-neutral-400">&mdash;</td>
                            <td class="py-3.5 px-4 text-center">
                                @if(auth()->user()->role !== 'manager')
                                    <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                @else
                                    <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-1 font-medium">
                <span>Showing 1 to 4 of 48 results</span>
                <div class="flex items-center gap-1 font-mono text-neutral-800">
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                    <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">2</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">3</button>
                    <span class="px-0.5 text-neutral-300">...</span>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white">5</button>
                    <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                </div>
            </div>
        </div>

        <div class="space-y-6 shrink-0 w-full flex flex-col justify-between">
            
            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-neutral-100 pb-3">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Roles</h3>
                        <span class="text-[9px] text-neutral-400 block mt-0.5">Manage user roles and permissions.</span>
                    </div>
                    
                    @if(auth()->user()->role !== 'manager')
                        <button class="bg-amber-800 hover:bg-amber-900 text-white font-bold text-[10px] uppercase tracking-wider px-3 py-1.5 transition-colors cursor-pointer"><i class="fa-solid fa-plus text-[9px] mr-1"></i> Add Role</button>
                    @endif
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] pb-2">
                                <th class="pb-2 font-semibold">Role</th>
                                <th class="pb-2 font-semibold">Users</th>
                                <th class="pb-2 font-semibold">Description</th>
                                <th class="pb-2 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-50 font-medium text-neutral-600 text-[11px]">
                            <tr>
                                <td class="py-2.5 font-bold text-neutral-900">Administrator</td>
                                <td class="font-mono">1</td>
                                <td class="text-neutral-400 truncate max-w-[120px]" title="Full access to all system features">Full access to all system...</td>
                                <td class="text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    @else
                                        <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-neutral-900">Manager</td>
                                <td class="font-mono">3</td>
                                <td class="text-neutral-400 truncate max-w-[120px]">Access to reports and...</td>
                                <td class="text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    @else
                                        <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-neutral-900">Front Desk</td>
                                <td class="font-mono">8</td>
                                <td class="text-neutral-400 truncate max-w-[120px]">Manage reservations,...</td>
                                <td class="text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    @else
                                        <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="py-2.5 font-bold text-neutral-900">Housekeeping</td>
                                <td class="font-mono">6</td>
                                <td class="text-neutral-400 truncate max-w-[120px]">Manage rooms, cleaning...</td>
                                <td class="text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="text-neutral-400 hover:text-neutral-900 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    @else
                                        <button class="text-neutral-300 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-6 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-neutral-100 pb-3">
                    <div>
                        <h3 class="font-serif text-sm text-neutral-900 font-medium tracking-wide">Permission Overview</h3>
                        <span class="text-[9px] text-neutral-400 block mt-0.5">Summary of system permissions by module.</span>
                    </div>
                    <a href="#" class="text-[9px] font-bold text-amber-800 uppercase tracking-widest hover:underline">View Details</a>
                </div>

                <div class="grid grid-cols-5 gap-2 text-center text-neutral-500 font-medium">
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <div class="text-neutral-400 text-xs"><i class="fa-regular fa-calendar-check text-purple-700"></i></div>
                        <span class="text-xs font-bold text-neutral-900 block mt-1 font-mono">12</span>
                        <span class="text-[8px] text-neutral-400 block tracking-tight">Reservations</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <div class="text-neutral-400 text-xs"><i class="fa-solid fa-bed text-blue-700"></i></div>
                        <span class="text-xs font-bold text-neutral-900 block mt-1 font-mono">15</span>
                        <span class="text-[8px] text-neutral-400 block tracking-tight">Rooms</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <div class="text-neutral-400 text-xs"><i class="fa-solid fa-file-invoice-dollar text-emerald-700"></i></div>
                        <span class="text-xs font-bold text-neutral-900 block mt-1 font-mono">18</span>
                        <span class="text-[8px] text-neutral-400 block tracking-tight">Finance</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <div class="text-neutral-400 text-xs"><i class="fa-solid fa-chart-line text-amber-700"></i></div>
                        <span class="text-xs font-bold text-neutral-900 block mt-1 font-mono">14</span>
                        <span class="text-[8px] text-neutral-400 block tracking-tight">Reports</span>
                    </div>
                    <div class="bg-neutral-50 border p-2 flex flex-col items-center justify-center">
                        <div class="text-neutral-400 text-xs"><i class="fa-solid fa-sliders text-neutral-500"></i></div>
                        <span class="text-xs font-bold text-neutral-900 block mt-1 font-mono">20</span>
                        <span class="text-[8px] text-neutral-400 block tracking-tight">Settings</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-admin-dashboard-layout>