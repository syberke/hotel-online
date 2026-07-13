@if(config('app.node.name'))
    <div
        class="fixed bottom-4 right-4 z-[9999] flex items-center gap-2 border border-white/20 px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-white shadow-xl"
        style="background-color: {{ config('app.node.color') }}"
        title="Respons dilayani oleh {{ config('app.node.name') }}"
    >
        <span class="inline-block h-2 w-2 rounded-full bg-white animate-pulse"></span>
        Served by {{ config('app.node.name') }}
    </div>
@endif
