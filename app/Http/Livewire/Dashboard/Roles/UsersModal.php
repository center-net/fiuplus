<?php

namespace App\Http\Livewire\Dashboard\Roles;

use App\Models\Role;
use Livewire\Component;

class UsersModal extends Component
{
    public $roleId;
    public $roleName;
    public $users = [];

    protected $listeners = ['openUsersModal' => 'loadUsers'];

    public function loadUsers($roleId = null)
    {
        $this->roleId = $roleId;
        $this->users = [];
        $this->roleName = '';

        if ($roleId) {
            $role = Role::with('users')->findOrFail($roleId);
            $this->roleName = $role->name;
            $this->users = $role->users;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.roles.users-modal');
    }
}
