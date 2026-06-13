@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-neutral-950 text-xs uppercase tracking-widest font-bold text-neutral-950 focus:outline-none transition-all duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-xs uppercase tracking-widest font-bold text-neutral-400 hover:text-neutral-950 hover:border-neutral-300 focus:outline-none transition-all duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>