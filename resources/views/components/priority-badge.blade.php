@props(['priority'])

@php
    $classes = match($priority) {
        'high' => 'bg-[#f0f0f0] text-[#333333] border-[#cccccc]',
        'medium' => 'bg-[#f7f7f7] text-[#555555] border-[#e5e5e5]',
        'low' => 'bg-[#ffffff] text-[#999999] border-[#f0f0f0]',
        default => 'bg-[#f7f7f7] text-[#555555] border-[#e5e5e5]',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-none text-[11px] font-medium border uppercase tracking-wide inline-flex items-center $classes"]) }}>
    {{ $priority }}
</span>
