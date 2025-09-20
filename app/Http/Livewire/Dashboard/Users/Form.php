<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\Role;
use App\Models\User;
use App\Traits\HasLocationLogic;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Form extends Component
{
    use WithFileUploads, HasLocationLogic, AuthorizesRequests;

    public $user_id;
    public $name;
    public $username;
    public $email;
    public $phone;
    public $password;
    public $avatar;
    public $country_id;
    public $city_id;
    public $village_id;
    public $selectedRoles = [];
    public $defaultRoleId;

    public $roles = [];

    protected $listeners = ['openUserFormModal' => 'loadUser'];

    public function mount()
    {
        // Authorization: creating new user or updating existing one via modal logic happens later, but component requires base access
        $this->authorize('viewAny', User::class);

        $this->loadCountries();
        $this->roles = Role::select('id','name','key','color')->get();

        $defaultRole = Role::where('key', 'user')->first();
        if ($defaultRole) {
            $this->defaultRoleId = $defaultRole->id;
            $this->selectedRoles = [$this->defaultRoleId];
        }

        $this->resetForm();
    }



    public function loadUser($userId = null)
    {
        $this->resetForm();
        $this->user_id = $userId;

        if ($userId) {
            $user = User::with('roles')->findOrFail($userId);
            $this->name = $user->name;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->avatar = $user->avatar;
            $this->country_id = $user->country_id;
            $this->city_id = $user->city_id;
            $this->village_id = $user->village_id;

            $this->selectedRoles = $user->roles->pluck('id')->toArray();

            $this->loadCities($this->country_id);
            $this->loadVillages($this->city_id);
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user_id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($this->user_id)],
            'password' => $this->user_id ? 'nullable|string|min:8' : 'required|string|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,webp,avif|max:2048', // Max 2MB with explicit mimes
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'village_id' => 'nullable|exists:villages,id',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
        ];
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Handle file upload (store first to set path)
        if ($this->avatar && !is_string($this->avatar)) {
            $validatedData['avatar'] = $this->avatar->store('users/avatars', 'public');
        }

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            $this->authorize('update', $user);
            
            // Let model cast hash the password
            if ($this->password) {
                $validatedData['password'] = $this->password;
            } else {
                unset($validatedData['password']); // Don't update password if it's empty
            }

            $oldAvatar = $user->avatar ?? null;
            $user->update($validatedData);
            $user->roles()->sync($this->selectedRoles);
            $user->clearPermissionsCache();

            // Remove old avatar if changed
            if (isset($validatedData['avatar']) && $oldAvatar && $oldAvatar !== $validatedData['avatar']) {
                Storage::disk('public')->delete($oldAvatar);
            }

            $this->dispatch('show-toast', message: 'تم تحديث المستخدم بنجاح.');
        } else {
            // Ensure there's a role for new users
            if (empty($this->selectedRoles)) {
                $this->selectedRoles = [$this->defaultRoleId];
            }
            // Require create ability when adding new user
            $this->authorize('create', User::class);
            
            // Let model cast hash the password
            $validatedData['password'] = $this->password;

            $user = User::create($validatedData);
            $user->roles()->sync($this->selectedRoles);
            $user->clearPermissionsCache();

            $this->dispatch('show-toast', message: 'تم اضافة المستخدم بنجاح.');
        }

        $this->dispatch('userSaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->user_id = null;
        $this->name = '';
        $this->username = '';
        $this->email = '';
        $this->phone = '';
        $this->password = '';
        $this->avatar = null;
        $this->country_id = null;
        $this->city_id = null;
        $this->village_id = null;

        if (!$this->user_id && isset($this->defaultRoleId)) {
            $this->selectedRoles = [$this->defaultRoleId];
        } else {
            $this->selectedRoles = [];
        }

        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.users.form');
    }
}
