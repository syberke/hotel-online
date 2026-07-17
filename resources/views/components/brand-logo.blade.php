@props([
    'alt' => 'Oasis Hotel & Resort',
])

<img
    src="{{ asset('logo.svg') }}"
    alt="{{ $alt }}"
    {{ $attributes->merge(['class' => 'h-10 w-auto object-contain']) }}
>
