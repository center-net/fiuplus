<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\City;
use App\Models\Country;
use App\Models\Role;
use App\Models\User;
use App\Models\Village;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

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

    public $countries = [];
    public $cities = [];
    public $villages = [];
    public $roles = [];

    protected $listeners = ['openUserFormModal' => 'loadUser'];

    public function mount()
    {
        $this->countries = Country::all();
        $this->roles = Role::all();

        $defaultRole = Role::where('name', 'user')->first();
        if ($defaultRole) {
            $this->defaultRoleId = $defaultRole->id;
            $this->selectedRoles = [$this->defaultRoleId];
        }

        $this->resetForm();
    }

    public function updatedCountryId($value)
    {
        $this->cities = City::where('country_id', $value)->get();
        $this->city_id = null;
        $this->villages = [];
        $this->village_id = null;
    }

    public function updatedCityId($value)
    {
        $this->villages = Village::where('city_id', $value)->get();
        $this->village_id = null;
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

            if ($this->country_id) {
                $this->cities = City::where('country_id', $this->country_id)->get();
            }
            if ($this->city_id) {
                $this->villages = Village::where('city_id', $this->city_id)->get();
            }
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
            'avatar' => 'nullable|image|max:1024', // Max 1MB
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

        // Handle file upload
        if ($this->avatar && !is_string($this->avatar)) {
            $validatedData['avatar'] = $this->avatar->store('avatars', 'public');
        }

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            
            if ($this->password) {
                $validatedData['password'] = Hash::make($this->password);
            } else {
                unset($validatedData['password']); // Don't update password if it's empty
            }

            $user->update($validatedData);
            $user->roles()->sync($this->selectedRoles);

            $this->dispatch('show-toast', message: 'تم تحديث المستخدم بنجاح.');
        } else {
            // Ensure there's a role for new users
            if (empty($this->selectedRoles)) {
                $this->selectedRoles = [$this->defaultRoleId];
            }
            
            $validatedData['password'] = Hash::make($this->password);

            $user = User::create($validatedData);
            $user->roles()->sync($this->selectedRoles);

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

        $this->cities = [];
        $this->villages = [];
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.users.form');
    }
}
