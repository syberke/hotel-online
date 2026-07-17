<x-guest-layout>
    <div class="min-h-screen bg-slate-50 text-slate-900">
        @include('layouts.navigation')
        <main class="mx-auto max-w-4xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-10">
                <p class="text-sm font-semibold text-blue-600">Legal information</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-tight text-slate-900">Privacy Policy</h1>
                <p class="mt-4 text-sm leading-7 text-slate-500">Last updated: {{ date('d F Y') }}</p>

                <div class="mt-8 space-y-8 text-sm leading-7 text-slate-600">
                    <section><h2 class="text-lg font-semibold text-slate-900">Information we collect</h2><p class="mt-2">Oasis Hotel may collect account details, reservation information, contact messages, restaurant reservations, facility bookings, payment references, and information required for hotel operations.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">How information is used</h2><p class="mt-2">Information is used to provide accommodation and hotel services, process reservations and payments, respond to inquiries, maintain operational records, and protect account security.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Account and payment security</h2><p class="mt-2">Passwords are stored as secure hashes. Payment processing may use third-party payment providers. Secret payment credentials are never displayed in public pages.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Data access</h2><p class="mt-2">Operational access is limited according to role. Guests access their own records, receptionists manage front-office operations, managers review operational information, and administrators manage master data and system records.</p></section>
                    <section><h2 class="text-lg font-semibold text-slate-900">Contact</h2><p class="mt-2">Questions about privacy can be sent through the <a href="{{ route('contact') }}" class="font-semibold text-blue-600">Contact page</a>, which stores the message in the hotel inbox.</p></section>
                </div>
            </div>
        </main>
        @include('layouts.footer')
    </div>
</x-guest-layout>
