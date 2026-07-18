@php
    $configuredNodeName = trim((string) config('app.node.name'));
    $nodeName = $configuredNodeName !== ''
        ? $configuredNodeName
        : (app()->environment('production') ? (gethostname() ?: null) : null);

    $nodePalette = ['#0f766e', '#b45309', '#7e22ce'];
    $nodeColor = (string) config('app.node.color', '#0f766e');

    if ($configuredNodeName === '' && $nodeName) {
        $nodeColor = $nodePalette[abs(crc32($nodeName)) % count($nodePalette)];
    }
@endphp

@if($nodeName)
    <div
        class="fixed bottom-4 right-4 z-[9999] max-w-[calc(100vw-2rem)] rounded-2xl border border-white/20 px-3 py-2.5 text-white shadow-2xl backdrop-blur"
        style="background-color: {{ $nodeColor }}"
        title="Refresh halaman untuk membuktikan request berpindah antar-container web."
        aria-label="Load balancing node {{ $nodeName }}"
    >
        <div class="flex items-center gap-2">
            <span class="relative flex h-2.5 w-2.5 shrink-0">
                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-white opacity-60"></span>
                <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-white"></span>
            </span>
            <div class="min-w-0">
                <p class="text-[9px] font-bold uppercase tracking-[0.14em] text-white/75">Load-balanced node</p>
                <p class="max-w-48 truncate font-mono text-[11px] font-bold" title="{{ $nodeName }}">{{ $nodeName }}</p>
            </div>
        </div>
    </div>
@endif
