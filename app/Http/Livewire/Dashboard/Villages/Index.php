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

        $locale = app()->getLocale();

        $query = Village::query()
            ->with(['city.country'])
            ->when($this->cityId, fn($q) => $q->byCity($this->cityId))
            ->when(!$this->cityId && $this->countryId, function ($q) {
                $q->whereHas('city', fn($qq) => $qq->where('country_id', $this->countryId));
            })
            ->when($this->search, function ($q) {
                $q->search($this->search);
            });

        if ($this->sortBy === 'name') {
            $query->leftJoin('village_translations as vt', function ($join) use ($locale) {
                    $join->on('vt.village_id', '=', 'villages.id')
                         ->where('vt.locale', '=', $locale);
                })
                ->select('villages.*')
                ->orderByRaw('CASE WHEN vt.name IS NULL THEN 0 ELSE 1 END')
                ->orderBy('vt.name', $this->sortDirection);
        } else {
            $query->orderBy($this->sortBy, $this->sortDirection);
        }

        $villages = $query->paginate($this->perPage);

        $countries = Country::query()
            ->leftJoin('country_translations as cnt', function ($join) use ($locale) {
                $join->on('cnt.country_id', '=', 'countries.id')
                     ->where('cnt.locale', '=', $locale);
            })
            ->select('countries.id', 'cnt.name as name')
            ->orderByRaw('CASE WHEN cnt.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('cnt.name')
            ->get();

        $cities = $this->countryId ? City::query()
            ->byCountry($this->countryId)
            ->leftJoin('city_translations as ct', function ($join) use ($locale) {
                $join->on('ct.city_id', '=', 'cities.id')
                     ->where('ct.locale', '=', $locale);
            })
            ->select('cities.id', 'ct.name as name')
            ->orderByRaw('CASE WHEN ct.name IS NULL THEN 0 ELSE 1 END')
            ->orderBy('ct.name')
            ->get() : collect();

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