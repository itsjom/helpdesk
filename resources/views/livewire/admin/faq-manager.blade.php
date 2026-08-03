<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage FAQs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Frequently Asked Questions</h3>
                        <button wire:click="create" class="btn-primary flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add FAQ
                        </button>
                    </div>

                    <!-- Flash Message -->
                    @if (session()->has('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Table -->
                    <div class="overflow-x-auto border border-gray-200 rounded-md">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th scope="col" class="px-6 py-3 font-medium">Question</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Answer</th>
                                    <th scope="col" class="px-6 py-3 font-medium">Status</th>
                                    <th scope="col" class="px-6 py-3 font-medium text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($faqs as $faq)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $faq->question }}
                                        </td>
                                        <td class="px-6 py-4 truncate max-w-xs">
                                            {{ Str::limit($faq->answer, 50) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($faq->is_active)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-400">Active</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <button wire:click="edit({{ $faq->id }})" class="font-medium text-blue-600 hover:underline">Edit</button>
                                            <button wire:click="confirmDelete({{ $faq->id }})" class="font-medium text-red-600 hover:underline">Delete</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No FAQs found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $faqs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <x-modal name="faq-modal" maxWidth="2xl">
        <form wire:submit="save" class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                {{ $isEditMode ? 'Edit FAQ' : 'Create New FAQ' }}
            </h2>

            <div class="space-y-4">
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                    <input type="text" id="question" wire:model="question" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <x-input-error :messages="$errors->get('question')" class="mt-2" />
                </div>

                <div>
                    <label for="answer" class="block text-sm font-medium text-gray-700">Answer</label>
                    <textarea id="answer" wire:model="answer" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    <x-input-error :messages="$errors->get('answer')" class="mt-2" />
                </div>

                <div class="flex items-center">
                    <input id="is_active" type="checkbox" wire:model="is_active" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" wire:click="$dispatch('close-modal', 'faq-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md hover:bg-indigo-700">
                    {{ $isEditMode ? 'Update FAQ' : 'Save FAQ' }}
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-confirmation-modal" maxWidth="md">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">Are you sure you want to delete this FAQ?</h2>
            <p class="mt-1 text-sm text-gray-600">This action cannot be undone.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" wire:click="$set('deletingId', null); $dispatch('close-modal', 'delete-confirmation-modal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    Cancel
                </button>
                <button type="button" wire:click="delete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </x-modal>
</div>
