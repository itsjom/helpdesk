<div wire:poll.10s>
    <div class="mb-12 flex justify-between items-end">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d]">My Tickets</h1>
            <p class="text-[14px] text-[#555555] mt-1">Track your requests. The progress bar under status shows how far
                along each ticket is.</p>
        </div>
        <a href="{{ route('user.tickets.create') }}" wire:navigate class="btn-primary">
            New Request
        </a>
    </div>

    <!-- Ticket List Table -->
    <div class="border border-[#e5e5e5] rounded-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#f0f0f0]">
                <thead class="bg-[#f7f7f7]">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            No.</th>
                        <th
                            class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            Service</th>
                        <th
                            class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            Priority</th>
                        <th
                            class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            Status</th>
                        <th
                            class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            Assigned</th>

                        <th
                            class="px-6 py-3 text-right text-[11px] font-medium text-[#999999] uppercase tracking-widest">
                            Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0f0f0]">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap text-[13px] font-semibold text-[#2d2d2d]">
                                {{ $ticket->ticket_no }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-[13px] text-[#555555] capitalize">
                                {{ $ticket->serviceType?->name ?? str_replace('_', ' ', $ticket->service_type) }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <x-priority-badge :priority="$ticket->priority" />
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap align-top">
                                <div class="flex flex-col gap-2">
                                    <x-status-badge :status="$ticket->status" />
                                    <x-ticket-progress :status="$ticket->status" :priority="$ticket->priority" />
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-[13px]">
                                @if($ticket->assignedTo)
                                    <span class="font-medium text-[#2d2d2d]">{{ $ticket->assignedTo->username }}</span>
                                @else
                                    <span class="text-[#999999] italic">Waiting...</span>
                                @endif
                            </td>

                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <button wire:click="viewTicket({{ $ticket->id }})"
                                        class="text-[12px] font-bold text-[#2d2d2d] hover:text-[#555555] uppercase tracking-wider">
                                        View
                                    </button>

                                    @php
                                        $pdfPath = $ticket->recommendation?->file_path ?? $ticket->disposal?->file_path;
                                    @endphp

                                    @if($pdfPath)
                                        <a href="{{ Storage::disk('public')->url($pdfPath) }}" target="_blank"
                                            class="text-[12px] font-bold text-[#2d2d2d] underline flex items-center gap-1"
                                            title="View Resolution PDF">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 9h1.5m1.5 0H13m-3 3h3m-3 3h3" />
                                            </svg>
                                            Recommendation
                                        </a>
                                    @endif

                                    @if($ticket->status === 'pending')
                                        <button wire:click="cancelTicket({{ $ticket->id }})" wire:confirm="Cancel this request?"
                                            class="text-[12px] font-bold text-[#999999] hover:text-[#2d2d2d] uppercase tracking-wider">
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <p class="text-[13px] text-[#999999] italic">No service requests found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($tickets->hasPages())
        <div class="mt-8 px-6">
            {{ $tickets->links() }}
        </div>
    @endif

    <!-- View Ticket Modal -->
    <x-modal name="view-ticket-modal" maxWidth="lg" focusable>
        @if($viewingTicket)
            <div class="p-8">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h3 class="text-[16px] font-semibold text-[#2d2d2d]">{{ $viewingTicket->ticket_no }}</h3>
                        <p class="text-[13px] text-[#555555] mt-1 capitalize">
                            {{ $viewingTicket->serviceType?->name ?? str_replace('_', ' ', $viewingTicket->service_type) }}
                        </p>
                    </div>
                    <x-status-badge :status="$viewingTicket->status" />
                </div>

                <dl class="grid grid-cols-2 gap-4 text-[13px] mb-6">
                    <div>
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest">Priority</dt>
                        <dd class="mt-1"><x-priority-badge :priority="$viewingTicket->priority" /></dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest">Assigned To</dt>
                        <dd class="mt-1 text-[#2d2d2d]">{{ $viewingTicket->assignedTo?->username ?? 'Waiting...' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest">Submitted</dt>
                        <dd class="mt-1 text-[#2d2d2d]">{{ $viewingTicket->created_at->format('M d, Y g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest">Due</dt>
                        <dd class="mt-1 text-[#2d2d2d]">{{ $viewingTicket->due_date?->format('M d, Y g:i A') ?? '—' }}</dd>
                    </div>
                </dl>

                <div class="mb-6">
                    <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-1">Description</dt>
                    <p class="text-[13px] text-[#2d2d2d] whitespace-pre-line">{{ $viewingTicket->description ?: '—' }}</p>
                </div>

                @if($viewingTicket->admin_remarks)
                    <div class="mb-6">
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-1">Admin Remarks</dt>
                        <p class="text-[13px] text-[#2d2d2d] whitespace-pre-line">{{ $viewingTicket->admin_remarks }}</p>
                    </div>
                @endif

                @php
                    $viewPdfPath = $viewingTicket->recommendation?->file_path ?? $viewingTicket->disposal?->file_path;
                @endphp
                @if($viewPdfPath)
                    <div class="mb-6">
                        <a href="{{ Storage::disk('public')->url($viewPdfPath) }}" target="_blank"
                            class="text-[12px] font-bold text-[#2d2d2d] underline">
                            View Resolution PDF
                        </a>
                    </div>
                @endif

                @if($viewingTicket->logs->isNotEmpty())
                    <div class="mb-6">
                        <dt class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">History</dt>
                        <ul class="space-y-2">
                            @foreach($viewingTicket->logs as $log)
                                <li class="text-[12px] text-[#555555] flex justify-between gap-4 border-b border-[#f0f0f0] pb-2">
                                    <span>{{ $log->newStatusLabel() }}@if($log->remarks) — {{ $log->remarks }}@endif</span>
                                    <span class="text-[#999999] whitespace-nowrap">{{ $log->created_at->format('M d, g:i A') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-end pt-4 border-t border-[#f0f0f0]">
                    <x-secondary-button x-on:click="$dispatch('close')">Close</x-secondary-button>
                </div>
            </div>
        @endif
    </x-modal>
</div>