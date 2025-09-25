<?php

namespace App\Http\Livewire\Dashboard\Stores;

use App\Models\Store;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'storeSaved' => '$refresh',
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
        $this->authorize('viewAny', Store::class);

        $locale = app()->getLocale();

        $query = Store::query()
            ->with(['user'])
            ->when($this->search, function ($q) {
                $q->search($this->search);
            });

        if ($this->sortBy === 'name') {
            $query->leftJoin('store_translations as st', function ($join) use ($locale) {
                $join->on('st.store_id', '=', 'stores.id')
                     ->where('st.locale', '=', $locale);
            })
            ->select('stores.*')
            ->orderByRaw('CASE WHEN st.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('st.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $stores = $query->paginate($this->perPage);

        return view('livewire.dashboard.stores.index', compact('stores'))
            ->layout('layouts.app', ['title' => __('app.stores_manage_title')]);
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

    public function delete($storeId)
    {
        $store = Store::findOrFail($storeId);
        $this->authorize('delete', $store);
        $store->delete();
        $this->dispatch('show-toast', message: 'تم حذف المتجر بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}