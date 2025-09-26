<?php

namespace App\Http\Livewire\Dashboard\StoreCategories;

use App\Models\StoreCategory;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class Form extends Component
{
    use AuthorizesRequests;

    public $category_id;
    public $name = '';
    public $description = '';
    public $slug = '';

    public $slug_touched = false;

    protected $listeners = ['openStoreCategoryFormModal' => 'loadCategory'];

    public function mount(): void
    {
        $this->authorize('viewAny', StoreCategory::class);
        $this->resetForm();
    }

    public function loadCategory($payload = null): void
    {
        // Accept either scalar id or array payload from blade dispatch
        $categoryId = is_array($payload) ? ($payload[0] ?? null) : $payload;

        $this->resetForm();
        $this->category_id = $categoryId;

        if ($categoryId) {
            $cat = StoreCategory::findOrFail($categoryId);
            $this->authorize('update', $cat);
            $this->name = $cat->name; // translatable accessor
            $this->description = $cat->description;
            $this->slug = $cat->slug;
        } else {
            $this->authorize('create', StoreCategory::class);
        }
    }

    protected function rules(): array
    {
        $locale = app()->getLocale();

        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('store_category_translations', 'name')
                    ->ignore($this->category_id, 'store_category_id')
                    ->where(fn($q) => $q->where('locale', $locale)),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
            'slug' => [
                'required', 'string', 'max:255',
                Rule::unique('store_categories', 'slug')->ignore($this->category_id),
            ],
        ];
    }

    private function generateSlug(string $source): string
    {
        $slug = Str::slug($source, '-', 'ar');
        if ($slug === '') {
            $slug = preg_replace('/[^\p{Arabic}\p{L}\p{N}]+/u', '-', $source) ?? '';
            $slug = trim($slug, '-');
            $slug = mb_strtolower($slug, 'UTF-8');
        }
        return $slug;
    }

    public function updatedName(): void
    {
        if (!$this->slug_touched) {
            $this->slug = $this->generateSlug($this->name);
        }
    }

    public function updatedSlug(): void
    {
        $this->slug_touched = true;
        $this->slug = $this->generateSlug($this->slug);
    }

    public function save(): void
    {
        $data = $this->validate();
        $locale = app()->getLocale();

        if ($this->category_id) {
            $cat = StoreCategory::findOrFail($this->category_id);
            $this->authorize('update', $cat);
            $cat->update(['slug' => $data['slug']]);
            $cat->translateOrNew($locale)->name = $data['name'];
            $cat->translateOrNew($locale)->description = $data['description'];
            $cat->save();
            $this->dispatch('show-toast', message: 'تم تحديث القسم بنجاح.');
        } else {
            $this->authorize('create', StoreCategory::class);
            $cat = StoreCategory::create(['slug' => $data['slug']]);
            $cat->translateOrNew($locale)->name = $data['name'];
            $cat->translateOrNew($locale)->description = $data['description'];
            $cat->save();
            $this->dispatch('show-toast', message: 'تم إضافة القسم بنجاح.');
        }

        $this->dispatch('categorySaved');
        $this->dispatch('closeModal');
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->category_id = null;
        $this->name = '';
        $this->description = '';
        $this->slug = '';
        $this->slug_touched = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.dashboard.store-categories.form');
    }
}