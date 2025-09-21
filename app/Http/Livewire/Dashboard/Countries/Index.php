<?php

namespace App\Http\Livewire\Dashboard\Countries;

use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'countrySaved' => '$refresh',
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
        $this->authorize('viewAny', Country::class);

        $locale = app()->getLocale();

        $query = Country::query()
            ->when($this->search, function ($q) {
                $q->search($this->search);
            });

        if ($this->sortBy === 'name') {
            // Translation-aware ordering by country name (null-first), then add counts AFTER select to preserve them
            $query->leftJoin('country_translations as ct', function ($join) use ($locale) {
                $join->on('ct.country_id', '=', 'countries.id')
                     ->where('ct.locale', '=', $locale);
            })
            ->select('countries.*')
            ->withCount(['cities', 'villages'])
            ->orderByRaw('CASE WHEN ct.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('ct.name', $this->sortDirection);
        } else {
            // When sorting by counts or base fields, ensure counts exist BEFORE ordering by them
            $query->withCount(['cities', 'villages'])
                  ->orderBy($this->sortBy, $this->sortDirection);
        }

        $countries = $query->paginate($this->perPage);

        return view('livewire.dashboard.countries.index', compact('countries'))
            ->layout('layouts.app', ['title' => 'الدول']);
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

    public function delete($countryId)
    {
        $country = Country::findOrFail($countryId);
        $this->authorize('delete', $country);

        if ($country->cities()->exists()) {
            $this->dispatch('show-toast', message: 'لا يمكن حذف الدولة المرتبطة بمدن.');
            return;
        }

        $country->delete();
        $this->dispatch('show-toast', message: 'تم حذف الدولة بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}