<style>
    /* Desain scrollbar minimalis khusus area menu Oasis */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #faf9f6; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e5e5; 
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #a3a3a3; 
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<x-guest-layout>
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        @include('layouts.navigation')

        <div class="max-w-6xl mx-auto px-6 pt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <nav class="flex items-center space-x-2 text-[10px] uppercase tracking-widest text-neutral-400 font-bold">
                <a href="{{ route('home') }}" class="hover:text-neutral-900 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('restaurant') }}" class="hover:text-neutral-900 transition-colors">Restaurant</a>
                <span>/</span>
                <span class="text-amber-700">{{ $menu->name }}</span>
            </nav>

            <a href="{{ route('restaurant') }}" class="inline-flex items-center text-[10px] font-bold uppercase tracking-widest text-neutral-500 hover:text-neutral-900 transition-colors border border-neutral-300 hover:border-neutral-900 px-4 py-2 bg-white self-start sm:self-auto">
                <i class="fa-solid fa-arrow-left me-2"></i> Back To Menu Card
            </a>
        </div>

        <main class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            
            <div class="space-y-4">
                <div class="w-full border border-neutral-200 overflow-hidden bg-neutral-100 shadow-sm relative h-[400px]">
                    <img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                    <span class="absolute bottom-4 left-4 bg-amber-800 text-white text-[8px] font-bold uppercase tracking-wider px-2 py-1">
                        <i class="fa-solid fa-star text-amber-400 mr-0.5"></i> Curated Masterpiece
                    </span>
                </div>
                
                <div class="grid grid-cols-3 gap-4 text-center text-neutral-500 text-[10px] font-bold uppercase tracking-wider bg-white border border-neutral-200 p-4">
                    <div>
                        <div class="text-amber-800 text-sm mb-1"><i class="fa-solid fa-fire-flame-curved"></i></div>
                        <div>100% Organic</div>
                    </div>
                    <div class="border-x border-neutral-100">
                        <div class="text-amber-800 text-sm mb-1"><i class="fa-solid fa-kitchen-set"></i></div>
                        <div>Freshly Prepared</div>
                    </div>
                    <div>
                        <div class="text-amber-800 text-sm mb-1"><i class="fa-solid fa-bell-concierge"></i></div>
                        <div>Room Delivery</div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-amber-700 block mb-1">Premium Gastronomy Architecture</span>
                    <h1 class="text-3xl font-serif text-neutral-900 uppercase tracking-wide">{{ $menu->name }}</h1>
                    <div class="text-xl font-bold text-amber-800 font-mono mt-2">
                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                    </div>
                </div>

                <div class="border-t border-neutral-200 pt-6">
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-neutral-800 mb-2">Gastronomy Description</h4>
                    <p class="text-neutral-500 text-xs leading-relaxed font-medium">{{ $menu->description }}</p>
                </div>

                <div class="bg-white border border-neutral-200 p-6 shadow-md rounded-none">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-neutral-900 border-b border-neutral-100 pb-3 mb-4">Suite Service Order Engine</h4>
                    
                    <form id="detail-gastronomy-form" action="{{ route('restaurant.order') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" id="final-invoice-price" name="total_price" value="{{ $menu->price }}">
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                        <div class="flex items-center justify-between bg-neutral-50 border border-neutral-100 px-4 py-3">
                            <span class="text-xs font-bold text-neutral-700 uppercase tracking-wider">Portion Quantity</span>
                            <div class="flex items-center border border-neutral-300 bg-white">
                                <button type="button" onclick="changeQuantity(-1)" class="px-3 py-1 text-xs font-bold hover:bg-neutral-100 transition-colors">-</button>
                                <input type="text" id="display-qty" name="quantity" value="1" readonly class="w-10 text-center border-none p-0 text-xs font-bold focus:ring-0 text-neutral-800 bg-transparent">
                                <button type="button" onclick="changeQuantity(1)" class="px-3 py-1 text-xs font-bold hover:bg-neutral-100 transition-colors">+</button>
                            </div>
                        </div>

                        <div class="flex justify-between items-center text-xs font-bold uppercase tracking-wider py-2">
                            <span>Total Accumulation Cost</span>
                            <span id="display-total-cost" class="text-amber-800 font-mono text-base">Rp {{ number_format($menu->price, 0, ',', '.') }}</span>
                        </div>

                        <div id="ajax-response-alert" class="hidden p-3 text-[10px] font-bold uppercase tracking-wider"></div>

                        <button type="submit" id="submit-order-btn" class="w-full bg-neutral-900 hover:bg-neutral-800 text-white font-bold text-xs uppercase tracking-widest py-3.5 rounded-none transition-all cursor-pointer">
                            Confirm Order To Room Enclosure &rarr;
                        </button>
                    </form>
                </div>

                <div class="border-t border-neutral-100 pt-4 text-[11px] text-neutral-400 leading-relaxed font-medium">
                    <i class="fa-solid fa-circle-info text-amber-800 mr-1"></i> **In-Suite Notice**: Estimasi waktu pengantaran makanan menuju paviliun atau kamar Anda berkisar antara 20-30 menit tergantung pada kompleksitas teknik memasak hidangan.
                </div>
            </div>
        </main>

        @include('layouts.footer')
    </div>
</x-guest-layout>

<script>
    // Menyimpan harga dasar menu langsung dari database Laravel
    const baseMenuPrice = {{ $menu->price }};
    const qtyInput = document.getElementById('display-qty');
    const invoicePriceInput = document.getElementById('final-invoice-price');
    const totalCostLabel = document.getElementById('display-total-cost');
    
    const orderForm = document.getElementById('detail-gastronomy-form');
    const submitBtn = document.getElementById('submit-order-btn');
    const alertBox = document.getElementById('ajax-response-alert');

    // Fungsi Pengubah Qty (+/-)
    function changeQuantity(delta) {
        let currentVal = parseInt(qtyInput.value);
        let updatedVal = currentVal + delta;
        
        // Membatasi pesanan minimal 1 porsi dan maksimal 10 porsi sekali pesan
        if (updatedVal >= 1 && updatedVal <= 10) {
            qtyInput.value = updatedVal;
            recalculateInvoice();
        }
    }

    // Fungsi Sinkronisasi Hitung Harga Total
    function recalculateInvoice() {
        let totalSum = baseMenuPrice * parseInt(qtyInput.value);
        invoicePriceInput.value = totalSum;
        totalCostLabel.innerText = 'Rp ' + totalSum.toLocaleString('id-ID');
    }

    // Pemrosesan Pengiriman Form Melalui AJAX Asinkronus
    orderForm.addEventListener('submit', function (e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = "Transmitting Order Request...";

        fetch(orderForm.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: new FormData(orderForm)
        })
        .then(async response => {
            const data = await response.json();
            submitBtn.disabled = false;
            submitBtn.innerText = "Confirm Order To Room Enclosure →";
            
            // Bersihkan sisa class pesan lama
            alertBox.classList.remove('hidden', 'bg-red-50', 'text-red-800', 'border-red-200', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'border');

            if (response.ok && data.success) {
                alertBox.classList.add('bg-emerald-50', 'text-emerald-800', 'border', 'border-emerald-200');
                alertBox.innerText = data.message;
            } else {
                alertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
                alertBox.innerText = data.message || "Pemesanan gagal diproses oleh dapur pusat.";
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = "Confirm Order To Room Enclosure →";
            alertBox.classList.remove('hidden');
            alertBox.classList.add('bg-red-50', 'text-red-800', 'border', 'border-red-200');
            alertBox.innerText = "Terjadi gangguan transmisi sinyal lokal.";
        });
    });
</script>