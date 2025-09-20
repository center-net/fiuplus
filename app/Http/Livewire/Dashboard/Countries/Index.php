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

        $countries = Country::query()
            ->withCount(['cities', 'villages'])
            ->when($this->search, function ($q) {
                $q->search($this->search);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

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