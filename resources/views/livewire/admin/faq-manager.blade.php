<div>
    <div class="mb-12 flex justify-between items-end">
        <div>
            <h1 class="text-[24px] font-semibold text-[#2d2d2d] uppercase tracking-widest">FAQs</h1>
            <p class="text-[14px] text-[#555555] mt-1">Manage the questions and answers shown to users.</p>
        </div>
        <button wire:click="create" class="btn-primary flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Add FAQ
        </button>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 px-4 py-3 bg-[#f7f7f7] border border-[#e5e5e5] text-[13px] text-[#2d2d2d]">
            {{ session('success') }}
        </div>
    @endif

    <!-- FAQ Table -->
    <div class="border border-[#e5e5e5] rounded-none overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#f0f0f0]">
                <thead class="bg-[#f7f7f7]">
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Question</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Answer</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#999999] uppercase tracking-widest">Status</th>
                        <th class="px-6 py-3 text-right text-[11px] font-medium text-[#999999] uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-[#f0f0f0]">
                    @forelse ($faqs as $faq)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-5 text-[13px] font-medium text-[#2d2d2d] max-w-xs">
                                {{ $faq->question }}
                            </td>
                            <td class="px-6 py-5 text-[13px] text-[#555555] truncate max-w-xs">
                                {{ Str::limit($faq->answer, 50) }}
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @if($faq->is_active)
                                    <span class="inline-flex items-center gap-2 text-[12px] text-[#2d2d2d] font-medium">
                                        <span class="w-[6px] h-[6px] rounded-full shrink-0 bg-[#2d2d2d]"></span>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 text-[12px] text-[#999999]">
                                        <span class="w-[6px] h-[6px] rounded-full shrink-0 bg-[#e0e0e0]"></span>Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <button wire:click="edit({{ $faq->id }})" class="text-[12px] font-bold text-[#2d2d2d] hover:text-[#555555] uppercase tracking-wider">Edit</button>
                                    <button wire:click="confirmDelete({{ $faq->id }})" class="text-[12px] font-bold text-red-600 hover:text-red-800 uppercase tracking-wider">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <p class="text-[13px] text-[#999999] italic">No FAQs found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($faqs->hasPages())
        <div class="mt-8 px-6">
            {{ $faqs->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    <x-modal name="faq-modal" maxWidth="2xl" focusable>
        <form wire:submit="save" class="p-8 space-y-6">
            <h2 class="text-[16px] font-semibold text-[#2d2d2d] mb-2">
                {{ $isEditMode ? 'Edit FAQ' : 'Create new FAQ' }}
            </h2>

            <div class="space-y-2">
                <x-input-label for="question" value="Question" class="text-[11px] font-medium uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="question" id="question" type="text" class="block w-full" required />
                <x-input-error :messages="$errors->get('question')" class="mt-2" />
            </div>

            <div class="space-y-2">
                <x-input-label for="answer" value="Answer" class="text-[11px] font-medium uppercase tracking-widest text-[#999999]" />
                <textarea id="answer" wire:model="answer" rows="4" class="input-field w-full"></textarea>
                <x-input-error :messages="$errors->get('answer')" class="mt-2" />
            </div>

            <div class="flex items-center gap-2">
                <input id="is_active" type="checkbox" wire:model="is_active" class="rounded-none border-[#e5e5e5] text-[#2d2d2d] focus:ring-0">
                <label for="is_active" class="text-[13px] text-[#555555]">Active</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-[#f0f0f0]">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button type="submit">{{ $isEditMode ? 'Update FAQ' : 'Save FAQ' }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-confirmation-modal" maxWidth="sm" focusable>
        <div class="p-6">
            <div class="mb-5 text-center">
                <h3 class="text-[16px] font-semibold text-[#2d2d2d] mb-1">Delete FAQ</h3>
                <p class="text-[13px] text-[#555555]">This action cannot be undone.</p>
            </div>
            <div class="flex justify-center gap-3 pt-5 border-t border-[#f0f0f0]">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <button wire:click="delete" class="border border-red-600 text-red-600 bg-transparent rounded-none px-5 py-2 text-[13px] font-medium transition-all hover:bg-red-50 active:scale-95">
                    Delete
                </button>
            </div>
        </div>
    </x-modal>
</div>
