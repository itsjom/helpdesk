@props(['priority'])

@php
    $dotClasses = match($priority) {
        'high' => 'bg-[#2d2d2d]',
        'medium' => 'bg-[#999999]',
        'low' => 'bg-[#e0e0e0]',
        default => 'bg-[#e0e0e0]',
    };

    $textClasses = match($priority) {
        'high' => 'text-[#2d2d2d] font-semibold',
        'medium' => 'text-[#555555] font-medium',
        'low' => 'text-[#999999] font-normal',
        default => 'text-[#999999] font-normal',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 text-[12px] capitalize $textClasses"]) }}>
    <span class="w-[6px] h-[6px] rounded-full shrink-0 {{ $dotClasses }}"></span>
    {{ $priority ?: 'Unset' }}
</span>
