@props(['priority'])

@php
    $classes = match($priority) {
        'high' => 'bg-red-600 text-white border-red-600 font-bold',
        'medium' => 'bg-red-50 text-red-600 border-red-600 font-semibold',
        'low' => 'bg-transparent text-red-600 border-transparent font-medium text-[#999999]',
        default => 'bg-[#f7f7f7] text-[#999999] border-[#e5e5e5]',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-none text-[10px] border uppercase tracking-widest inline-flex items-center $classes"]) }}>
    {{ $priority ?: 'Pending' }}
</span>
