<?php

namespace App\Http\Livewire\Dashboard\StoreCategories;

use App\Models\StoreCategory;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'categorySaved' => '$refresh',
        'closeModal' => 'closeBootstrapModal',
    ];

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->authorize('viewAny', StoreCategory::class);

        $locale = app()->getLocale();

        $query = StoreCategory::query()
            ->when($this->search, function ($q) use ($locale) {
                $q->whereHas('translations', function ($t) use ($locale) {
                    $t->where('locale', $locale)
                      ->where(function ($tt) {
                          $term = '%'.trim($this->search).'%';
                          $tt->where('name', 'LIKE', $term)
                             ->orWhere('description', 'LIKE', $term);
                      });
                })
                ->orWhere('slug', 'LIKE', '%'.trim($this->search).'%');
            });

        if ($this->sortBy === 'name') {
            $query->leftJoin('store_category_translations as sct', function ($join) use ($locale) {
                $join->on('sct.store_category_id', '=', 'store_categories.id')
                     ->where('sct.locale', '=', $locale);
            })
            ->select('store_categories.*')
            ->orderByRaw('CASE WHEN sct.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('sct.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $categories = $query->paginate($this->perPage);

        return view('livewire.dashboard.store-categories.index', compact('categories'))
            ->layout('layouts.app', ['title' => __('app.store_categories_manage_title')]);
    }

    public function sort(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function delete($categoryId)
    {
        $cat = StoreCategory::findOrFail($categoryId);
        $this->authorize('delete', $cat);
        $cat->delete();
        $this->dispatch('show-toast', message: 'تم حذف القسم بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}