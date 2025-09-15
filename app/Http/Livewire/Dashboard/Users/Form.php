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

class Form extends Component
{
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

        // تعيين دور افتراضي للمستخدمين الجدد
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

            // Load cities and villages based on user's country and city
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
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users')->ignore($this->user_id),
            ],
            'password' => $this->user_id ? 'nullable|string|min:8' : 'required|string|min:8',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
            'village_id' => 'nullable|exists:villages,id',
            'selectedRoles' => 'required|array|min:1',
            'selectedRoles.*' => 'exists:roles,id',
        ];
    }

    public function save()
    {
        $this->validate();

        if ($this->user_id) {
            $user = User::findOrFail($this->user_id);
            $user->update([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->avatar,
                'country_id' => $this->country_id,
                'city_id' => $this->city_id,
                'village_id' => $this->village_id,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            $user->roles()->sync($this->selectedRoles);

            session()->flash('message', 'تم تحديث المستخدم بنجاح.');
        } else {
            // التأكد من وجود دور مختار
            if (empty($this->selectedRoles) || !is_array($this->selectedRoles)) {
                $this->selectedRoles = [$this->defaultRoleId];
            }

            $user = User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email,
                'phone' => $this->phone,
                'avatar' => $this->avatar,
                'password' => Hash::make($this->password),
                'country_id' => $this->country_id,
                'city_id' => $this->city_id,
                'village_id' => $this->village_id,
            ]);

            $user->roles()->sync($this->selectedRoles);

            session()->flash('message', 'تم اضافة المستخدم بنجاح.');
        }

        $this->dispatch('userSaved'); // Event to notify index component to refresh
        $this->dispatch('closeModal'); // Event to close the modal
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
        $this->avatar = '';
        $this->country_id = null;
        $this->city_id = null;
        $this->village_id = null;

        // إعادة تعيين الأدوار إلى الافتراضي للمستخدمين الجدد
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