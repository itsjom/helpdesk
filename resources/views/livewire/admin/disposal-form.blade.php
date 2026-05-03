<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Process Disposal</h1>
                <p class="mt-2 text-sm text-gray-600">Documenting cause of disposal for <span class="font-bold text-indigo-600">{{ $ticket->ticket_no }}</span></p>
            </div>
            <a href="{{ route('admin.tickets') }}" wire:navigate class="text-sm font-bold text-gray-500 hover:text-gray-700">
                &larr; Back to Tickets
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Ticket Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Request Info</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">User</label>
                            <p class="text-sm font-bold text-gray-900">{{ $ticket->user->username }}</p>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 uppercase">Description</label>
                            <p class="text-sm text-gray-700 italic leading-relaxed">"{{ $ticket->description }}"</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                    <div class="p-8">
                        <form wire:submit="save" class="space-y-6">
                            <div>
                                <label for="cause" class="block text-sm font-bold text-gray-700 mb-2">Cause of Disposal / Assessment</label>
                                <p class="text-xs text-gray-500 mb-4">Describe the reason for disposal (e.g., beyond economical repair, obsolete, physical damage).</p>
                                
                                <textarea 
                                    wire:model="cause" 
                                    id="cause" 
                                    rows="10" 
                                    class="block w-full border-gray-200 rounded-2xl focus:ring-indigo-500 focus:border-indigo-500 text-sm p-4 bg-gray-50"
                                    placeholder="Enter detailed cause of disposal here..."
                                ></textarea>
                                <x-input-error :messages="$errors->get('cause')" class="mt-2" />
                            </div>

                            <div class="pt-6 border-t border-gray-100 flex items-center justify-end">
                                <button type="submit" wire:loading.attr="disabled" class="inline-flex items-center px-8 py-3 bg-red-600 text-white font-bold rounded-xl shadow-lg hover:bg-red-700 transition-all transform hover:scale-105 active:scale-95 disabled:opacity-50">
                                    <span wire:loading.remove>Finalize & Resolve</span>
                                    <span wire:loading class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Processing...
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
