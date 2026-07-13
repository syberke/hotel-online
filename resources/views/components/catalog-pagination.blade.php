@props(['paginator', 'label' => 'items'])

@if ($paginator->hasPages())
    <nav aria-label="{{ ucfirst($label) }} pagination"
         class="mt-10 flex flex-col gap-4 border-t border-neutral-200 pt-6 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-center text-[10px] font-bold uppercase tracking-widest text-neutral-400 sm:text-left">
            Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}
            of {{ $paginator->total() }} {{ $label }}
        </p>

        <div class="flex items-center justify-center gap-1.5">
            @if ($paginator->onFirstPage())
                <span class="cursor-not-allowed border border-neutral-200 px-3 py-2 text-[9px] font-bold uppercase tracking-widest text-neutral-300">
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="border border-neutral-300 px-3 py-2 text-[9px] font-bold uppercase tracking-widest text-neutral-700 transition-colors hover:border-neutral-900 hover:text-neutral-900">
                    Previous
                </a>
            @endif

            <span class="px-2 text-[10px] font-bold uppercase tracking-wider text-neutral-500 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            <div class="hidden items-center gap-1.5 sm:flex">
                @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 1), min($paginator->lastPage(), $paginator->currentPage() + 1)) as $page => $url)
                    @if ($page === $paginator->currentPage())
                        <span aria-current="page"
                              class="flex h-8 min-w-8 items-center justify-center bg-neutral-900 px-2 text-[10px] font-bold text-white">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="flex h-8 min-w-8 items-center justify-center border border-neutral-300 px-2 text-[10px] font-bold text-neutral-600 transition-colors hover:border-neutral-900 hover:text-neutral-900">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="border border-neutral-300 px-3 py-2 text-[9px] font-bold uppercase tracking-widest text-neutral-700 transition-colors hover:border-neutral-900 hover:text-neutral-900">
                    Next
                </a>
            @else
                <span class="cursor-not-allowed border border-neutral-200 px-3 py-2 text-[9px] font-bold uppercase tracking-widest text-neutral-300">
                    Next
                </span>
            @endif
        </div>
    </nav>
@elseif ($paginator->total() > 0)
    <p class="mt-8 border-t border-neutral-200 pt-5 text-center text-[10px] font-bold uppercase tracking-widest text-neutral-400">
        Showing all {{ $paginator->total() }} {{ $label }}
    </p>
@endif
