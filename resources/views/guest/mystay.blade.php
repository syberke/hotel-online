<style>
    /* Mengubah scrollbar bawaan agar serasi dengan dashboard premium Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #171717; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #404040; 
        border-radius: 3px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #b45309; 
    }
</style>

<x-guest-dashboard-layout>
    <div class="min-h-screen bg-[#f5f5f3] text-neutral-900 font-sans antialiased flex flex-col md:flex-row">
       

        <main class="flex-1 p-6 lg:p-8 overflow-y-auto custom-scrollbar space-y-6">
            
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 pb-4 border-b border-neutral-200">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-widest text-neutral-400">Welcome Back, guest1</span>
                    <h2 class="text-3xl font-serif text-neutral-900 mt-0.5">My Stay</h2>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white border border-neutral-200 p-4 w-full lg:w-auto shadow-sm">
                    <div class="text-xs">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-calendar text-amber-700 mr-1"></i> Check-In</p>
                        <p class="font-bold text-neutral-800 mt-0.5">17 Jun 2026</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-calendar text-amber-700 mr-1"></i> Check-Out</p>
                        <p class="font-bold text-neutral-800 mt-0.5">18 Jun 2026</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-key text-amber-700 mr-1"></i> Room</p>
                        <p class="font-bold text-neutral-800 mt-0.5">Room 1205</p>
                    </div>
                    <div class="text-xs border-l border-neutral-200 pl-4">
                        <p class="text-[9px] font-bold uppercase tracking-wider text-neutral-400"><i class="fa-solid fa-hourglass-half text-amber-700 mr-1"></i> Stay Progress</p>
                        <p class="font-bold text-neutral-800 mt-0.5">Day 1 of 2</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <div class="lg:col-span-2 relative h-64 overflow-hidden bg-neutral-950 text-white shadow-lg border border-neutral-200 group">
                    <img src="https://images.unsplash.com/photo-1618773928121-c32242e63f39?q=80&w=1200" 
                         class="w-full h-full object-cover opacity-40 transition-transform duration-700 group-hover:scale-102" alt="Premium Enclave Suite">
                    <div class="absolute inset-0 p-6 flex flex-col justify-between bg-gradient-to-t from-black/80 via-black/20 to-transparent">
                        <div>
                            <span class="bg-amber-800 text-white text-[8px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-none">Current Stay</span>
                            <h3 class="text-2xl font-serif tracking-wide mt-2">Premium Enclave Suite</h3>
                            <p class="text-neutral-300 text-xs mt-1">Ocean View &bull; Room 1205 &bull; 2 Guests</p>
                        </div>
                        <div class="flex items-center justify-between border-t border-white/20 pt-4">
                            <div class="text-xs text-neutral-400">
                                Check-in: <span class="text-white font-bold">17 Jun 2026, 03:00 PM</span>
                            </div>
                            <a href="#" class="bg-white/10 hover:bg-white/20 text-white text-[10px] font-bold uppercase tracking-widest py-2 px-4 transition-colors">
                                View Room Details
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-neutral-900 text-white border border-neutral-800 p-6 shadow-lg flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Digital Room Key</h4>
                            <span class="inline-flex items-center gap-1.5 text-[9px] font-bold uppercase tracking-wider text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Active
                            </span>
                        </div>
                        <div class="flex items-center gap-4 bg-neutral-950 p-4 border border-neutral-800">
                            <div class="w-10 h-10 bg-neutral-900 flex items-center justify-center text-amber-500 border border-neutral-800"><i class="fa-solid fa-wifi text-lg"></i></div>
                            <div>
                                <p class="text-xs font-bold text-white">Room 1205</p>
                                <span class="text-[10px] text-neutral-500 font-medium">Premium Enclave Suite</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-center items-center py-2">
                        <div class="bg-white p-2 border-2 border-amber-600 shadow-md">
                            <div class="w-20 h-20 bg-neutral-900 flex items-center justify-center text-white text-[9px] font-mono tracking-tighter text-center p-1">
                                [ QR LOCK KEY ]
                            </div>
                        </div>
                    </div>

                    <button type="button" class="w-full bg-amber-700 hover:bg-amber-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors shadow-md">
                        <i class="fa-solid fa-unlock-keyhole mr-1.5"></i> Unlock Door
                    </button>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Today's Itinerary</h4>
                            <a href="#" class="text-[10px] font-bold uppercase tracking-wide text-neutral-900 underline hover:text-amber-700">View Full Schedule</a>
                        </div>
                        <div class="space-y-4 relative before:absolute before:inset-y-1 before:left-3 before:w-0.5 before:bg-neutral-100">
                            <div class="flex items-start gap-4 relative">
                                <div class="w-6 h-6 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-[10px] text-amber-800 z-10"><i class="fa-solid fa-mug-saucer"></i></div>
                                <div class="flex-1 text-xs">
                                    <div class="flex justify-between font-bold text-neutral-800">
                                        <span>Breakfast</span>
                                        <span class="text-emerald-700 bg-emerald-50 px-1.5 py-0.5 text-[9px] font-mono tracking-wider font-bold">AVAILABLE</span>
                                    </div>
                                    <p class="text-[10px] text-neutral-400 font-medium mt-0.5">06:00 AM - 10:00 AM &bull; Restaurant Oasis</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 relative">
                                <div class="w-6 h-6 rounded-full bg-amber-50 border border-amber-200 flex items-center justify-center text-[10px] text-amber-800 z-10"><i class="fa-solid fa-spa"></i></div>
                                <div class="flex-1 text-xs">
                                    <div class="flex justify-between font-bold text-neutral-800">
                                        <span>Spa Appointment</span>
                                        <span class="text-amber-700 bg-amber-50 px-1.5 py-0.5 text-[9px] font-mono tracking-wider font-bold">CONFIRMED</span>
                                    </div>
                                    <p class="text-[10px] text-neutral-400 font-medium mt-0.5">02:00 PM &bull; Luxury Spa Center</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Request Services</h4>
                            <a href="#" class="text-[10px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View All Services</a>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <button type="button" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5">
                                <i class="fa-solid fa-broom text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Housekeeping</span>
                            </button>
                            <button type="button" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5">
                                <i class="fa-solid fa-shirt text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Laundry</span>
                            </button>
                            <button type="button" class="border border-neutral-100 bg-neutral-50/50 p-3 hover:border-amber-600 transition-colors flex flex-col items-center justify-center gap-1.5">
                                <i class="fa-solid fa-mattress-pillow text-neutral-400 text-sm"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-neutral-700">Extra Pillows</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center pb-3 border-b border-neutral-100 mb-4">
                            <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-400">Current Bill</h4>
                            <a href="#" class="text-[10px] font-bold uppercase tracking-wide text-neutral-400 hover:text-neutral-900">View Full Bill</a>
                        </div>
                        <div class="space-y-2 text-xs font-medium text-neutral-600">
                            <div class="flex justify-between"><span>Room Charges</span><span class="font-mono text-neutral-800 font-bold">Rp 1.650.000</span></div>
                            <div class="flex justify-between"><span>Restaurant Orders</span><span class="font-mono text-neutral-800 font-bold">Rp 850.000</span></div>
                            <div class="flex justify-between border-t border-neutral-100 pt-2 font-bold text-neutral-900 uppercase tracking-wide text-[11px]">
                                <span>Total Amount</span>
                                <span class="font-mono text-amber-800 text-sm font-bold">Rp 2.500.000</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3 transition-colors mt-4">
                        View Bill & Pay
                    </button>
                </div>

            </div>

        </main>
    </div>
</x-guest-dashboard-layout>