@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 bg-white text-[#2d2d2d] font-medium border-l-2 border-white transition duration-150 ease-in-out rounded-none shadow-lg shadow-black/20'
            : 'flex items-center px-4 py-2 text-white/60 hover:bg-white/10 hover:text-white transition duration-150 ease-in-out rounded-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
