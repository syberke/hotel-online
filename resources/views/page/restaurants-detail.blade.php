<x-guest-layout>
    <div
        class="min-h-screen bg-slate-50 text-slate-900"
        x-data="{
            quantity: 1,
            cart: JSON.parse(localStorage.getItem('oasis_restaurant_cart') || '[]'),
            added: false,
            addToCart() {
                const item = {
                    id: {{ $menu->id }},
                    title: @js($menu->name),
                    price: {{ $menu->price }},
                    image_url: @js($menu->foto_url),
                    quantity: Number(this.quantity),
                    venue: 'Oasis Fine Dining'
                };
                const existing = this.cart.find(entry => entry.id === item.id);
                if (existing) existing.quantity += item.quantity;
                else this.cart.push(item);
                localStorage.setItem('oasis_restaurant_cart', JSON.stringify(this.cart));
                this.added = true;
                setTimeout(() => this.added = false, 2400);
            }
        }"
    >
        @include('layouts.navigation')

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 lg:py-12">
            <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <nav class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500"><a href="{{ route('home') }}" class="hover:text-slate-900">Home</a><i class="fa-solid fa-chevron-right text-[9px]"></i><a href="{{ route('restaurant') }}" class="hover:text-slate-900">Restaurant</a><i class="fa-solid fa-chevron-right text-[9px]"></i><span class="text-blue-600">{{ $menu->name }}</span></nav>
                <a href="{{ route('restaurant') }}" class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50 hover:text-slate-900"><i class="fa-solid fa-arrow-left text-xs"></i>Back to menu</a>
            </div>

            <section class="grid grid-cols-1 gap-8 lg:grid-cols-[minmax(0,1.15fr)_minmax(360px,0.85fr)] lg:items-start">
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="relative h-[430px] overflow-hidden bg-slate-100 sm:h-[560px]"><img src="{{ $menu->foto_url }}" alt="{{ $menu->name }}" class="h-full w-full object-cover"><div class="absolute inset-0 bg-gradient-to-t from-slate-950/45 via-transparent to-transparent"></div><span class="absolute bottom-5 left-5 rounded-full bg-white/90 px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm backdrop-blur">Hotel restaurant menu</span></div>
                </div>

                <div class="space-y-6">
                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                        <p class="text-sm font-medium text-blue-600">Menu item</p>
                        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">{{ $menu->name }}</h1>
                        <p class="mt-3 text-2xl font-semibold text-blue-700">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                        <p class="mt-6 border-t border-slate-100 pt-5 text-sm leading-7 text-slate-500">{{ $menu->description }}</p>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs text-slate-500">Preparation</p><p class="mt-1 text-sm font-semibold text-slate-900">Made to order</p></div>
                            <div class="rounded-xl bg-slate-50 p-4"><p class="text-xs text-slate-500">Delivery estimate</p><p class="mt-1 text-sm font-semibold text-slate-900">20–30 minutes</p></div>
                        </div>
                    </section>

                    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4"><div><p class="text-xs font-medium text-slate-500">Add to order</p><h2 class="mt-1 text-lg font-semibold text-slate-900">Choose quantity</h2></div><p class="text-right text-sm font-semibold text-blue-700" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format({{ $menu->price }} * quantity)"></p></div>

                        <div class="mt-5 flex items-center justify-between rounded-xl bg-slate-50 p-4"><span class="text-sm font-medium text-slate-700">Portions</span><div class="flex items-center rounded-xl border border-slate-200 bg-white"><button type="button" @click="quantity = Math.max(1, quantity - 1)" class="grid h-10 w-10 place-items-center text-slate-500 hover:bg-slate-50">−</button><input type="text" x-model="quantity" readonly class="h-10 w-12 border-0 bg-transparent p-0 text-center text-sm font-semibold text-slate-900 focus:ring-0"><button type="button" @click="quantity = Math.min(10, quantity + 1)" class="grid h-10 w-10 place-items-center text-slate-500 hover:bg-slate-50">+</button></div></div>

                        <button type="button" @click="addToCart()" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white hover:bg-blue-700"><i class="fa-solid fa-basket-shopping text-xs"></i>Add to restaurant cart</button>

                        <div x-show="added" x-transition x-cloak class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800"><i class="fa-solid fa-circle-check mr-2"></i>Item added to your restaurant cart.</div>

                        @auth
                            @if(auth()->user()->role === 'guest' && Route::has('guest.restaurant.orders'))
                                <a href="{{ route('guest.restaurant.orders') }}" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">Open restaurant portal <i class="fa-solid fa-arrow-right text-xs"></i></a>
                            @endif
                        @else
                            <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-500">Your cart is stored on this device. <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">Sign in</a> to continue ordering through the guest portal.</div>
                        @endauth
                    </section>

                    <section class="rounded-2xl border border-blue-100 bg-blue-50 p-5 text-sm leading-6 text-blue-900"><div class="flex items-start gap-3"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-white text-blue-600 shadow-sm"><i class="fa-solid fa-circle-info"></i></span><div><p class="font-semibold">Before placing an order</p><p class="mt-1 text-blue-800">Please mention allergies or dietary requirements in the guest portal before payment.</p></div></div></section>
                </div>
            </section>

            <section class="mt-12 grid grid-cols-1 gap-5 md:grid-cols-3">
                @foreach([
                    ['fa-leaf', 'Prepared fresh', 'Kitchen preparation begins after the order is confirmed.'],
                    ['fa-clock', 'Track order history', 'Paid and pending restaurant orders stay visible in the guest portal.'],
                    ['fa-receipt', 'Keep your receipt', 'Restaurant receipts can be reopened and printed after payment.'],
                ] as [$icon, $title, $description])
                    <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><span class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-blue-600"><i class="fa-solid {{ $icon }}"></i></span><h2 class="mt-4 text-base font-semibold text-slate-900">{{ $title }}</h2><p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p></article>
                @endforeach
            </section>
        </main>

        @include('layouts.footer')
    </div>
</x-guest-layout>
