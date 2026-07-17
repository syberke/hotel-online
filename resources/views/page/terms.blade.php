<x-guest-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')
        <main class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10">
                <p class="text-sm font-semibold text-blue-600">Legal information</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-slate-900">Terms of Service</h1>
                <p class="mt-4 text-sm leading-7 text-slate-500">Last updated: {{ date('d F Y') }}</p>

                <div class="mt-8 space-y-8 text-sm leading-7 text-slate-600">
                    <section><h2 class="text-lg font-semibold text-slate-900">Account responsibility</h2><p class="mt-2">Users must provide accurate information, protect account credentials, and use the system only for legitimate hotel reservations and services.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Reservations</h2><p class="mt-2">Room, restaurant, and facility reservations depend on availability and confirmation by the hotel. Dates, guest counts, prices, and cancellation status shown in the portal form part of the operational record.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Payments and receipts</h2><p class="mt-2">Payments must be completed using the available payment methods. Receipts remain accessible for eligible confirmed, checked-in, and checked-out stays.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Hotel operations</h2><p class="mt-2">The hotel may update room assignments, service status, and operational schedules where required for safety, maintenance, or service continuity.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Support</h2><p class="mt-2">Questions about these terms can be submitted through the <a href="{{ route('contact') }}" class="font-semibold text-blue-600">Contact page</a>.</p></section>
                </div>
            </div>
        </main>
        @include('layouts.footer')
    </div>
</x-guest-layout>
