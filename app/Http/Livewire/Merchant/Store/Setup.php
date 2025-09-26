<?php

namespace App\Http\Livewire\Merchant\Store;

use Livewire\Component;
use App\Models\Store;
use App\Models\StoreCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class Setup extends Component
{
    use AuthorizesRequests;

    public $name;
    public $email;
    public $phone;
    public $category_id;

    public $categories = [];

    public function mount(): void
    {
        $user = auth()->user();
        $user->loadMissing(['roles', 'store']);

        // Prevent non-merchants from accessing or creating a store via this setup flow
        if (!$user->roles->contains('key', 'merchant')) {
            abort(403, __('app.non_merchant_forbidden'));
        }

        $store = $user->store;
        if (!$store) {
            $store = $user->store()->create([
                'slug' => $user->username ?: (string)$user->id,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_active' => false,
            ]);
        }

        // Authorization: only the merchant owner (or admins with edit permission) can access setup
        $this->authorize('setup', $store);

        $this->name = $store->name;
        $this->email = $store->email;
        $this->phone = $store->phone;
        $this->category_id = $store->category_id;

        $this->categories = StoreCategory::withTranslation(app()->getLocale())
            ->orderByTranslation('name')
            ->get();
    }

    protected function rules(): array
    {
        $locale = app()->getLocale();
        $storeId = optional(auth()->user()->store)->id;
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('store_translations', 'name')->where(function ($q) use ($locale, $storeId) {
                    $q->where('locale', $locale);
                    if ($storeId) {
                        $q->where('store_id', '!=', $storeId);
                    }
                }),
            ],
            'email' => ['required', 'email', 'max:255', Rule::unique('stores', 'email')->ignore($storeId)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('stores', 'phone')->ignore($storeId)],
            'category_id' => ['nullable', Rule::exists('store_categories', 'id')],
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();
        $locale = app()->getLocale();
        $user = auth()->user();
        $store = $user->store;

        $slug = $store->slug;
        if (blank($slug) || $store->getTranslation('name', $locale) !== $validated['name']) {
            $base = Str::slug($validated['name']);
            $slug = $base;
            $i = 2;
            $query = Store::where('id', '!=', $store->id);
            while ($query->clone()->where('slug', $slug)->exists()) {
                $slug = $base.'-'.$i;
                $i++;
            }
        }

        $store->update([
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'slug' => $slug,
            'category_id' => $this->category_id,
            'is_active' => false, // remain inactive until admin approves (or until a later rule changes it)
        ]);
        $store->translateOrNew($locale)->name = $validated['name'];
        $store->save();

        $this->dispatch('show-toast', message: __('app.saved_successfully'));
    }

    public function activateAndFinish()
    {
        // Save first
        $this->save();

        // Auto-activate after completion
        $store = auth()->user()->store;
        $this->authorize('update', $store);
        $store->update(['is_active' => true]);

        // Optionally flash success
        $this->dispatch('show-toast', message: __('app.saved_successfully'));

        // Redirect to dashboard (or merchant home if exists)
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.merchant.store.setup')
            ->layout('layouts.app', ['title' => __('app.store_categories_manage_title')]);
    }
}