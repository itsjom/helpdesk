<div x-data="{ showRemarks: null }">

    <div class="mb-12 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">Tickets</h1>
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
                    <option value="approved">OnQueue</option>
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
                                    <select wire:change="updatePriority({{ $ticket->id }}, $event.target.value)" 
                                        @class([
                                            'text-[10px] uppercase tracking-widest pl-2 pr-8 py-1 border cursor-pointer focus:ring-0 focus:outline-none transition-all w-fit min-w-[85px]',
                                            'bg-red-600 text-white border-red-600 font-bold' => $ticket->priority === 'high',
                                            'bg-red-50 text-red-600 border-red-600 font-semibold' => $ticket->priority === 'medium',
                                            'bg-transparent text-red-600 border-transparent font-medium text-[#999999]' => $ticket->priority === 'low',
                                            'bg-[#f7f7f7] text-[#999999] border-[#e5e5e5]' => !$ticket->priority,
                                        ])>
                                        <option value="" class="bg-white text-[#999999]" {{ !$ticket->priority ? 'selected' : '' }}>Pending</option>
                                        <option value="high" class="bg-white text-[#2d2d2d]" {{ $ticket->priority === 'high' ? 'selected' : '' }}>High</option>
                                        <option value="medium" class="bg-white text-[#2d2d2d]" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="low" class="bg-white text-[#2d2d2d]" {{ $ticket->priority === 'low' ? 'selected' : '' }}>Low</option>
                                    </select>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap align-top">
                                <div class="flex flex-col gap-2">
                                    <x-status-badge :status="$ticket->status" />
                                    <x-ticket-progress :status="$ticket->status" :priority="$ticket->priority" />
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($ticket->assignedTo)
                                    <span class="text-[12px] text-[#2d2d2d] font-medium">{{ $ticket->assignedTo->username }}</span>
                                @else
                                    <span class="text-[12px] text-[#999999] italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($ticket->status === 'pending')
                                            <button wire:click="updateStatus({{ $ticket->id }}, 'approved')" class="p-2 text-[#2d2d2d] hover:bg-[#f0f0f0] rounded-none transition-all" title="Move to OnQueue">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                            </button>
                                            <button @click="showRemarks = {{ $ticket->id }}" class="p-2 text-[#555555] hover:bg-[#f0f0f0] rounded-none transition-all" title="Disapprove">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        @elseif($ticket->status === 'approved')
                                            <div class="flex items-center justify-end gap-1">
                                                @if(in_array($ticket->serviceType?->kind, ['recommendation', 'disposal']))
                                                    <button wire:click="$set('uploadingTicketId', {{ $ticket->id }})" 
                                                        class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-red-600 text-white border border-red-600 hover:bg-red-700 transition-all">
                                                        File Upload
                                                    </button>
                                                @else
                                                    <button wire:click="updateStatus({{ $ticket->id }}, 'in_progress')" 
                                                        class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-[#2d2d2d] text-white border border-[#2d2d2d] hover:bg-black transition-all">
                                                        Start
                                                    </button>
                                                @endif
                                            </div>
                                        @elseif($ticket->status === 'in_progress')
                                            <div class="flex items-center justify-end gap-1">
                                                <button wire:click="updateStatus({{ $ticket->id }}, 'resolved')" class="btn-primary text-[11px] py-1.5 px-3">Resolve</button>
                                                <button @click="showRemarks = (showRemarks === {{ $ticket->id }} ? null : {{ $ticket->id }}); if(showRemarks) $wire.set('remarks', '{{ addslashes($ticket->admin_remarks) }}')" 
                                                    class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 bg-white text-[#2d2d2d] border border-[#e5e5e5] hover:bg-[#f7f7f7] transition-all">
                                                    Comment
                                                </button>
                                            </div>
                                        @endif

                                        <button class="p-2 text-[#999999] hover:text-[#2d2d2d] transition-all" @click="$dispatch('open-modal', 'details-{{ $ticket->id }}')">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </button>
                                    </div>

                                    @if($uploadingTicketId === $ticket->id)
                                        <div class="mt-4 p-6 bg-red-50 border border-red-100 animate-fade-in text-left">
                                            <label class="text-[10px] font-bold text-red-800 uppercase tracking-widest mb-3 block">Upload {{ ucfirst($ticket->serviceType?->kind) }} File</label>
                                            <div class="flex flex-col gap-4">
                                                <input type="file" wire:model="attachedFile" accept=".pdf,.doc,.docx,.png" class="text-[12px] text-[#555555] file:mr-4 file:py-2 file:px-4 file:rounded-none file:border-0 file:text-[11px] file:font-bold file:bg-red-600 file:text-white hover:file:bg-red-700 transition-all">
                                                <div class="flex items-center gap-4">
                                                    <button wire:click="uploadFile({{ $ticket->id }})" wire:loading.attr="disabled" class="bg-[#2d2d2d] text-white text-[11px] font-bold uppercase tracking-widest px-4 py-2 hover:bg-black transition-all">
                                                        Save & Start
                                                    </button>
                                                    <button wire:click="$set('uploadingTicketId', null)" class="text-[11px] font-bold text-[#999999] uppercase tracking-widest">Cancel</button>
                                                </div>
                                            </div>
                                            <div wire:loading wire:target="attachedFile" class="mt-3 text-[10px] text-red-600 font-medium animate-pulse">Uploading file... please wait</div>
                                            <x-input-error :messages="$errors->get('attachedFile')" class="mt-2" />
                                        </div>
                                    @endif

                                <template x-if="showRemarks === {{ $ticket->id }}">
                                    <div class="mt-4 text-left p-4 bg-[#f7f7f7] rounded-[8px] border border-[#e5e5e5] animate-fade-in">
                                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-wider mb-2 block">
                                            {{ $ticket->status === 'pending' ? 'Disapproval Remarks' : 'Resolution Remarks / Comments' }}
                                        </label>
                                        <textarea wire:model="remarks" class="input-field w-full text-[13px]" rows="2" placeholder="Enter remarks here..."></textarea>
                                        <div class="mt-4 flex justify-end gap-4">
                                            <button @click="showRemarks = null" class="text-[11px] font-semibold text-[#999999] uppercase tracking-wider">Cancel</button>
                                            @if($ticket->status === 'pending')
                                                <button wire:click="updateStatus({{ $ticket->id }}, 'disapproved')" class="text-[11px] font-semibold text-[#2d2d2d] uppercase tracking-wider underline">Confirm Disapproval</button>
                                            @else
                                                <button wire:click="updateRemarks({{ $ticket->id }})" @click="showRemarks = null" class="text-[11px] font-semibold text-[#2d2d2d] uppercase tracking-wider underline">Save Comment</button>
                                            @endif
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
                        <x-ticket-progress :status="$ticket->status" :priority="$ticket->priority" class="[&_p]:ml-auto" />
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
                        <p class="text-[14px] font-medium text-[#2d2d2d]">
                            {{ $ticket->due_date ? $ticket->due_date->format('M d, Y — g:i A') : 'Pending' }}
                        </p>
                    </div>
                </div>

                <div class="mb-10">
                    <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Requirement Description</h4>
                    <div class="p-6 bg-[#f7f7f7] rounded-[12px] border border-[#e5e5e5] text-[14px] text-[#555555] leading-relaxed">
                        {{ $ticket->description }}
                    </div>
                </div>

                @php
                    $pdfPath = $ticket->recommendation?->file_path ?? $ticket->disposal?->file_path;
                @endphp

                @if($pdfPath)
                    <div class="mb-10">
                        <h4 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Resolution Document</h4>
                        <a href="{{ Storage::url($pdfPath) }}" target="_blank" class="flex items-center gap-3 p-4 bg-red-50 border border-red-100 text-red-700 hover:bg-red-100 transition-all group">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h1.5m1.5 0H13m-3 3h3m-3 3h3" /></svg>
                            <div class="flex flex-col">
                                <span class="text-[13px] font-bold uppercase tracking-wider">Attached File</span>
                                <span class="text-[10px] text-red-500 font-medium">Click to view or download</span>
                            </div>
                        </a>
                    </div>
                @endif

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
