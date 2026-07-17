@php
    $userRole = auth()->user()->role ?? 'guest';
    $layoutComponent = $userRole . '-dashboard-layout';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <div class="mx-auto w-full max-w-5xl text-slate-900">
        <div class="mb-6 flex flex-col justify-between gap-4 border-b border-slate-200 pb-5 md:flex-row md:items-end">
            <div>
                <p class="text-xs font-medium text-blue-600">
                    {{ $userRole === 'guest' ? 'Account preferences' : 'Staff account' }}
                </p>
                <h1 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900 md:text-3xl">Profile Settings</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                    Update your personal information, password, and account security settings.
                </p>
            </div>

            <span class="inline-flex w-fit items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-xs font-medium text-slate-600 shadow-sm">
                <span class="h-2 w-2 rounded-full {{ $isProfileComplete ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                {{ $isProfileComplete ? 'Profile complete' : 'Action required' }}
            </span>
        </div>

        @if($userRole === 'guest')
            @if($isProfileComplete)
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white text-emerald-600 shadow-sm">
                        <i class="fa-solid fa-circle-check"></i>
                    </span>
                    <div>
                        <p class="font-semibold">Your profile is complete</p>
                        <p class="mt-1 text-emerald-700">You can continue making room reservations and facility bookings.</p>
                    </div>
                </div>
            @else
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    <span class="mt-0.5 grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-white text-amber-600 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </span>
                    <div>
                        <p class="font-semibold">Complete your profile</p>
                        <p class="mt-1 text-amber-800">Add your identity number, phone number, and address to unlock online booking.</p>
                    </div>
                </div>
            @endif
        @endif

        @if(session('info'))
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                <i class="fa-solid fa-circle-info mt-0.5"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <div class="space-y-5">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-7">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-7">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </section>

            @if($userRole === 'guest')
                <section class="rounded-2xl border border-rose-200 bg-rose-50/50 p-5 shadow-sm md:p-7">
                    <div class="max-w-2xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
            @endif
        </div>
    </div>
</x-dynamic-component>
