<div>
    <div class="mb-12 flex items-center justify-between">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">Create Recommendation</h1>
            <p class="text-[13px] text-[#999999] mt-1">Generating hardware specifications for <span class="font-semibold text-[#2d2d2d]">{{ $ticket->ticket_no }}</span></p>
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
                <h3 class="text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-6 border-b border-[#e5e5e5] pb-3">User Request</h3>
                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block mb-1">Requested By</label>
                        <p class="text-[13px] font-semibold text-[#2d2d2d]">{{ $ticket->user->username }}</p>
                        <p class="text-[11px] text-[#999999]">{{ $ticket->user->department->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block mb-1">Requirement</label>
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
                <form wire:submit="generate" class="space-y-8">
                    <div>
                        <label for="specs" class="block text-[11px] font-medium text-[#999999] uppercase tracking-widest mb-3">Hardware Specifications & Recommendation</label>
                        <p class="text-[12px] text-[#555555] mb-4 leading-relaxed">Enter the recommended specs (Processor, RAM, Storage, etc.) that will be included in the PDF document.</p>
                        
                        <textarea 
                            wire:model="specs" 
                            id="specs" 
                            rows="15" 
                            class="input-field w-full rounded-none font-mono text-[13px] p-4"
                            placeholder="Example:
Laptop Model: Dell Latitude 5440
Processor: Intel Core i7-1355U
RAM: 16GB DDR4
Storage: 512GB SSD NVMe
Display: 14.0 FHD"
                        ></textarea>
                        <x-input-error :messages="$errors->get('specs')" class="mt-2" />
                    </div>

                    <div class="pt-8 border-t border-[#f0f0f0] flex items-center justify-between">
                        <div class="flex items-center text-[11px] text-[#999999] italic uppercase tracking-wider">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            PDF will be saved and ticket marked as resolved.
                        </div>
                        <button type="submit" wire:loading.attr="disabled" class="btn-primary px-10 py-3">
                            <span wire:loading.remove>Generate & Send PDF</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Generating...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

