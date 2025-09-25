<?php

namespace App\Http\Livewire\Dashboard\Stores;

use App\Models\Store;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Form extends Component
{
    use AuthorizesRequests;

    // Form state
    public $store_id;
    public $name;
    public $user_id;
    public $email;
    public $phone;

    // View data
    public $users = [];

    protected $listeners = [
        'openStoreFormModal' => 'loadStore',
    ];

    public function mount(): void
    {
        // Base authorization (view list); specific actions are checked in save()
        $this->authorize('viewAny', Store::class);

        $locale = app()->getLocale();
        $this->users = User::query()
            ->leftJoin('user_translations as ut', function ($join) use ($locale) {
                $join->on('ut.user_id', '=', 'users.id')
                     ->where('ut.locale', '=', $locale);
            })
            ->select('users.id', 'users.username', 'ut.name as name')
            ->orderByRaw('CASE WHEN ut.name IS NULL THEN 0 ELSE 1 END DESC')
            ->orderBy('ut.name')
            ->get();

        $this->resetForm();
    }

    public function loadStore($storeId = null): void
    {
        // Accept both scalar ID and [ID] payloads from event dispatch
        if (is_array($storeId)) {
            $storeId = $storeId[0] ?? null;
        }

        $this->resetForm();
        $this->store_id = $storeId;

        if ($storeId) {
            $store = Store::with('user')->findOrFail($storeId);
            $this->authorize('update', $store);

            $this->name = $store->name; // translatable accessor
            $this->user_id = $store->user_id;
            $this->email = $store->email;
            $this->phone = $store->phone;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            // Ensure a user can have only one store
            'user_id' => [
                'required',
                Rule::exists('users', 'id'),
                Rule::unique('stores', 'user_id')->ignore($this->store_id),
            ],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $locale = app()->getLocale();

        // name is translatable; remove from base payload
        $name = $validated['name'];
        unset($validated['name']);

        // Enforce unique store per user (app-level validation to show friendly error)
        $existsForUser = Store::where('user_id', $validated['user_id'])
            ->when($this->store_id, fn($q) => $q->where('id', '!=', $this->store_id))
            ->exists();
        if ($existsForUser) {
            $this->addError('user_id', __('app.user_already_has_store'));
            return;
        }

        if ($this->store_id) {
            $store = Store::findOrFail($this->store_id);
            $this->authorize('update', $store);

            // Auto-generate slug from name if not exists or if name changed
            $slug = $store->slug;
            if (blank($slug) || $store->getTranslation('name', $locale) !== $name) {
                $slug = $this->makeUniqueSlug($name, $this->store_id);
            }

            $store->update($validated + ['slug' => $slug]);
            $store->translateOrNew($locale)->name = $name;
            $store->save();

            $this->dispatch('show-toast', message: 'تم تحديث المتجر بنجاح.');
        } else {
            $this->authorize('create', Store::class);

            // Auto-generate slug from name
            $slug = $this->makeUniqueSlug($name);

            $store = Store::create($validated + [
                'is_active' => true,
                'slug' => $slug,
            ]);
            $store->translateOrNew($locale)->name = $name;
            $store->save();

            $this->dispatch('show-toast', message: 'تم إضافة المتجر بنجاح.');
        }

        $this->dispatch('storeSaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->reset('store_id', 'name', 'user_id', 'email', 'phone');
        $this->resetValidation();
    }

    public function render()
    {
        // Pass users to the view for the owner select
        return view('livewire.dashboard.stores.form', [
            'users' => $this->users,
        ]);
    }

    // Generate a URL-friendly unique slug from name
    protected function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        $query = Store::query();
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        while ($query->clone()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }
        return $slug;
    }
}