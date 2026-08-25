@props(['status'])

@php
    $dotClasses = match($status) {
        'pending' => 'bg-[#999999]',
        'approved', 'in_progress' => 'bg-[#2d2d2d]',
        'resolved' => 'bg-[#c7c7c7]',
        'disapproved', 'cancelled' => 'bg-[#e0e0e0]',
        default => 'bg-[#999999]',
    };

    $textClasses = match($status) {
        'approved', 'in_progress' => 'text-[#2d2d2d] font-medium',
        'resolved' => 'text-[#999999]',
        'disapproved', 'cancelled' => 'text-[#bbbbbb]',
        default => 'text-[#555555]',
    };

    $label = match($status) {
        'approved' => 'OnQueue',
        'in_progress' => 'In Progress',
        default => str_replace('_', ' ', $status),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-2 text-[12px] capitalize $textClasses"]) }}>
    <span class="w-[6px] h-[6px] rounded-full shrink-0 {{ $dotClasses }}"></span>
    {{ $label }}
</span>
