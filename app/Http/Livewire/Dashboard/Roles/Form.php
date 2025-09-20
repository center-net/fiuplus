<?php

namespace App\Http\Livewire\Dashboard\Roles;

use App\Models\Permission;
use App\Models\Role;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use AuthorizesRequests;

    public $role_id;
    public $name = '';
    public $key = '';
    public $color = '';
    public $selectedPermissions = [];

    public $permissions = [];

    protected $listeners = ['openRoleFormModal' => 'loadRole'];

    public function mount()
    {
        // Base access guard: must be able to view roles listing to use the form
        $this->authorize('viewAny', Role::class);
        $this->permissions = Permission::orderBy('table_name')->orderBy('name')->get();
        $this->resetForm();
    }

    public function loadRole($roleId = null)
    {
        $this->resetForm();
        $this->role_id = $roleId;

        if ($roleId) {
            $role = Role::with('permissions')->findOrFail($roleId);
            $this->authorize('update', $role);
            $this->name = $role->name;
            $this->key = $role->key;
            $this->color = $role->color;
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
        } else {
            // Creating a new role requires create ability
            $this->authorize('create', Role::class);
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($this->role_id)],
            'color' => ['nullable', 'string', 'max:50'],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['exists:permissions,id'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->role_id) {
            $role = Role::findOrFail($this->role_id);
            $this->authorize('update', $role);
            $role->update($data);
            $role->permissions()->sync($this->selectedPermissions);
            // مسح كاش صلاحيات جميع المستخدمين المرتبطين بهذا الدور
            $role->load('users');
            foreach ($role->users as $u) {
                $u->clearPermissionsCache();
            }
            $this->dispatch('show-toast', message: 'تم تحديث الدور بنجاح.');
        } else {
            $this->authorize('create', Role::class);
            $role = Role::create($data);
            $role->permissions()->sync($this->selectedPermissions);
            // لا يوجد مستخدمون مرتبطون بعد، لكن نضمن تنظيف كاش أي مستخدم يضاف لاحقاً عبر حدث خارجي إن لزم
            $this->dispatch('show-toast', message: 'تم اضافة الدور بنجاح.');
        }

        $this->dispatch('roleSaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->role_id = null;
        $this->name = '';
        $this->key = '';
        $this->color = '';
        $this->selectedPermissions = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.roles.form');
    }
}