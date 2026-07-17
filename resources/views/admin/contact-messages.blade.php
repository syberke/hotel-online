<x-admin-dashboard-layout>
    <div class="space-y-5">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold text-blue-600">Guest communication</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Contact inbox</h2>
                <p class="mt-2 text-sm text-slate-500">Messages submitted from the public Contact page are stored here.</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                @foreach([
                    ['All', $counts['all'], 'slate'],
                    ['New', $counts['new'], 'blue'],
                    ['In progress', $counts['in_progress'], 'amber'],
                    ['Resolved', $counts['resolved'], 'emerald'],
                ] as [$label, $count, $tone])
                    <div class="rounded-xl bg-{{ $tone }}-50 px-4 py-3 text-center"><p class="text-xl font-semibold text-{{ $tone }}-700">{{ $count }}</p><p class="mt-1 text-[11px] font-medium text-{{ $tone }}-600">{{ $label }}</p></div>
                @endforeach
            </div>
        </section>

        <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
            <div class="relative min-w-0 flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email, or subject" class="w-full py-2.5 pr-4 text-sm">
            </div>
            <select name="status" class="min-w-44 px-3 py-2.5 text-sm" onchange="this.form.submit()">
                <option value="">All status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
        </form>

        <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
            @forelse($messages as $message)
                <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0"><p class="text-xs font-medium text-slate-400">#MSG-{{ str_pad((string) $message->id, 5, '0', STR_PAD_LEFT) }} · {{ $message->created_at->format('d M Y H:i') }}</p><h3 class="mt-1 truncate text-lg font-semibold text-slate-900">{{ $message->subject }}</h3></div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $message->status === 'resolved' ? 'bg-emerald-50 text-emerald-700' : ($message->status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">{{ ucwords(str_replace('_', ' ', $message->status)) }}</span>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-2 rounded-xl bg-slate-50 p-4 text-sm text-slate-600 sm:grid-cols-2"><p><strong class="text-slate-900">{{ $message->name }}</strong><br>{{ $message->email }}</p><p>{{ $message->phone ?: 'No phone number' }}</p></div>
                    <p class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $message->message }}</p>
                    <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                        <a href="mailto:{{ $message->email }}?subject={{ rawurlencode('Re: ' . $message->subject) }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"><i class="fa-solid fa-reply text-blue-600"></i>Reply by email</a>
                        @if($message->phone)<a href="tel:{{ $message->phone }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700"><i class="fa-solid fa-phone text-emerald-600"></i>Call</a>@endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('admin.contact-messages.status', $message) }}" method="POST" class="ml-auto flex gap-2">@csrf @method('PATCH')<select name="status" class="px-2 py-2 text-xs"><option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option><option value="in_progress" {{ $message->status === 'in_progress' ? 'selected' : '' }}>In progress</option><option value="resolved" {{ $message->status === 'resolved' ? 'selected' : '' }}>Resolved</option></select><button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Update</button></form>
                            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" data-confirm="Hapus pesan ini?">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button></form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="xl:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">No contact messages match the selected filter.</div>
            @endforelse
        </div>

        <div>{{ $messages->links() }}</div>
    </div>
</x-admin-dashboard-layout>
