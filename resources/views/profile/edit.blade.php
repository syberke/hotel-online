@php
    // Ambil role user yang sedang login, jika kosong default ke 'guest'
    $userRole = auth()->user()->role ?? 'guest';
    
    // Tentukan nama komponen berdasarkan role secara otomatis
    // Hasilnya akan menjadi: admin-dashboard-layout, manager-dashboard-layout, dll.
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