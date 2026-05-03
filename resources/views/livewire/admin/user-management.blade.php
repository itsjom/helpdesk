<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-[24px] font-bold text-[#2d2d2d] tracking-tight">Management</h2>
        <div class="flex gap-4">
            <div class="flex bg-[#f7f7f7] border border-[#e5e5e5] p-1 rounded-none">
                <button wire:click="switchView('users')" 
                    class="px-4 py-1.5 text-[12px] font-bold uppercase tracking-wider {{ $view === 'users' ? 'bg-[#2d2d2d] text-white' : 'text-[#999999] hover:text-[#2d2d2d]' }} transition-all">
                    Users
                </button>
                <button wire:click="switchView('departments')" 
                    class="px-4 py-1.5 text-[12px] font-bold uppercase tracking-wider {{ $view === 'departments' ? 'bg-[#2d2d2d] text-white' : 'text-[#999999] hover:text-[#2d2d2d]' }} transition-all">
                    Departments
                </button>
                <button wire:click="switchView('service_types')" 
                    class="px-4 py-1.5 text-[12px] font-bold uppercase tracking-wider {{ $view === 'service_types' ? 'bg-[#2d2d2d] text-white' : 'text-[#999999] hover:text-[#2d2d2d]' }} transition-all">
                    Service types
                </button>
            </div>

            @if($view === 'users')
                <button wire:click="openCreateModal" 
                    class="btn-primary flex items-center gap-2 {{ $dept_list->isEmpty() ? 'opacity-50 cursor-not-allowed' : '' }}"
                    @if($dept_list->isEmpty()) disabled title="Create a department first" @endif>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New User
                </button>
            @elseif($view === 'departments')
                <button x-data x-on:click="$dispatch('open-modal', 'add-dept-modal')" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New Department
                </button>
            @else
                <button wire:click="openServiceTypeModal" type="button" class="btn-primary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    New service type
                </button>
            @endif
        </div>
    </div>

    @if($dept_list->isEmpty() && $view === 'users')
        <div class="bg-[#f7f7f7] border-l-4 border-[#2d2d2d] p-4 text-[13px] text-[#555555]">
            <p><strong>Notice:</strong> You must create at least one department before you can add users.</p>
        </div>
    @endif

    <div class="flex items-center gap-4 bg-white border border-[#e5e5e5] p-4 rounded-none">
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-[#999999]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" 
                class="input-field w-full pl-10" 
                placeholder="Search @if($view === 'service_types')service types@else{{ $view }}@endif...">
        </div>
    </div>

    <div class="bg-white border border-[#e5e5e5] rounded-none overflow-hidden">
        @if($view === 'users')
            <table class="w-full text-left">
                <thead class="bg-[#f7f7f7] border-b border-[#e5e5e5]">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">User</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Role</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Department</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f0f0]">
                    @forelse($users as $user)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-none bg-[#2d2d2d] flex items-center justify-center text-[11px] font-bold text-white uppercase">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-[14px] font-bold text-[#2d2d2d]">{{ $user->name }}</div>
                                        <div class="text-[12px] text-[#999999]">{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 border {{ $user->role === 'admin' ? 'bg-[#2d2d2d] text-white border-[#2d2d2d]' : 'bg-white text-[#2d2d2d] border-[#e5e5e5]' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-[13px] text-[#555555]">{{ $user->department?->name ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end flex-wrap items-center gap-3">
                                    @if($user->role === 'user')
                                        <a href="{{ route('admin.tickets', ['requester' => $user->id]) }}" wire:navigate
                                            class="inline-flex items-center btn-secondary text-[11px] py-1.5 px-3 uppercase tracking-widest font-bold">
                                            Tickets
                                        </a>
                                    @endif
                                    <button wire:click="editUser({{ $user->id }})" 
                                        class="text-[11px] font-bold text-[#2d2d2d] hover:underline uppercase tracking-widest transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="confirmUserDeletion({{ $user->id }})" 
                                        class="text-[11px] font-bold text-red-600 hover:text-red-800 uppercase tracking-widest transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-[13px] text-[#999999] italic">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t border-[#e5e5e5]">
                {{ $users->links() }}
            </div>
        @elseif($view === 'departments')
            <table class="w-full text-left">
                <thead class="bg-[#f7f7f7] border-b border-[#e5e5e5]">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Department Name</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">User Count</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f0f0]">
                    @forelse($departments as $dept)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-4 text-[14px] font-bold text-[#2d2d2d]">{{ $dept->name }}</td>
                            <td class="px-6 py-4 text-[13px] text-[#555555]">{{ $dept->users_count }} users</td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="confirmDepartmentDeletion({{ $dept->id }})" 
                                    class="text-[11px] font-bold text-red-600 hover:text-red-800 uppercase tracking-widest transition-colors">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-[13px] text-[#999999] italic">No departments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t border-[#e5e5e5]">
                {{ $departments->links() }}
            </div>
        @elseif($view === 'service_types')
            <table class="w-full text-left">
                <thead class="bg-[#f7f7f7] border-b border-[#e5e5e5]">
                    <tr>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Name</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Code</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Sort</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Active</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest">Workflow</th>
                        <th class="px-6 py-4 text-[11px] font-bold text-[#999999] uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#f0f0f0]">
                    @forelse($serviceTypes as $st)
                        <tr class="hover:bg-[#fafafa] transition-colors">
                            <td class="px-6 py-4 text-[14px] font-bold text-[#2d2d2d]">{{ $st->name }}</td>
                            <td class="px-6 py-4 text-[12px] font-mono text-[#555555]">{{ $st->code }}</td>
                            <td class="px-6 py-4 text-[13px] text-[#555555]">{{ $st->sort_order }}</td>
                            <td class="px-6 py-4 text-[13px] text-[#555555]">{{ $st->is_active ? 'Yes' : 'No' }}</td>
                            <td class="px-6 py-4 text-[12px] text-[#555555] capitalize">{{ str_replace('_', ' ', $st->kind) }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-4">
                                    <button wire:click="editServiceType({{ $st->id }})" type="button"
                                        class="text-[11px] font-bold text-[#2d2d2d] hover:underline uppercase tracking-widest transition-colors">
                                        Edit
                                    </button>
                                    <button wire:click="confirmServiceTypeDeletion({{ $st->id }})" type="button"
                                        class="text-[11px] font-bold text-red-600 hover:text-red-800 uppercase tracking-widest transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-[13px] text-[#999999] italic">No service types found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-6 border-t border-[#e5e5e5]">
                {{ $serviceTypes->links() }}
            </div>
        @endif
    </div>

    <!-- Modals -->
    <x-modal name="user-modal" :title="$isEditing ? 'Edit User' : 'New User'" focusable>
        <form wire:submit="{{ $isEditing ? 'updateUser' : 'createUser' }}" class="p-8 space-y-6">
            <div class="space-y-2">
                <x-input-label for="name" value="Full Name" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="name" id="name" type="text" class="block w-full" placeholder="e.g. Juan dela Cruz" required />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <div class="space-y-2">
                <x-input-label for="username" value="Username" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="username" id="username" type="text" class="block w-full" placeholder="Unique identifier" required />
                <x-input-error :messages="$errors->get('username')" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-input-label for="role" value="Role" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                    <select wire:model="role" id="role" class="input-field w-full">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" />
                </div>
                <div class="space-y-2">
                    <x-input-label for="department_id" value="Department" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                    <select wire:model="department_id" id="department_id" class="input-field w-full">
                        <option value="">Select Department</option>
                        @foreach($dept_list as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department_id')" />
                </div>
            </div>

            <div class="space-y-2">
                <x-input-label for="password" value="{{ $isEditing ? 'New Password (Leave blank to keep current)' : 'Password' }}" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="password" id="password" type="password" class="block w-full" :required="!$isEditing" />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-[#f0f0f0]">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>{{ $isEditing ? 'Update Account' : 'Create Account' }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="service-type-modal" :title="$isEditingServiceType ? 'Edit service type' : 'New service type'" focusable>
        <form wire:submit="saveServiceType" class="p-8 space-y-6">
            <p class="text-[12px] text-[#555555] leading-relaxed">
                The <strong>code</strong> is generated automatically when you create a type (used in the database). Use <strong>Workflow</strong> to attach the recommendation PDF or disposal flow — only one type each for recommendation and disposal.
            </p>
            @if($isEditingServiceType && $st_code_readonly)
                    <div class="text-[11px] font-mono text-[#555555] bg-[#f7f7f7] border border-[#e5e5e5] px-3 py-2 rounded-none">
                        Code: <span class="text-[#2d2d2d]">{{ $st_code_readonly }}</span>
                    </div>
            @endif
            <div class="space-y-2">
                <x-input-label for="st_name" value="Display name" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="st_name" id="st_name" type="text" class="block w-full" placeholder="e.g. Network / Internet" required />
                <x-input-error :messages="$errors->get('st_name')" />
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <x-input-label for="st_sort_order" value="Sort order" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                    <x-text-input wire:model="st_sort_order" id="st_sort_order" type="number" min="0" class="block w-full" required />
                    <x-input-error :messages="$errors->get('st_sort_order')" />
                </div>
                <div class="space-y-2 flex flex-col justify-end">
                    <label class="flex items-center gap-2 text-[13px] text-[#555555] cursor-pointer">
                        <input type="checkbox" wire:model="st_is_active" class="rounded-none border-[#e5e5e5] text-[#2d2d2d] focus:ring-0" />
                        Active (shown in request form)
                    </label>
                </div>
            </div>
            <div class="space-y-2">
                <x-input-label for="st_kind" value="Workflow" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <select wire:model="st_kind" id="st_kind" class="input-field w-full">
                    <option value="general">Standard ticket</option>
                    <option value="recommendation">Recommendation (PDF workflow)</option>
                    <option value="disposal">Disposal workflow</option>
                </select>
                <x-input-error :messages="$errors->get('st_kind')" />
            </div>
            <div class="flex justify-end gap-3 pt-6 border-t border-[#f0f0f0]">
                <x-secondary-button type="button" x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>{{ $isEditingServiceType ? 'Save changes' : 'Create' }}</x-primary-button>
            </div>
        </form>
    </x-modal>

    <x-modal name="add-dept-modal" title="New Department" focusable>
        <form wire:submit="createDepartment" class="p-8 space-y-6">
            <div class="space-y-2">
                <x-input-label for="dept_name" value="Department Name" class="text-[11px] font-bold uppercase tracking-widest text-[#999999]" />
                <x-text-input wire:model="dept_name" id="dept_name" type="text" class="block w-full" placeholder="e.g. IT, Finance, HR" required />
                <x-input-error :messages="$errors->get('dept_name')" />
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-[#f0f0f0]">
                <x-secondary-button x-on:click="$dispatch('close')">Cancel</x-secondary-button>
                <x-primary-button>Create Department</x-primary-button>
            </div>
        </form>
    </x-modal>

    <!-- Delete Confirmation Modal -->
    <x-modal name="delete-confirmation-modal" maxWidth="sm" focusable>
        <div class="p-6">
            <div class="mb-5 text-center">
                <h3 class="text-[16px] font-bold text-[#2d2d2d] mb-1">
                    Delete @if($deleteType === 'service_type')service type@elseif($deleteType === 'department')department@else{{ $deleteType }}@endif
                </h3>
                <p class="text-[13px] text-[#555555]">
                    Remove <span class="font-bold text-[#2d2d2d]">"{{ $deletingName }}"</span>?
                </p>
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
