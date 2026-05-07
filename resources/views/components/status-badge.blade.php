@props(['status'])

@php
    $classes = match($status) {
        'pending' => 'bg-[#f0f0f0] text-[#555555] border-[#e0e0e0]',
        'approved' => 'bg-[#2d2d2d] text-white border-none',
        'in_progress' => 'bg-[#2d2d2d] text-white border-none',
        'resolved' => 'bg-[#f0f0f0] text-[#999999] border-[#e0e0e0]',
        'disapproved' => 'bg-[#f7f7f7] text-[#bbb] border-[#efefef]',
        'cancelled' => 'bg-[#f7f7f7] text-[#bbb] border-[#efefef]',
        default => 'bg-[#f7f7f7] text-[#555555] border-[#e5e5e5]',
    };
@endphp

<span {{ $attributes->merge(['class' => "px-3 py-1 rounded-none text-[11px] font-medium border uppercase tracking-wide inline-flex items-center $classes"]) }}>
    {{ match($status) {
        'approved' => 'OnQueue',
        'in_progress' => 'In Progress',
        default => str_replace('_', ' ', $status)
    } }}
</span>
