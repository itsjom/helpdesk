<div x-data="{ showRemarks: null }">
    @if($filterUser)
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-[#f7f7f7] border border-[#e5e5e5] px-4 py-3 rounded-none">
            <p class="text-[13px] text-[#555555]">
                Showing tickets for <span class="font-semibold text-[#2d2d2d]">{{ $filterUser->name }}</span>
                <span class="text-[#999999]">({{ $filterUser->username }})</span>
            </p>
            <button type="button" wire:click="clearRequesterFilter" class="btn-secondary text-[12px] py-2 px-4">
                Show all tickets
            </button>
        </div>
    @endif

    <div class="mb-12 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d]">Ticket</h1>
        </div>
        
        <!-- Metrics -->
        <div class="flex gap-4">
            <div @class([
                'px-4 py-3 rounded-none flex flex-col min-w-[120px] border',
                'bg-red-600 border-red-700 text-white' => $pendingCount > 0,
                'bg-[#f7f7f7] border-[#e5e5e5]' => $pendingCount === 0,
            ])>
                <span @class([
                    'text-[10px] font-medium uppercase tracking-widest mb-1',
                    'text-white/80' => $pendingCount > 0,
                    'text-[#999999]' => $pendingCount === 0,
                ])>Pending</span>
                <span @class([
                    'text-[18px] font-semibold leading-none',
                    'text-white' => $pendingCount > 0,
                    'text-[#2d2d2d]' => $pendingCount === 0,
                ])>{{ $pendingCount }}</span>
            </div>
            <div @class([
                'px-4 py-3 rounded-none flex flex-col min-w-[120px] border',
                'bg-red-600 border-red-700 text-white' => $activeCount > 0,
                'bg-[#2d2d2d] border-[#2d2d2d]' => $activeCount === 0,
            ])>
                <span @class([
                    'text-[10px] font-medium uppercase tracking-widest mb-1',
                    'text-white/80' => $activeCount > 0,
                    'text-white/50' => $activeCount === 0,
                ])>Active</span>
                <span class="text-[18px] font-semibold text-white leading-none">{{ $activeCount }}</span>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-[#f7f7f7] p-6 rounded-none border border-[#e5e5e5] mb-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">Search</label>
                <input wire:model.live.debounce.300ms="search" type="text" class="input-field w-full" placeholder="Search No. or User...">
            </div>
            <div>
                <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">Status</label>
                <select wire:model.live="status" class="input-field w-full">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="disapproved">Disapproved</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">Priority</label>
                <select wire:model.live="priority" class="input-field w-full">
                    <option value="">All Priorities</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div>
                <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">Type</label>
                <select wire:model.live="service_type" class="input-field w-full">
                    <option value="">All Service Types</option>
                    @foreach($serviceTypeOptions as $st)
                        <option value="{{ $st->code }}">{{ $st->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="border border-[#e5e5e5] rounded-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#f0f0f0]">
                <thead class="bg-[#f7f7f7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Ticket</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">User</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Details</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Assigned</th>
                        <th class="px-6 py-3 text-right text-[11px] font-medium text-[#999999] uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0f0f0]">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-[13px] font-semibold text-[#2d2d2d]">{{ $ticket->ticket_no }}</div>
                                <div class="text-[11px] text-[#999999] mt-1">{{ $ticket->created_at->format('M d, Y') }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="text-[13px] text-[#2d2d2d] font-medium">{{ $ticket->user->username }}</div>
                                <div class="text-[11px] text-[#999999]">{{ $ticket->user->department?->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <div class="flex flex-col gap-2">
                                    <span class="text-[12px] text-[#555555]">{{ $ticket->serviceType?->name ?? str_replace('_', ' ', $ticket->service_type) }}</span>
                                    <x-priority-badge :priority="$ticket->priority" />
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap align-top">
                                <div class="flex flex-col gap-2">
                                    <x-status-badge :status="$ticket->status" />
                                    <x-ticket-progress :status="$ticket->status" :service-kind="$ticket->serviceType?->kind ?? 'general'" />
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                <select 
                                    wire:change="assignTicket({{ $ticket->id }}, $event.target.value)"
                                    class="text-[12px] bg-transparent border-none p-0 focus:ring-0 text-[#2d2d2d] font-medium cursor-pointer"
                                >
                                    <option value="">Unassigned</option>
                                    @foreach($admins as $admin)
                                        <option value="{{ $admin->id }}" {{ $ticket->assigned_to == $admin->id ? 'selected' : '' }}>
                                            {{ $admin->username }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($ticket->status === 'pending')
                                        <button wire:click="updateStatus({{ $ticket->id }}, 'approved')" class="p-2 text-[#2d2d2d] hover:bg-[#f0f0f0] rounded-lg transition-all" title="Approve">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        </button>
                                        <button @click="showRemarks = {{ $ticket->id }}" class="p-2 text-[#555555] hover:bg-[#f0f0f0] rounded-lg transition-all" title="Disapprove">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    @elseif($ticket->status === 'approved')
                                        @if(($ticket->serviceType?->kind ?? '') === 'recommendation')
                                            <a href="{{ route('admin.tickets.recommendation', $ticket->id) }}" wire:navigate class="btn-primary text-[11px] py-1.5">Recommend</a>
                                        @elseif(($ticket->serviceType?->kind ?? '') === 'disposal')
                                            <a href="{{ route('admin.tickets.disposal', $ticket->id) }}" wire:navigate class="btn-primary text-[11px] py-1.5">Disposal</a>
                                        @else
                                            <button wire:click="updateStatus({{ $ticket->id }}, 'in_progress')" class="btn-primary text-[11px] py-1.5">Start</button>
                                        @endif
                                    @elseif($ticket->status === 'in_progress')
                                        <button wire:click="updateStatus({{ $ticket->id }}, 'resolved')" class="btn-primary text-[11px] py-1.5 px-3">Resolve</button>
                                    @endif

                                    <button class="p-2 text-[#999999] hover:text-[#2d2d2d] transition-all" @click="$dispatch('open-modal', 'details-{{ $ticket->id }}')">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </div>

                                <template x-if="showRemarks === {{ $ticket->id }}">
                                    <div class="mt-4 text-left p-4 bg-[#f7f7f7] rounded-[8px] border border-[#e5e5e5] animate-fade-in">
                                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">Disapproval Remarks</label>
                                        <textarea wire:model="remarks" class="input-field w-full text-[13px]" rows="2"></textarea>
                                        <div class="mt-4 flex justify-end gap-4">
                                            <button @click="showRemarks = null" class="text-[11px] font-bold text-[#999999] uppercase tracking-wider">Cancel</button>
                                            <button wire:click="updateStatus({{ $ticket->id }}, 'disapproved')" class="text-[11px] font-bold text-[#2d2d2d] uppercase tracking-wider underline">Confirm</button>
                                        </div>
                                    </div>
                                </template>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[#999999] text-[13px] italic">No tickets matching the criteria.</td>
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

    <!-- Modals -->
    @foreach($tickets as $ticket)
        <x-modal name="details-{{ $ticket->id }}" :show="false">
            <div class="p-10">
                <div class="flex justify-between items-start gap-6 mb-10 pb-6 border-b border-[#f0f0f0]">
                    <div>
                        <h2 class="text-[24px] font-semibold text-[#2d2d2d]">{{ $ticket->ticket_no }}</h2>
                        <p class="text-[12px] text-[#999999] uppercase tracking-widest mt-1">Request Information</p>
                    </div>
                    <div class="flex flex-col items-end gap-2 shrink-0 text-right">
                        <x-status-badge :status="$ticket->status" />
                        <x-ticket-progress :status="$ticket->status" :service-kind="$ticket->serviceType?->kind ?? 'general'" class="[&_p]:ml-auto" />
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-10 mb-10">
                    <div>
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">Service Type</h4>
                        <p class="text-[14px] font-medium text-[#2d2d2d]">{{ $ticket->serviceType?->name ?? str_replace('_', ' ', $ticket->service_type) }}</p>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">Priority</h4>
                        <p class="text-[14px] font-medium text-[#2d2d2d] capitalize">{{ $ticket->priority }}</p>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">User</h4>
                        <p class="text-[14px] font-medium text-[#2d2d2d]">{{ $ticket->user->username }} ({{ $ticket->user->department?->name ?? 'N/A' }})</p>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-2">Due Date</h4>
                        <p class="text-[14px] font-medium text-[#2d2d2d]">{{ $ticket->due_date->format('M d, Y — g:i A') }}</p>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Requirement Description</h4>
                    <div class="p-6 bg-[#f7f7f7] rounded-[12px] border border-[#e5e5e5] text-[14px] text-[#555555] leading-relaxed">
                        {{ $ticket->description }}
                    </div>
                </div>

                @if($ticket->admin_remarks)
                    <div class="mb-10">
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Admin Assessment</h4>
                        <div class="p-6 bg-[#2d2d2d] text-white rounded-[12px] text-[14px] leading-relaxed">
                            {{ $ticket->admin_remarks }}
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-6 border-t border-[#f0f0f0]">
                    <button @click="$dispatch('close-modal', 'details-{{ $ticket->id }}')" class="btn-secondary">Close Details</button>
                </div>
            </div>
        </x-modal>
    @endforeach
</div>
