<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create Recommendation</h1>
                <p class="mt-2 text-sm text-gray-600">Generating hardware specifications for <span class="font-bold text-indigo-600">{{ $ticket->ticket_no }}</span></p>
            </div>
            <a href="{{ route('admin.tickets') }}" wire:navigate class="text-sm font-bold text-gray-500 hover:text-gray-700">
                &larr; Back to Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Ticket Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">User Request</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Requested By</label>
                            <p class="text-sm font-bold text-gray-900">{{ $ticket->user->username }}</p>
                            <p class="text-xs text-gray-500">{{ $ticket->user->department }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Requirement</label>
                            <p class="text-sm text-gray-700 italic">"{{ $ticket->description }}"</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <form wire:submit="generate" class="space-y-6">
                            <div>
                                <label for="specs" class="block text-sm font-bold text-gray-700 mb-2">Hardware Specifications & Recommendation</label>
                                <p class="text-xs text-gray-500 mb-4">Enter the recommended specs (Processor, RAM, Storage, etc.) that will be included in the PDF.</p>
                                
                                <textarea 
                                    wire:model="specs" 
                                    id="specs" 
                                    rows="12" 
                                    class="block w-full border-gray-200 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 text-sm font-mono p-4 bg-gray-50"
                                    placeholder="Example:
Laptop Model: Dell Latitude 5440
Processor: Intel Core i7-1355U
RAM: 16GB DDR4
Storage: 512GB SSD NVMe
Display: 14.0 FHD"
                                ></textarea>
                                <x-input-error :messages="$errors->get('specs')" class="mt-2" />
                            </div>

                            <div class="pt-6 border-t border-gray-100 flex items-center justify-between">
                                <div class="flex items-center text-xs text-gray-400">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    PDF will be saved to storage and marked as resolved.
                                </div>
                                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all transform hover:scale-105 active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove>Generate & Send PDF</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Generating...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
