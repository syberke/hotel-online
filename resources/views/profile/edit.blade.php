@php
    $userRole = auth()->user()->role ?? 'guest';
    $layoutComponent = $userRole . '-dashboard-layout';
@endphp

<x-dynamic-component :component="$layoutComponent">
    <div class="min-h-screen bg-[#faf9f6] text-neutral-900 font-sans antialiased">
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            
            <div class="border-b border-neutral-200 pb-6 mb-12">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-800">
                    {{ $userRole === 'guest' ? 'Personal Sanctuary' : 'Staff Control Token' }}
                </p>
                <h1 class="text-3xl font-serif mt-1">Profile Settings</h1>
                <p class="text-neutral-400 text-xs mt-1">Manage your personal identification credentials, security tokens, and account status.</p>
            </div>

            @if($userRole === 'guest')
                @if($isProfileComplete)
                    <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-bold uppercase tracking-wider">
                        <i class="fa-solid fa-circle-check mr-2"></i> Status Profil: Lengkap. Anda diizinkan untuk melakukan reservasi & booking kamar secara online.
                    </div>
                @else
                    <div class="mb-8 p-4 bg-amber-50 border border-amber-200 text-amber-800 text-[11px] font-bold uppercase tracking-wider animate-pulse">
                        <i class="fa-solid fa-triangle-exclamation mr-2"></i> Status Profil: Belum Lengkap! Silakan lengkapi nomor KTP, telepon, dan alamat Anda di bawah untuk membuka fitur booking hotel.
                    </div>
                @endif
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 text-xs font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-circle-info mr-2"></i> {{ session('info') }}
                </div>
            @endif

            <div class="space-y-12">
                <div class="bg-white border border-neutral-200 p-8 rounded-none shadow-none">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="bg-white border border-neutral-200 p-8 rounded-none shadow-none">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                @if($userRole === 'guest')
                    <div class="bg-white border border-neutral-200 p-8 rounded-none shadow-none border-t-red-200 bg-red-50/10">
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>