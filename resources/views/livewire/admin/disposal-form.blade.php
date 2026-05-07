<div>
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">Process Disposal</h1>
            <p class="text-[13px] text-[#999999] mt-1">Documenting cause of disposal for <span class="font-semibold text-[#2d2d2d]">{{ $ticket->ticket_no }}</span></p>
        </div>
        <a href="{{ route('admin.tickets') }}" wire:navigate class="text-[11px] font-semibold text-[#999999] hover:text-[#2d2d2d] uppercase tracking-widest transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <!-- Left: Ticket Summary -->
        <div class="lg:col-span-1">
            <div class="bg-[#f7f7f7] p-8 border border-[#e5e5e5] rounded-none">
                <h3 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-6 border-b border-[#e5e5e5] pb-3">Request Details</h3>
                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block mb-1">User</label>
                        <p class="text-[13px] font-semibold text-[#2d2d2d]">{{ $ticket->user->username }}</p>
                        <p class="text-[11px] text-[#999999]">{{ $ticket->user->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block mb-1">Description</label>
                        <p class="text-[13px] text-[#555555] italic leading-relaxed">"{{ $ticket->description }}"</p>
                    </div>
                    <div>
                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block mb-1">Priority</label>
                        <x-priority-badge :priority="$ticket->priority" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Form -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#e5e5e5] rounded-none p-8">
                <form wire:submit="save" class="space-y-8">
                    <div>
                        <label for="cause" class="block text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Cause of Disposal / Assessment</label>
                        <p class="text-[12px] text-[#555555] mb-4 leading-relaxed">Describe the reason for disposal (e.g., beyond economical repair, obsolete, physical damage).</p>
                        
                        <textarea 
                            wire:model="cause" 
                            id="cause" 
                            rows="12" 
                            class="input-field w-full rounded-none p-4"
                            placeholder="Enter detailed cause of disposal here..."
                        ></textarea>
                        <x-input-error :messages="$errors->get('cause')" class="mt-2" />
                    </div>

                    <div class="pt-8 border-t border-[#f0f0f0] flex items-center justify-end">
                        <button type="submit" wire:loading.attr="disabled" class="btn-primary px-10 py-3">
                            <span wire:loading.remove>Finalize & Resolve</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

