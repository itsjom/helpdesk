<?php

namespace App\Livewire\Admin;

use App\Models\Department;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class UserManagement extends Component
{
    use WithPagination;

    // User Fields
    public $name = '';

    public $username = '';

    public $password = '';

    public $role = 'user';

    public $department_id = '';

    // Department Fields
    public $dept_name = '';

    public $isEditingDept = false;

    public $editingDeptId = null;

    // Service type fields
    public $st_name = '';

    public $st_sort_order = 0;

    public $st_is_active = true;

    /** Shown in edit modal only (code is immutable after create) */
    public $st_code_readonly = '';

    public $isEditingServiceType = false;

    public $editingServiceTypeId = null;

    public $search = '';

    public $view = 'users'; // 'users' | 'departments' | 'service_types'

    public $isEditing = false;

    public $editingUserId = null;

    // Deletion Confirmation
    public $confirmingDeletion = false;

    public $deletingId = null;

    public $deleteType = ''; // 'user' | 'department' | 'service_type' | 'role'

    public $deletingName = '';

    // Role Fields
    public $role_name = '';
    public $selected_permissions = [];
    public $isEditingRole = false;
    public $editingRoleId = null;

    protected function rules()
    {
        if ($this->view === 'users') {
            return [
                'name' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    $this->isEditing ? Rule::unique('users')->ignore($this->editingUserId) : 'unique:users,username',
                ],
                'password' => $this->isEditing ? 'nullable|string|min:8' : 'required|string|min:8',
                'role' => 'required|exists:roles,name',
                'department_id' => 'required|exists:departments,id',
            ];
        }

        if ($this->view === 'service_types') {
            return [
                'st_name' => 'required|string|max:255',
                'st_sort_order' => 'required|integer|min:0|max:65535',
                'st_is_active' => 'boolean',
            ];
        }

        if ($this->view === 'roles') {
            return [
                'role_name' => [
                    'required',
                    'string',
                    'max:255',
                    $this->isEditingRole ? Rule::unique('roles', 'name')->ignore($this->editingRoleId) : 'unique:roles,name',
                ],
                'selected_permissions' => 'array',
                'selected_permissions.*' => 'exists:permissions,name',
            ];
        }

        return [
            'dept_name' => [
                'required',
                'string',
                'max:255',
                $this->isEditingDept ? Rule::unique('departments', 'name')->ignore($this->editingDeptId) : 'unique:departments,name',
            ],
        ];
    }

    public function createUser()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'department_id' => $this->department_id,
        ]);

        $user->syncRoles([$this->role]);

        $this->resetFields();
        $this->dispatch('close-modal', 'user-modal');
        session()->flash('success', 'User '.$this->username.' created successfully!');
    }

    public function editUser($id)
    {
        $this->isEditing = true;
        $this->editingUserId = $id;
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->username = $user->username;
        $this->role = $user->role;
        $this->department_id = $user->department_id;
        $this->password = '';

        $this->dispatch('open-modal', 'user-modal');
    }

    public function updateUser()
    {
        $this->validate();

        $user = User::findOrFail($this->editingUserId);

        $data = [
            'name' => $this->name,
            'username' => $this->username,
            'role' => $this->role,
            'department_id' => $this->department_id,
        ];

        if (! empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);
        $user->syncRoles([$this->role]);

        $this->resetFields();
        $this->dispatch('close-modal', 'user-modal');
        session()->flash('success', 'User updated successfully!');
    }

    public function createDepartment()
    {
        $this->validate();

        Department::create(['name' => $this->dept_name]);

        $this->reset(['dept_name', 'isEditingDept', 'editingDeptId']);
        $this->dispatch('close-modal', 'dept-modal');
        session()->flash('success', 'Department created successfully!');
    }

    public function editDepartment($id)
    {
        $this->isEditingDept = true;
        $this->editingDeptId = $id;
        $dept = Department::findOrFail($id);
        $this->dept_name = $dept->name;
        $this->dispatch('open-modal', 'dept-modal');
    }

    public function updateDepartment()
    {
        $this->validate();

        $dept = Department::findOrFail($this->editingDeptId);
        $dept->update(['name' => $this->dept_name]);

        $this->reset(['dept_name', 'isEditingDept', 'editingDeptId']);
        $this->dispatch('close-modal', 'dept-modal');
        session()->flash('success', 'Department updated successfully!');
    }

    public function openServiceTypeModal()
    {
        $this->isEditingServiceType = false;
        $this->editingServiceTypeId = null;
        $this->st_name = '';
        $this->st_sort_order = (int) (ServiceType::max('sort_order') ?? 0) + 10;
        $this->st_is_active = true;
        $this->st_code_readonly = '';
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'service-type-modal');
    }

    public function editServiceType($id)
    {
        $st = ServiceType::findOrFail($id);
        $this->isEditingServiceType = true;
        $this->editingServiceTypeId = $id;
        $this->st_name = $st->name;
        $this->st_sort_order = $st->sort_order;
        $this->st_is_active = $st->is_active;
        $this->st_code_readonly = $st->code;
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'service-type-modal');
    }

    public function saveServiceType()
    {
        $this->validate();

        if ($this->isEditingServiceType) {
            $st = ServiceType::findOrFail($this->editingServiceTypeId);
            $st->update([
                'name' => $this->st_name,
                'sort_order' => $this->st_sort_order,
                'is_active' => $this->st_is_active,
                'kind' => ServiceType::KIND_GENERAL,
            ]);
            session()->flash('success', 'Service type updated.');
        } else {
            ServiceType::create([
                'code' => $this->generateUniqueServiceTypeCode($this->st_name),
                'name' => $this->st_name,
                'sort_order' => $this->st_sort_order,
                'is_active' => $this->st_is_active,
                'kind' => ServiceType::KIND_GENERAL,
            ]);
            session()->flash('success', 'Service type created.');
        }

        $this->dispatch('close-modal', 'service-type-modal');
        $this->resetServiceTypeForm();
    }

    protected function resetServiceTypeForm(): void
    {
        $this->reset(['st_name', 'st_sort_order', 'st_is_active', 'isEditingServiceType', 'editingServiceTypeId', 'st_code_readonly']);
        $this->st_sort_order = 0;
        $this->st_is_active = true;
    }

    protected function generateUniqueServiceTypeCode(string $name): string
    {
        $base = Str::slug($name, '_');
        if ($base === '') {
            $base = 'service_type';
        }
        $code = substr($base, 0, 64);
        $n = 1;
        while (ServiceType::where('code', $code)->exists()) {
            $suffix = '_'.$n++;
            $code = substr($base, 0, max(1, 64 - strlen($suffix))).$suffix;
        }

        return $code;
    }

    public function openRoleModal()
    {
        $this->isEditingRole = false;
        $this->editingRoleId = null;
        $this->role_name = '';
        $this->selected_permissions = [];
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'role-modal');
    }

    public function createRole()
    {
        $this->validate();

        $role = \Spatie\Permission\Models\Role::create(['name' => $this->role_name]);
        $role->syncPermissions($this->selected_permissions);

        $this->reset(['role_name', 'selected_permissions', 'isEditingRole', 'editingRoleId']);
        $this->dispatch('close-modal', 'role-modal');
        session()->flash('success', 'Role created successfully!');
    }

    public function editRole($id)
    {
        $this->isEditingRole = true;
        $this->editingRoleId = $id;
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        
        $this->role_name = $role->name;
        $this->selected_permissions = $role->permissions->pluck('name')->toArray();
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'role-modal');
    }

    public function updateRole()
    {
        $this->validate();

        $role = \Spatie\Permission\Models\Role::findOrFail($this->editingRoleId);
        $role->update(['name' => $this->role_name]);
        $role->syncPermissions($this->selected_permissions);

        $this->reset(['role_name', 'selected_permissions', 'isEditingRole', 'editingRoleId']);
        $this->dispatch('close-modal', 'role-modal');
        session()->flash('success', 'Role updated successfully!');
    }


    public function confirmUserDeletion($id)
    {
        $user = User::findOrFail($id);

        if ($user->username === 'admin') {
            $this->dispatch('notify', message: 'You cannot delete the System Administrator!', type: 'error');
            return;
        }

        $this->deleteType = 'user';
        $this->deletingId = $id;
        $this->deletingName = $user->name;
        $this->dispatch('open-modal', 'delete-confirmation-modal');
    }

    public function confirmDepartmentDeletion($id)
    {
        $this->deleteType = 'department';
        $this->deletingId = $id;
        $dept = Department::findOrFail($id);
        $this->deletingName = $dept->name;
        $this->dispatch('open-modal', 'delete-confirmation-modal');
    }

    public function confirmServiceTypeDeletion($id)
    {
        $st = ServiceType::findOrFail($id);
        if ($st->tickets()->exists()) {
            session()->flash('error', 'Cannot delete: tickets still reference this service type.');

            return;
        }
        $this->deleteType = 'service_type';
        $this->deletingId = $id;
        $this->deletingName = $st->name;
        $this->dispatch('open-modal', 'delete-confirmation-modal');
    }

    public function confirmRoleDeletion($id)
    {
        $role = \Spatie\Permission\Models\Role::findOrFail($id);
        if (in_array($role->name, ['admin', 'user'])) {
            $this->dispatch('notify', message: 'You cannot delete a core system role!', type: 'error');
            return;
        }

        $this->deleteType = 'role';
        $this->deletingId = $id;
        $this->deletingName = $role->name;
        $this->dispatch('open-modal', 'delete-confirmation-modal');
    }

    public function delete()
    {
        if ($this->deleteType === 'user') {
            if ($this->deletingId === 1) {
                session()->flash('error', 'You cannot delete yourself!');
                $this->dispatch('close-modal', 'delete-confirmation-modal');

                return;
            }

            $user = User::findOrFail($this->deletingId);
            if ($user->username === 'admin') {
                session()->flash('error', 'You cannot delete the System Administrator!');
                $this->dispatch('close-modal', 'delete-confirmation-modal');
                return;
            }

            $user->delete();
            session()->flash('success', 'User deleted successfully!');
        } elseif ($this->deleteType === 'department') {
            $dept = Department::findOrFail($this->deletingId);
            if ($dept->users()->count() > 0) {
                session()->flash('error', 'Cannot delete department with active users!');
                $this->dispatch('close-modal', 'delete-confirmation-modal');

                return;
            }
            $dept->delete();
            session()->flash('success', 'Department deleted successfully!');
        } elseif ($this->deleteType === 'service_type') {
            ServiceType::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Service type deleted.');
        } elseif ($this->deleteType === 'role') {
            $role = \Spatie\Permission\Models\Role::findOrFail($this->deletingId);
            $role->delete();
            session()->flash('success', 'Role deleted successfully!');
        }

        $this->dispatch('close-modal', 'delete-confirmation-modal');
        $this->reset(['deleteType', 'deletingId', 'deletingName']);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function switchView($newView)
    {
        $this->view = $newView;
        $this->resetPage();
    }

    public function resetFields()
    {
        $this->reset(['name', 'username', 'password', 'role', 'department_id', 'isEditing', 'editingUserId', 'dept_name', 'isEditingDept', 'editingDeptId', 'role_name', 'selected_permissions', 'isEditingRole', 'editingRoleId']);
    }

    public function openCreateModal()
    {
        $this->resetFields();
        $this->isEditing = false;
        $this->dispatch('open-modal', 'user-modal');
    }

    public function openCreateDeptModal()
    {
        $this->resetFields();
        $this->isEditingDept = false;
        $this->dispatch('open-modal', 'dept-modal');
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $users = $this->view === 'users'
            ? User::with('department')
                ->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhereHas('department', function ($query) {
                            $query->where('name', 'like', '%'.$this->search.'%');
                        });
                })
                ->latest()
                ->paginate(10)
            : null;

        $departments = $this->view === 'departments'
            ? Department::withCount('users')
                ->where('name', 'like', '%'.$this->search.'%')
                ->paginate(10)
            : null;

        $serviceTypes = $this->view === 'service_types'
            ? ServiceType::query()
                ->when($this->search, function ($q) {
                    $q->where(function ($qq) {
                        $qq->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('code', 'like', '%'.$this->search.'%');
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(10)
            : null;

        $roles = $this->view === 'roles'
            ? \Spatie\Permission\Models\Role::with('permissions')
                ->where('name', 'like', '%'.$this->search.'%')
                ->paginate(10)
            : null;

        return view('livewire.admin.user-management', [
            'users' => $users,
            'departments' => $departments,
            'serviceTypes' => $serviceTypes,
            'roles' => $roles,
            'dept_list' => Department::all(),
            'role_list' => \Spatie\Permission\Models\Role::all(),
            'permission_list' => \Spatie\Permission\Models\Permission::all(),
        ]);
    }
}
