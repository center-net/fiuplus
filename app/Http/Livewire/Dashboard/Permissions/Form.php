<?php

namespace App\Http\Livewire\Dashboard\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public $permission_id;
    public $name = '';
    public $key = '';
    public $table_name = '';

    protected $listeners = ['openPermissionFormModal' => 'loadPermission'];

    public function mount()
    {
        // Require at least listing permission to access the form component
        $this->authorize('viewAny', Permission::class);
        $this->resetForm();
    }

    public function loadPermission($permissionId = null)
    {
        $this->resetForm();
        $this->permission_id = $permissionId;

        if ($permissionId) {
            $p = Permission::findOrFail($permissionId);
            $this->authorize('update', $p);
            $this->name = $p->name;
            $this->key = $p->key;
            $this->table_name = $p->table_name;
        } else {
            $this->authorize('create', Permission::class);
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'key' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($this->permission_id)],
            'table_name' => ['required', 'string', 'max:255'],
        ];
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->permission_id) {
            $p = Permission::findOrFail($this->permission_id);
            $this->authorize('update', $p);
            $p->update($data);
            $this->dispatch('show-toast', message: 'تم تحديث الصلاحية بنجاح.');
        } else {
            $this->authorize('create', Permission::class);
            Permission::create($data);
            $this->dispatch('show-toast', message: 'تم اضافة الصلاحية بنجاح.');
        }

        $this->dispatch('permissionSaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->permission_id = null;
        $this->name = '';
        $this->key = '';
        $this->table_name = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.permissions.form');
    }
}