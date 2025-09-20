<?php

namespace App\Http\Livewire\Dashboard\Cities;

use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'citySaved' => '$refresh',
        'closeModal' => 'closeBootstrapModal',
    ];

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;
    public $countryId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'countryId' => ['except' => ''],
    ];

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->authorize('viewAny', City::class);

        $cities = City::query()
            ->with(['country'])
            ->withCount(['villages'])
            ->when($this->countryId, fn($q) => $q->byCountry($this->countryId))
            ->when($this->search, function ($q) {
                $q->search($this->search);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $countries = Country::orderBy('name')->get(['id','name']);

        return view('livewire.dashboard.cities.index', compact('cities','countries'))
            ->layout('layouts.app', ['title' => 'المدن']);
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
    public function updatedCountryId(): void { $this->resetPage(); }

    public function delete($cityId)
    {
        $city = City::findOrFail($cityId);
        $this->authorize('delete', $city);

        if ($city->villages()->exists()) {
            $this->dispatch('show-toast', message: 'لا يمكن حذف المدينة المرتبطة بقرى.');
            return;
        }

        $city->delete();
        $this->dispatch('show-toast', message: 'تم حذف المدينة بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}