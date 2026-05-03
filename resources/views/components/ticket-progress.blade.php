@props(['status', 'serviceKind' => 'general'])

@php
    $status = (string) $status;
    $isTerminal = in_array($status, ['disapproved', 'cancelled'], true);

    $activeIndex = match ($status) {
        'pending' => 0,
        'approved' => 1,
        'in_progress' => 2,
        'resolved' => 3,
        default => -1,
    };

    $hint = match ($status) {
        'pending' => 'Waiting for IT review',
        'approved' => match ($serviceKind) {
            'recommendation' => 'Next: complete recommendation',
            'disposal' => 'Next: complete disposal',
            default => 'Approved — ready to start work',
        },
        'in_progress' => 'Work in progress',
        'resolved' => 'Completed',
        'disapproved' => 'Not approved',
        'cancelled' => 'Cancelled by requester',
        default => str_replace('_', ' ', $status),
    };
@endphp

<div {{ $attributes }}>
    @if ($isTerminal)
        <p class="text-[11px] text-[#999999] leading-snug max-w-[220px]">{{ $hint }}</p>
    @else
        <div class="flex gap-0.5 mb-1 max-w-[200px]">
            @for ($i = 0; $i < 4; $i++)
                <div
                    class="h-1.5 min-w-[12px] flex-1 rounded-none {{ $i <= $activeIndex ? 'bg-[#2d2d2d]' : 'bg-[#e5e5e5]' }}"
                    title="{{ ['Submitted', 'Reviewed', 'In progress', 'Done'][$i] ?? '' }}"
                ></div>
            @endfor
        </div>
        <p class="text-[10px] text-[#999999] leading-snug max-w-[220px]">{{ $hint }}</p>
    @endif
</div>
