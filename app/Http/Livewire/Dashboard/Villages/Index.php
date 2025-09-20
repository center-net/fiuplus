<?php

namespace App\Http\Livewire\Dashboard\Villages;

use App\Models\Village;
use App\Models\City;
use App\Models\Country;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'villageSaved' => '$refresh',
        'closeModal' => 'closeBootstrapModal',
    ];

    public string $search = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;
    public $countryId = '';
    public $cityId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
        'countryId' => ['except' => ''],
        'cityId' => ['except' => ''],
    ];

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->authorize('viewAny', Village::class);

        $villages = Village::query()
            ->with(['city.country'])
            ->when($this->cityId, fn($q) => $q->byCity($this->cityId))
            ->when(!$this->cityId && $this->countryId, function ($q) {
                $q->whereHas('city', fn($qq) => $qq->where('country_id', $this->countryId));
            })
            ->when($this->search, function ($q) {
                $q->search($this->search);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $countries = Country::orderBy('name')->get(['id','name']);
        $cities = $this->countryId ? City::byCountry($this->countryId)->orderBy('name')->get(['id','name']) : collect();

        return view('livewire.dashboard.villages.index', compact('villages','countries','cities'))
            ->layout('layouts.app', ['title' => 'القرى']);
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
    public function updatedCountryId(): void { $this->cityId = ''; $this->resetPage(); }
    public function updatedCityId(): void { $this->resetPage(); }

    public function delete($villageId)
    {
        $village = Village::findOrFail($villageId);
        $this->authorize('delete', $village);
        $village->delete();
        $this->dispatch('show-toast', message: 'تم حذف القرية بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}