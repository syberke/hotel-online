<x-admin-dashboard-layout>

    <div class="flex flex-col xl:flex-row gap-8 items-start w-full">
        
        <div class="flex-1 w-full space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Total Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-neutral-900">86</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 18.4%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Pending Queue</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-amber-600">16</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 23.1%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">In Progress</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-blue-600">24</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 6.7%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Completed</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-emerald-700">42</span>
                        <span class="text-[10px] font-bold text-emerald-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-up text-[8px]"></i> 12.5%</span>
                    </div>
                </div>
                <div class="bg-white p-5 border border-neutral-200/60 flex flex-col justify-between shadow-sm">
                    <span class="text-[9px] font-bold text-neutral-400 uppercase tracking-wider block">Cancelled Orders</span>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-3xl font-light font-serif text-red-600">4</span>
                        <span class="text-[10px] font-bold text-red-600 flex items-center gap-0.5"><i class="fa-solid fa-arrow-down text-[8px]"></i> 11.1%</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 border-b border-neutral-100 pb-2">
                    <div class="flex flex-wrap text-xs font-bold uppercase tracking-wider text-neutral-400 gap-5">
                        <button class="text-neutral-900 border-b-2 border-neutral-900 pb-3 px-0.5 flex items-center gap-1">All Orders <span class="text-[10px] text-neutral-400 font-mono">(86)</span></button>
                        <button class="hover:text-neutral-900 transition-colors pb-3 px-0.5 flex items-center gap-1">Pending <span class="text-[10px] text-neutral-400 font-mono">(16)</span></button>
                        <button class="hover:text-neutral-900 transition-colors pb-3 px-0.5 flex items-center gap-1">In Progress <span class="text-[10px] text-neutral-400 font-mono">(24)</span></button>
                        <button class="hover:text-neutral-900 transition-colors pb-3 px-0.5 flex items-center gap-1">Completed <span class="text-[10px] text-neutral-400 font-mono">(42)</span></button>
                        <button class="hover:text-neutral-900 transition-colors pb-3 px-0.5 flex items-center gap-1">Cancelled <span class="text-[10px] text-neutral-400 font-mono">(4)</span></button>
                    </div>

                    <div class="flex items-center gap-3 w-full lg:w-auto">
                        <div class="relative flex-1 lg:flex-none lg:min-w-[220px]">
                            <i class="fa-solid fa-magnifying-glass text-neutral-400 text-xs absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input type="text" placeholder="Search by order ID, guest, room..." class="w-full pl-9 pr-4 py-2 text-xs border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        </div>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 flex items-center gap-1.5 bg-white"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                        <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 text-xs font-bold uppercase tracking-wider text-neutral-700 bg-white">More Filters</button>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left text-xs whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-neutral-100 text-neutral-400 uppercase tracking-wider font-bold text-[9px] bg-neutral-50/40">
                                <th class="py-3 px-4 font-semibold">Order ID</th>
                                <th class="py-3 px-4 font-semibold">Guest</th>
                                <th class="py-3 px-4 font-semibold">Room</th>
                                <th class="py-3 px-4 font-semibold">Order Time</th>
                                <th class="py-3 px-4 font-semibold">Delivery Time</th>
                                <th class="py-3 px-4 font-semibold">Status</th>
                                <th class="py-3 px-4 font-semibold">Order Type</th>
                                <th class="py-3 px-4 font-semibold">Amount</th>
                                <th class="py-3 px-4 text-center font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-100 font-medium text-neutral-600">
                            <tr class="hover:bg-neutral-50/40 transition-colors bg-amber-50/10 border-l-2 border-amber-600">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">RS-250617-0086</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-6 h-6 object-cover border border-neutral-200">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">David Thompson</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">+62 812 3456 7890</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">1205</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Deluxe Ocean View</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">08:45 AM</span></td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">09:15 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-amber-50 text-amber-800 border border-amber-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Pending</span>
                                    <span class="text-[8px] text-neutral-400 block font-normal mt-1">Accepted</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">Delivery</td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 285.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">RS-250617-0085</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=100" class="w-6 h-6 object-cover border border-neutral-200">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">Sarah Johnson</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">+62 812 2345 6789</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">1502</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Premier Suite</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">08:20 AM</span></td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">08:50 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-blue-50 text-blue-800 border border-blue-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">In Progress</span>
                                    <span class="text-[8px] text-neutral-400 block font-normal mt-1">Preparing</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">Delivery</td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 415.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>

                            <tr class="hover:bg-neutral-50/40 transition-colors">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-neutral-900 block font-mono">RS-250617-0084</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">17 Jun 2026</span>
                                </td>
                                <td class="py-3.5 px-4 flex items-center gap-2.5">
                                    <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=100" class="w-6 h-6 object-cover border border-neutral-200">
                                    <div>
                                        <span class="font-bold text-neutral-900 block">James Wilson</span>
                                        <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">+62 811 1111 2222</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-neutral-900 font-bold block">1008</span>
                                    <span class="text-[9px] text-neutral-400 block font-normal mt-0.5">Executive Suite</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">07:50 AM</span></td>
                                <td class="py-3.5 px-4 text-neutral-700 font-mono">17 Jun 2026<span class="block text-[9px] text-neutral-400 font-normal mt-0.5">08:20 AM</span></td>
                                <td class="py-3.5 px-4">
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 text-[8px] px-2 py-0.5 font-bold uppercase tracking-wide">Completed</span>
                                    <span class="text-[8px] text-neutral-400 block font-normal mt-1">Delivered</span>
                                </td>
                                <td class="py-3.5 px-4 text-neutral-500">Delivery</td>
                                <td class="py-3.5 px-4 font-mono font-bold text-neutral-900">Rp 350.000</td>
                                <td class="py-3.5 px-4 text-center">
                                    @if(auth()->user()->role !== 'manager')
                                        <button class="w-7 h-7 bg-white border border-neutral-200 hover:bg-neutral-100 text-neutral-500 cursor-pointer"><i class="fa-solid fa-ellipsis-vertical text-xs"></i></button>
                                    @else
                                        <button class="w-7 h-7 bg-neutral-50 border border-neutral-200 text-neutral-400 cursor-not-allowed" title="Read-Only View Mode"><i class="fa-solid fa-eye text-xs"></i></button>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex justify-between items-center text-[11px] text-neutral-400 pt-2 font-medium">
                    <span>Showing 1 to 3 of 86 results</span>
                    <div class="flex items-center gap-1 font-mono text-neutral-800">
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center hover:bg-neutral-50"><i class="fa-solid fa-chevron-left text-[9px]"></i></button>
                        <button class="w-6 h-6 bg-neutral-900 border border-neutral-900 text-white font-bold">1</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">3</button>
                        <span class="px-0.5 text-neutral-300">...</span>
                        <button class="w-6 h-6 border border-neutral-200 hover:border-neutral-400 flex items-center justify-center bg-white">9</button>
                        <button class="w-6 h-6 border border-neutral-200 flex items-center justify-center hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[9px]"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <aside class="w-full xl:w-96 bg-white border border-neutral-200 shadow-sm p-6 space-y-5 shrink-0 relative">
            
            <div class="flex justify-between items-center border-b border-neutral-100 pb-3.5">
                <div>
                    <h3 class="font-serif text-sm text-neutral-900 tracking-wide font-medium">Order Details</h3>
                    <span class="text-neutral-500 font-mono text-[10px] block mt-1">RS-250617-0086 &bull; 17 Jun 2026</span>
                </div>
                <button class="text-neutral-400 hover:text-neutral-900 transition-colors"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <div class="space-y-2.5">
                <div class="flex justify-between items-baseline"><h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Guest Information</h4><a href="#" class="text-[9px] font-bold text-amber-800 uppercase hover:underline">View Profile</a></div>
                <div class="flex items-center gap-3.5 p-3 bg-neutral-50/60 border border-neutral-100">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-9 h-9 object-cover border border-neutral-200">
                    <div>
                        <span class="text-xs font-bold text-neutral-900 block flex items-center gap-1.5">David Thompson <span class="bg-amber-100 text-amber-900 border border-amber-200 font-mono font-bold text-[7px] px-1.5 py-0.1 tracking-normal uppercase">VIP</span></span>
                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">Room 1205 &bull; Deluxe Ocean View</span>
                    </div>
                </div>
            </div>

            <div class="space-y-2.5 pt-1.5 border-t border-neutral-100 text-xs font-medium text-neutral-600">
                <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Order Information</h4>
                <div class="grid grid-cols-2 gap-y-2 px-1">
                    <div><span class="text-neutral-400">Order Type</span></div><div class="text-right text-neutral-900 font-bold">Delivery</div>
                    <div><span class="text-neutral-400">Delivery Time</span></div><div class="text-right font-mono text-neutral-900">17 Jun 2026 &bull; 09:15 AM</div>
                    <div><span class="text-neutral-400">Special Request</span></div><div class="text-right text-amber-900 font-bold">Extra cutlery, No onion</div>
                    <div><span class="text-neutral-400">Ordered By</span></div><div class="text-right text-neutral-900">Guest</div>
                    <div><span class="text-neutral-400">Payment Method</span></div><div class="text-right text-neutral-800">Add to Room (Post to Folio)</div>
                </div>
            </div>

            <div class="space-y-2 pt-2 border-t border-neutral-100 text-xs">
                <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400 mb-1">Order Items</h4>
                <div class="divide-y divide-neutral-50 px-1 font-medium text-neutral-700">
                    <div class="flex justify-between py-1.5"><span>1x <span class="text-neutral-900 ml-1">Club Sandwich</span></span><span class="font-mono text-neutral-500">Rp 135.000</span></div>
                    <div class="flex justify-between py-1.5"><span>1x <span class="text-neutral-900 ml-1">Chicken Caesar Salad</span></span><span class="font-mono text-neutral-500">Rp 120.000</span></div>
                    <div class="flex justify-between py-1.5"><span>2x <span class="text-neutral-900 ml-1">Fresh Orange Juice</span></span><span class="font-mono text-neutral-500">Rp 60.000</span></div>
                    <div class="flex justify-between py-1.5"><span>1x <span class="text-neutral-900 ml-1">Chocolate Cake</span></span><span class="font-mono text-neutral-500">Rp 70.000</span></div>
                </div>
                
                <div class="bg-neutral-50/50 border border-neutral-100 p-3 space-y-1.5 text-neutral-500 font-medium text-[11px] mt-2">
                    <div class="flex justify-between"><span>Subtotal</span><span class="font-mono">Rp 385.000</span></div>
                    <div class="flex justify-between"><span>Service Charge (10%)</span><span class="font-mono">Rp 38.500</span></div>
                    <div class="flex justify-between pb-1.5 border-b border-neutral-200/60"><span>Tax (11%)</span><span class="font-mono">Rp 42.350</span></div>
                    <div class="flex justify-between pt-1 text-xs font-bold text-neutral-900"><span>Total Amount</span><span class="font-mono text-amber-950 text-sm">Rp 465.850</span></div>
                </div>
            </div>

            <div class="space-y-2.5 pt-2 border-t border-neutral-100">
                <h4 class="text-[9px] font-bold uppercase tracking-widest text-neutral-400">Order Timeline</h4>
                <div class="pl-2 space-y-3 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-px before:bg-neutral-200">
                    <div class="flex gap-4 items-start text-[10px] text-neutral-400 font-medium relative"><span class="w-2 h-2 bg-amber-600 outline outline-4 outline-amber-50 mt-1 shrink-0"></span><div><span class="text-neutral-900 font-bold block">Order Placed</span><span class="font-mono text-[9px] block mt-0.5">17 Jun 2026 &bull; 08:45 AM</span></div></div>
                    <div class="flex gap-4 items-start text-[10px] text-neutral-400 font-medium relative"><span class="w-2 h-2 bg-neutral-300 outline outline-4 outline-white mt-1 shrink-0"></span><div><span class="text-neutral-500 block">Accepted by Kitchen</span><span class="font-mono text-[9px] block mt-0.5">17 Jun 2026 &bull; 08:46 AM</span></div></div>
                    <div class="flex gap-4 items-start text-[10px] text-neutral-400 font-medium relative"><span class="w-2 h-2 bg-neutral-300 outline outline-4 outline-white mt-1 shrink-0"></span><div><span class="text-neutral-500 block">Preparing In Kitchen</span><span class="font-mono text-[9px] block mt-0.5">17 Jun 2026 &bull; 08:50 AM</span></div></div>
                </div>
            </div>

            <div class="pt-3 border-t border-neutral-100">
                @if(auth()->user()->role !== 'manager')
                    <div class="grid grid-cols-3 gap-2.5 text-center font-bold text-[9px] uppercase tracking-wider">
                        <button class="bg-white border border-neutral-200 hover:bg-neutral-50 text-red-600/90 py-2.5 shadow-sm cursor-pointer">Cancel Order</button>
                        <button class="bg-white border border-neutral-200 hover:bg-neutral-50 text-neutral-700 py-2.5 shadow-sm cursor-pointer">Edit Order</button>
                        <button class="bg-amber-800 hover:bg-amber-900 text-white py-2.5 shadow-sm cursor-pointer">Mark Delivered</button>
                    </div>
                @else
                    <div class="bg-neutral-50 border border-neutral-200 p-3 text-center select-none">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-neutral-400 flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-lock text-neutral-400 text-xs"></i> Locked View Mode (Read-Only)
                        </span>
                    </div>
                @endif
            </div>
        </aside>

    </div>

</x-admin-dashboard-layout>