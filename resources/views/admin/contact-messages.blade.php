<x-admin-dashboard-layout>
    <div class="space-y-5">
        <section class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-blue-600">Guest communication</p>
                <h2 class="mt-1 text-2xl font-semibold tracking-tight text-slate-900">Contact inbox</h2>
                <p class="mt-2 text-sm text-slate-500">Messages from the public Contact page. Email opens a prepared Gmail draft, while Call copies the number and opens the device dialer.</p>
            </div>
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-4">
                @foreach([
                    ['All', $counts['all'], 'bg-slate-50 text-slate-700'],
                    ['New', $counts['new'], 'bg-blue-50 text-blue-700'],
                    ['In progress', $counts['in_progress'], 'bg-amber-50 text-amber-700'],
                    ['Resolved', $counts['resolved'], 'bg-emerald-50 text-emerald-700'],
                ] as [$label, $count, $classes])
                    <div class="rounded-xl px-4 py-3 text-center {{ $classes }}"><p class="text-xl font-semibold">{{ $count }}</p><p class="mt-1 text-[11px] font-medium">{{ $label }}</p></div>
                @endforeach
            </div>
        </section>

        <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center">
            <div class="relative min-w-0 flex-1">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search name, email, or subject" class="w-full py-2.5 pl-11 pr-4 text-sm">
            </div>
            <select name="status" class="min-w-0 px-3 py-2.5 text-sm sm:min-w-44" onchange="this.form.submit()">
                <option value="">All status</option>
                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In progress</option>
                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
            </select>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
        </form>

        <div class="grid min-w-0 grid-cols-1 gap-4 2xl:grid-cols-2">
            @forelse($messages as $message)
                @php
                    $replySubject = rawurlencode('Re: ' . $message->subject);
                    $replyBody = rawurlencode("Hello {$message->name},\n\nThank you for contacting Oasis Hotel & Resort regarding {$message->subject}.\n\n\nRegards,\nOasis Hotel Team");
                    $gmailReplyUrl = 'https://mail.google.com/mail/?view=cm&fs=1&to=' . rawurlencode($message->email) . '&su=' . $replySubject . '&body=' . $replyBody;
                    $callNumber = preg_replace('/[^0-9+]/', '', (string) $message->phone);
                @endphp
                <article class="min-w-0 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex min-w-0 items-start justify-between gap-4">
                        <div class="min-w-0"><p class="text-xs font-medium text-slate-400">#MSG-{{ str_pad((string) $message->id, 5, '0', STR_PAD_LEFT) }} · {{ $message->created_at->format('d M Y H:i') }}</p><h3 class="mt-1 break-words text-lg font-semibold text-slate-900">{{ $message->subject }}</h3></div>
                        <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold {{ $message->status === 'resolved' ? 'bg-emerald-50 text-emerald-700' : ($message->status === 'in_progress' ? 'bg-amber-50 text-amber-700' : 'bg-blue-50 text-blue-700') }}">{{ ucwords(str_replace('_', ' ', $message->status)) }}</span>
                    </div>
                    <div class="mt-4 grid min-w-0 grid-cols-1 gap-3 rounded-xl bg-slate-50 p-4 text-sm text-slate-600 sm:grid-cols-2"><p class="min-w-0"><strong class="block text-slate-900">{{ $message->name }}</strong><span class="block break-all">{{ $message->email }}</span></p><p class="break-all sm:text-right">{{ $message->phone ?: 'No phone number' }}</p></div>
                    <p class="mt-4 whitespace-pre-line break-words text-sm leading-7 text-slate-600">{{ $message->message }}</p>
                    <div class="mt-5 flex flex-wrap items-center gap-2 border-t border-slate-100 pt-4">
                        <a href="{{ $gmailReplyUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"><i class="fa-solid fa-reply text-blue-600"></i>Reply by email</a>
                        @if($callNumber)
                            <button type="button" data-call-contact data-phone="{{ $callNumber }}" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-700"><i class="fa-solid fa-phone text-emerald-600"></i>Call</button>
                        @endif
                        @if(auth()->user()->role === 'admin')
                            <form action="{{ route('admin.contact-messages.status', $message) }}" method="POST" class="flex min-w-0 flex-1 flex-wrap justify-end gap-2">@csrf @method('PATCH')<select name="status" class="min-w-32 px-2 py-2 text-xs"><option value="new" {{ $message->status === 'new' ? 'selected' : '' }}>New</option><option value="in_progress" {{ $message->status === 'in_progress' ? 'selected' : '' }}>In progress</option><option value="resolved" {{ $message->status === 'resolved' ? 'selected' : '' }}>Resolved</option></select><button class="rounded-lg bg-blue-600 px-3 py-2 text-xs font-semibold text-white">Update</button></form>
                            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST" data-confirm="Hapus pesan ini?">@csrf @method('DELETE')<button class="rounded-lg border border-rose-200 px-3 py-2 text-xs font-semibold text-rose-600">Delete</button></form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="2xl:col-span-2 rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-sm text-slate-500">No contact messages match the selected filter.</div>
            @endforelse
        </div>

        <div>{{ $messages->links() }}</div>
    </div>

    <div id="contact-call-toast" class="pointer-events-none fixed bottom-5 right-5 z-[100] hidden rounded-xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white shadow-2xl">Phone number copied. Opening the dialer…</div>
    <script>
        document.addEventListener('click', async (event) => {
            const button = event.target.closest('[data-call-contact]');
            if (!button) return;
            const phone = button.dataset.phone;
            try { await navigator.clipboard.writeText(phone); } catch (error) { /* Clipboard permission is optional. */ }
            const toast = document.getElementById('contact-call-toast');
            toast?.classList.remove('hidden');
            window.setTimeout(() => toast?.classList.add('hidden'), 2600);
            window.location.href = `tel:${phone}`;
        });
    </script>
</x-admin-dashboard-layout>
