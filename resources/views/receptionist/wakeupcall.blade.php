<x-receptionist-dashboard-layout>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start w-full text-xs">
        
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                
                <div class="flex flex-wrap text-[10px] font-bold uppercase tracking-wider text-neutral-400 gap-4 border-b border-neutral-100 pb-1 font-sans">
                    <button class="text-neutral-900 border-b-2 border-neutral-900 pb-2 px-0.5 font-bold">All Wake-up Calls (14)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Due Today (6)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Upcoming (5)</button>
                    <button class="hover:text-neutral-900 pb-2 px-0.5">Completed (3)</button>
                </div>

                <div class="flex items-center gap-3 w-full">
                    <div class="relative flex-1">
                        <input type="text" placeholder="Search by guest name, room, or phone..." class="w-full pr-3 pl-9 py-2 border border-neutral-200 focus:outline-none focus:border-neutral-900 font-medium placeholder-neutral-400 bg-neutral-50/50">
                        <i class="fa-solid fa-magnifying-glass text-neutral-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    </div>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-3 py-2 font-bold uppercase tracking-wider text-neutral-700 bg-white flex items-center gap-1.5 rounded-none"><i class="fa-solid fa-filter text-[10px] text-neutral-400"></i> Filter</button>
                </div>

                <div class="space-y-2">
                    <span class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 block mb-1"><i class="fa-regular fa-clock mr-1 text-blue-600"></i> Due Today (6)</span>
                    
                    <div class="divide-y border border-neutral-100 rounded-none bg-white font-semibold text-neutral-600">
                        <div class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer bg-blue-50/20 border-l-2 border-blue-600">
                            <div class="flex items-center gap-3">
                                <div class="w-10 text-center font-mono text-neutral-900 font-bold">
                                    <span class="block text-sm">1205</span>
                                    <span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Ocean</span>
                                </div>
                                <div class="border-l pl-3">
                                    <span class="text-neutral-900 font-bold block">Mr. John Anderson</span>
                                    <span class="text-[9px] text-neutral-400 font-normal block mt-0.5"><i class="fa-solid fa-users text-[8px] mr-1"></i>2 Adults, 0 Children</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono text-neutral-900 font-bold text-sm block">07:00 AM</span>
                                <span class="bg-emerald-50 text-emerald-800 text-[8px] px-1.5 py-0.2 uppercase font-sans font-bold border border-emerald-100 rounded-none mt-1 inline-block">Confirmed</span>
                            </div>
                        </div>

                        <div class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 text-center font-mono text-neutral-900 font-bold">
                                    <span class="block text-sm">1402</span>
                                    <span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Suite</span>
                                </div>
                                <div class="border-l pl-3">
                                    <span class="text-neutral-900 font-bold block">Mrs. Sophia Taylor</span>
                                    <span class="text-[9px] text-neutral-400 font-normal block mt-0.5"><i class="fa-solid fa-users text-[8px] mr-1"></i>2 Adults, 0 Children</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono text-neutral-900 font-bold text-sm block">06:30 AM</span>
                                <span class="bg-emerald-50 text-emerald-800 text-[8px] px-1.5 py-0.2 uppercase font-sans font-bold border border-emerald-100 rounded-none mt-1 inline-block">Confirmed</span>
                            </div>
                        </div>

                        <div class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 text-center font-mono text-neutral-900 font-bold">
                                    <span class="block text-sm">1003</span>
                                    <span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Superior</span>
                                </div>
                                <div class="border-l pl-3">
                                    <span class="text-neutral-900 font-bold block">Mr. David Wilson</span>
                                    <span class="text-[9px] text-neutral-400 font-normal block mt-0.5"><i class="fa-solid fa-user text-[8px] mr-1"></i>1 Adult, 0 Children</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono text-neutral-900 font-bold text-sm block">05:45 AM</span>
                                <span class="bg-amber-50 text-amber-800 text-[8px] px-1.5 py-0.2 uppercase font-sans font-bold border border-amber-100 rounded-none mt-1 inline-block">In Progress</span>
                            </div>
                        </div>

                        <div class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 text-center font-mono text-neutral-900 font-bold">
                                    <span class="block text-sm">1501</span>
                                    <span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Exec</span>
                                </div>
                                <div class="border-l pl-3">
                                    <span class="text-neutral-900 font-bold block">Ms. Olivia Martinez</span>
                                    <span class="text-[9px] text-neutral-400 font-normal block mt-0.5"><i class="fa-solid fa-users text-[8px] mr-1"></i>2 Adults, 0 Children</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="font-mono text-neutral-900 font-bold text-sm block">07:30 AM</span>
                                <span class="bg-blue-50 text-blue-800 text-[8px] px-1.5 py-0.2 uppercase font-sans font-bold border border-blue-100 rounded-none mt-1 inline-block">Pending</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 pt-1">
                    <span class="text-[9px] uppercase tracking-wider font-bold text-neutral-400 block mb-1"><i class="fa-regular fa-calendar-days text-purple-600 mr-1"></i> Upcoming (5)</span>
                    
                    <div class="divide-y border border-neutral-100 rounded-none bg-white font-semibold text-neutral-600">
                        <div class="p-3 flex items-center justify-between hover:bg-neutral-50/50 transition-colors cursor-pointer">
                            <div class="flex items-center gap-3">
                                <div class="w-10 text-center font-mono text-neutral-900 font-bold"><span class="block text-sm">1102</span><span class="text-[8px] uppercase tracking-tighter text-neutral-400 block font-sans">Superior</span></div>
                                <div class="border-l pl-3">
                                    <span class="text-neutral-900 font-bold block">Mr. Ethan Roberts</span>
                                    <span class="text-[9px] text-neutral-400 font-mono font-normal block mt-0.5">18 Jun 2026 • 08:00 AM</span>
                                </div>
                            </div>
                            <span class="bg-purple-50 text-purple-800 border border-purple-100 text-[8px] font-bold px-1.5 py-0.5 uppercase rounded-none tracking-wide">Scheduled</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center text-[10px] text-neutral-400 font-medium pt-1">
                    <span>Showing 1 to 6 of 14 wake-up calls</span>
                    <div class="flex gap-1 font-mono font-bold text-neutral-800">
                        <button class="w-5 h-5 border flex items-center justify-center bg-neutral-50 cursor-not-allowed"><i class="fa-solid fa-chevron-left text-[8px]"></i></button>
                        <button class="w-5 h-5 bg-neutral-900 border border-neutral-900 text-white">1</button>
                        <button class="w-5 h-5 border hover:border-neutral-400 flex items-center justify-center bg-white">2</button>
                        <button class="w-5 h-5 border flex items-center justify-center bg-white hover:bg-neutral-50"><i class="fa-solid fa-chevron-right text-[8px]"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-4">
            
            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Wake-up Call Details</h3>
                    <span class="bg-emerald-50 text-emerald-700 border border-emerald-100 text-[8px] font-bold px-1.5 uppercase font-sans tracking-wide rounded-none">Confirmed</span>
                </div>

                <div class="flex items-center gap-3.5 py-1">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100" class="w-10 h-10 object-cover border">
                    <div>
                        <h4 class="text-sm font-bold text-neutral-900">Mr. John Anderson</h4>
                        <span class="text-[9px] text-neutral-400 font-mono block mt-0.5">john.anderson@email.com • +62 812 3456 7890</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 border-t pt-3 font-mono text-neutral-900 font-bold text-center">
                    <div class="bg-neutral-50 p-2 border"><span class="text-[8px] font-sans uppercase font-bold text-neutral-400 block mb-0.5">Room</span>1205</div>
                    <div class="bg-neutral-50 p-2 border"><span class="text-[8px] font-sans uppercase font-bold text-neutral-400 block mb-0.5">Type</span>Deluxe Ocean</div>
                    <div class="bg-neutral-50 p-2 border font-sans"><span class="text-[8px] uppercase font-bold text-neutral-400 block mb-0.5 font-sans">Stay Period</span>17 – 20 Jun</div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                <div class="flex justify-between items-center border-b pb-2">
                    <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide">Wake-up Call Information</h3>
                    <button class="border border-neutral-200 hover:bg-neutral-50 px-2.5 py-1 text-[9px] font-bold uppercase tracking-wide text-neutral-700 bg-white flex items-center gap-1 cursor-pointer rounded-none"><i class="fa-solid fa-pen text-[8px] text-blue-600"></i> Edit</button>
                </div>

                <div class="space-y-3.5 font-semibold text-neutral-700">
                    <div class="flex items-center justify-between gap-4">
                        <span class="text-neutral-400 font-normal"><i class="fa-regular fa-clock w-4 mr-1 text-center"></i> Wake-up Time</span>
                        <div class="flex items-center gap-1 font-mono text-neutral-900 font-bold">
                            <input type="text" value="07:00" class="border p-1 text-center w-16 text-sm bg-neutral-50" readonly>
                            <span class="bg-blue-600 text-white px-2 py-1 text-[10px] uppercase font-sans">AM</span>
                            <span class="border p-1 px-2 text-[10px] bg-white text-neutral-400 hover:text-neutral-900 cursor-pointer font-sans">PM</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-neutral-400 font-normal"><i class="fa-regular fa-calendar w-4 mr-1 text-center"></i> Date</span>
                        <input type="text" value="17 Jun 2026" class="border p-1.5 font-mono text-[11px] bg-neutral-50 text-right w-40 font-bold text-neutral-900" readonly>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-neutral-400 font-normal"><i class="fa-solid fa-arrows-spin w-4 mr-1 text-center"></i> Repeat</span>
                        <select class="border p-1.5 bg-neutral-50 focus:outline-none text-right w-40 font-bold text-neutral-900" disabled>
                            <option>One Time</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-neutral-400 font-normal"><i class="fa-solid fa-phone-volume w-4 mr-1 text-center"></i> Method</span>
                        <select class="border p-1.5 bg-neutral-50 focus:outline-none text-right w-40 font-bold text-neutral-900" disabled>
                            <option>Phone Call</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-between gap-4">
                        <span class="text-neutral-400 font-normal"><i class="fa-solid fa-hashtag w-4 mr-1 text-center"></i> Phone Number</span>
                        <input type="text" value="+62 812 3456 7890" class="border p-1.5 font-mono text-[11px] bg-neutral-50 text-right w-44 font-bold text-neutral-900" readonly>
                    </div>

                    <div class="border-t pt-3">
                        <span class="text-neutral-400 font-normal block mb-1.5"><i class="fa-solid fa-note-sticky w-4 mr-1 text-center"></i> Notes (Optional)</span>
                        <span class="text-neutral-900 block font-medium bg-neutral-50 p-2.5 border border-neutral-100 leading-relaxed">Please call the room phone.</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 shadow-sm p-5 space-y-4">
                <h3 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Call Status</h3>
                
                <div class="relative pl-5 space-y-4 border-l border-neutral-200 ml-2 font-semibold">
                    <div class="relative">
                        <span class="absolute -left-[25px] top-0 w-2.5 h-2.5 rounded-full bg-blue-600 border-2 border-white ring-4 ring-blue-100"></span>
                        <div class="flex justify-between items-baseline">
                            <span class="text-neutral-900 block font-bold">Scheduled</span>
                            <span class="text-[9px] text-neutral-400 font-mono font-normal">17 Jun 2026, 09:00 AM</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">By Alicia (Receptionist)</span>
                    </div>

                    <div class="relative">
                        <span class="absolute -left-[24px] top-0 w-2 h-2 rounded-full bg-amber-500 border border-white"></span>
                        <div class="flex justify-between items-baseline">
                            <span class="text-amber-700 block font-bold">In Progress</span>
                            <span class="text-[9px] text-neutral-400 font-mono font-normal">17 Jun 2026, 05:45 AM</span>
                        </div>
                        <span class="text-[10px] text-neutral-400 block font-normal mt-0.5">Call will be made at 07:00 AM</span>
                    </div>

                    <div class="relative opacity-40">
                        <span class="absolute -left-[24px] top-0 w-2 h-2 rounded-full bg-neutral-300 border border-white"></span>
                        <div class="flex justify-between items-baseline">
                            <span class="text-neutral-500 block font-bold">Completed</span>
                            <span class="text-[9px] text-neutral-300 font-mono font-normal">—</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="lg:col-span-3 space-y-4 shrink-0">
            
            <div class="bg-white border border-neutral-200 p-5 shadow-sm space-y-4">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Today's Summary</h4>
                
                <div class="grid grid-cols-2 gap-3 font-semibold text-center text-neutral-500">
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <div class="text-blue-600 text-sm"><i class="fa-regular fa-clock"></i></div>
                        <span class="text-lg font-bold text-neutral-900 block font-mono mt-1">6</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans">Due Today</span>
                    </div>
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <div class="text-purple-600 text-sm"><i class="fa-regular fa-calendar-days"></i></div>
                        <span class="text-lg font-bold text-neutral-900 block font-mono mt-1">5</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans">Upcoming</span>
                    </div>
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <div class="text-amber-600 text-sm"><i class="fa-solid fa-phone-flip"></i></div>
                        <span class="text-lg font-bold text-neutral-900 block font-mono mt-1">1</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans">In Progress</span>
                    </div>
                    <div class="bg-neutral-50 border p-3 flex flex-col justify-center items-center">
                        <div class="text-emerald-600 text-sm"><i class="fa-solid fa-circle-check"></i></div>
                        <span class="text-lg font-bold text-neutral-900 block font-mono mt-1">3</span>
                        <span class="text-[8px] text-neutral-400 block uppercase tracking-wide font-sans">Completed</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-2 text-[11px] font-bold text-neutral-700 uppercase tracking-wide shadow-sm">
                <h4 class="font-serif text-xs text-neutral-900 border-b pb-2 tracking-normal mb-1">Quick Actions</h4>
                
                <div class="space-y-2 font-sans normal-case">
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-plus text-blue-600 text-center w-4 text-xs"></i> Add Wake-up Call
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-list-ul text-neutral-400 text-center w-4 text-xs"></i> View All Wake-up Calls
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-regular fa-file-lines text-neutral-400 text-center w-4 text-xs"></i> Wake-up Call Log
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-bed text-neutral-400 text-center w-4 text-xs"></i> Room Status
                    </button>
                    <button class="w-full border border-neutral-200 hover:border-neutral-900 p-2.5 flex items-center gap-2.5 transition-colors bg-white font-semibold text-xs rounded-none text-left cursor-pointer">
                        <i class="fa-solid fa-print text-neutral-400 text-center w-4 text-xs"></i> Print List (Today)
                    </button>
                </div>
            </div>

            <div class="bg-white border border-neutral-200 p-5 space-y-3 text-xs font-semibold text-neutral-600 shadow-sm">
                <h4 class="font-serif text-xs font-bold text-neutral-900 uppercase tracking-wide border-b pb-2">Important Notes</h4>
                <div class="space-y-2.5 font-sans normal-case text-neutral-500 text-[11px] leading-relaxed">
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-info text-blue-600 text-xs mt-0.5"></i> 
                        <span class="font-medium text-neutral-600">Wake-up calls will be made 5 minutes after the scheduled time.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs mt-0.5"></i> 
                        <span class="font-medium text-neutral-600">Please ensure the guest phone number is correct.</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-xs mt-0.5"></i> 
                        <span class="font-medium text-neutral-600">You will be notified for all pending wake-up calls.</span>
                    </div>
                </div>
            </div>
        </aside>

    </div>

</x-receptionist-dashboard-layout>