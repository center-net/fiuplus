<?php

namespace App\Http\Livewire\Dashboard\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $permission_id;
    public $name = '';
    public $key = '';
    public $table_name = '';

    protected $listeners = ['openPermissionFormModal' => 'loadPermission'];

    public function mount()
    {
        $this->resetForm();
    }

    public function loadPermission($permissionId = null)
    {
        $this->resetForm();
        $this->permission_id = $permissionId;

        if ($permissionId) {
            $p = Permission::findOrFail($permissionId);
            $this->name = $p->name;
            $this->key = $p->key;
            $this->table_name = $p->table_name;
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
            $p->update($data);
            $this->dispatch('show-toast', message: 'تم تحديث الصلاحية بنجاح.');
        } else {
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