<div>
    <div class="max-w-3xl">
        <div class="mb-12">
            <h1 class="text-[24px] font-semibold text-[#2d2d2d]">Request IT Support</h1>
            <p class="text-[14px] text-[#555555] mt-1">Please provide details about the issue you are experiencing.</p>
        </div>

        <div class="bg-white border border-[#e5e5e5] rounded-none p-10 shadow-sm shadow-black/[0.02]">
            <form wire:submit="save" class="space-y-10">
                <!-- Service Type -->
                <div class="space-y-4">
                    <label for="service_type" class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block">Service Type</label>
                    <select wire:model.live="service_type" id="service_type" class="input-field w-full">
                        <option value="">Select a service type</option>
                        @foreach($serviceTypes as $st)
                            <option value="{{ $st->code }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('service_type')" />
                </div>


                <!-- Description -->
                <div class="space-y-4">
                    <label for="description" class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block">Problem Description</label>
                    <textarea wire:model="description" id="description" rows="5" class="input-field w-full" placeholder="Please describe the issue in detail..."></textarea>
                    <x-input-error :messages="$errors->get('description')" />
                </div>

                <!-- Actions -->
                <div class="pt-10 flex items-center justify-end gap-6 border-t border-[#f0f0f0]">
                    <a href="{{ route('user.tickets') }}" wire:navigate class="text-[13px] font-medium text-[#999999] hover:text-[#2d2d2d] transition-colors">
                        Cancel
                    </a>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary flex items-center gap-3">
                        <span wire:loading.remove>Submit Request</span>
                        <span wire:loading class="flex items-center">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
