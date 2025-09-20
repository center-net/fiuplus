<?php

namespace App\Http\Livewire\Dashboard\Roles;

use App\Models\Role;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UsersModal extends Component
{
    use AuthorizesRequests;
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
            // Viewing users attached to a role requires read on roles
            $this->authorize('view', $role);
            $this->roleName = $role->name;
            $this->users = $role->users;
        }
    }

    public function render()
    {
        return view('livewire.dashboard.roles.users-modal');
    }
}
