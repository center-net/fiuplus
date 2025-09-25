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
        $locale = app()->getLocale();
        $this->roles = Role::query()
            ->leftJoin('role_translations as rt', function ($join) use ($locale) {
                $join->on('rt.role_id', '=', 'roles.id')
                    ->where('rt.locale', '=', $locale);
            })
            ->select('roles.id', 'roles.key', 'roles.color', 'rt.name as name')
            ->get();

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
        $locale = app()->getLocale();

        // Handle file upload (store first to set path)
        if ($this->avatar && !is_string($this->avatar)) {
            $validatedData['avatar'] = $this->avatar->store('users/avatars', 'public');
        }

        // 'name' is translatable; remove from base payload and save via translations API
        unset($validatedData['name']);

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
            // Save translated name
            $user->translateOrNew($locale)->name = $this->name;
            $user->save();

            $user->roles()->sync($this->selectedRoles);
            $user->clearPermissionsCache();

            // Handle merchant store activation/deactivation on role change
            $isMerchant = Role::where('key', 'merchant')->whereIn('id', $this->selectedRoles)->exists();
            if ($isMerchant) {
                // Ensure user has a store and it's active
                $store = $user->store()->first();
                if (!$store) {
                    // Auto-create store directly (no modal)
                    $store = \App\Models\Store::create([
                        'slug' => \Illuminate\Support\Str::slug($user->username ?: $user->name ?: (string)$user->id, '-', 'ar'),
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'is_active' => true,
                    ]);
                    $store->translateOrNew($locale)->name = $this->name;
                    $store->save();
                    $this->dispatch('show-toast', message: 'تم إنشاء المتجر بنجاح.');
                } else {
                    $store->update(['is_active' => true]);
                }
            } else {
                // If user had a store, deactivate it
                $store = $user->store()->first();
                if ($store) {
                    $store->update(['is_active' => false]);
                }
            }

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
            // Save translated name
            $user->translateOrNew($locale)->name = $this->name;
            $user->save();

            $user->roles()->sync($this->selectedRoles);
            $user->clearPermissionsCache();

            // If merchant is selected during creation, auto-create store (no modal)
            $isMerchant = Role::where('key', 'merchant')->whereIn('id', $this->selectedRoles)->exists();
            if ($isMerchant) {
                // Create store directly with defaults
                \App\Models\Store::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'slug' => \Illuminate\Support\Str::slug($user->username ?: $user->name ?: (string)$user->id, '-', 'ar'),
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'is_active' => true,
                    ]
                )->translateOrNew($locale)->name = $user->name;
                $store = $user->store; if ($store) { $store->save(); $this->dispatch('show-toast', message: 'تم إنشاء المتجر بنجاح.'); }
            }

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
