<?php

namespace App\Http\Livewire\Dashboard\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Permissions extends Component
{
    use AuthorizesRequests;

    public $userId;
    public $user;
    /** @var \Illuminate\Support\Collection */
    public $permissions;
    public $selectedPermissions = [];
    // key: permission_id, value: none|allow|deny
    public array $permEffects = [];

    protected $listeners = ['openUserPermissionsModal' => 'load'];

    public function mount()
    {
        // Order by translation: general (null) first, then by table_name and name (current locale)
        $locale = app()->getLocale();
        $this->permissions = Permission::query()
            ->with('translations')
            ->leftJoin('permission_translations as pt', function ($join) use ($locale) {
                $join->on('pt.permission_id', '=', 'permissions.id')
                    ->where('pt.locale', '=', $locale);
            })
            ->select('permissions.*')
            // Ensure null table_name (general) first, then by table_name and name
            ->orderByRaw('CASE WHEN pt.table_name IS NULL THEN 0 ELSE 1 END ASC')
            ->orderBy('pt.table_name')
            ->orderBy('pt.name')
            ->get();
    }

    public function load($userId)
    {
        $this->resetErrorBag();
        $this->userId = $userId;
        $this->user = User::with('permissions')->findOrFail($userId);
        $this->authorize('update', $this->user);

        // fill effects from pivot
        $this->permEffects = [];
        foreach ($this->user->permissions as $perm) {
            $this->permEffects[$perm->id] = $perm->pivot->effect ?? 'allow';
        }
    }

    public function save()
    {
        $user = User::findOrFail($this->userId);
        $this->authorize('update', $user);

        // Build sync payload with effects
        $syncPayload = [];
        foreach ($this->permEffects as $permId => $effect) {
            if ($effect === 'allow' || $effect === 'deny') {
                $syncPayload[(int)$permId] = ['effect' => $effect];
            }
        }

        $user->permissions()->sync($syncPayload);
        $user->clearPermissionsCache();

        $this->dispatch('show-toast', message: 'تم تحديث صلاحيات المستخدم.');
        $this->dispatch('userSaved');
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.dashboard.users.permissions');
    }
}