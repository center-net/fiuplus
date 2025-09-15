<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\Village;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'userSaved' => '$refresh',
        'closeModal' => 'closeBootstrapModal',
    ];

    // فلاتر وخصائص الواجهة
    public string $search = '';
    public $country_id = '';
    public $city_id = '';
    public $village_id = '';
    public string $sortBy = 'name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    // ربط القيم مع شريط العنوان لسهولة المشاركة والحفظ
    protected $queryString = [
        'search' => ['except' => ''],
        'country_id' => ['except' => ''],
        'city_id' => ['except' => ''],
        'village_id' => ['except' => ''],
        'sortBy' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    // استخدام ثيم Bootstrap للصفحات
    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->with(['country', 'city', 'village', 'roles'])
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->country_id, fn($q) => $q->where('country_id', $this->country_id))
            ->when($this->city_id, fn($q) => $q->where('city_id', $this->city_id))
            ->when($this->village_id, fn($q) => $q->where('village_id', $this->village_id))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $countries = Country::orderBy('name')->get();
        $cities = $this->country_id ? City::where('country_id', $this->country_id)->orderBy('name')->get() : collect();
        $villages = $this->city_id ? Village::where('city_id', $this->city_id)->orderBy('name')->get() : collect();

        return view('livewire.dashboard.users.index', compact('users', 'countries', 'cities', 'villages'))
            ->layout('layouts.app', ['title' => 'المستخدمون']);
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
    public function updatedCountryId(): void { $this->city_id = ''; $this->village_id = ''; $this->resetPage(); }
    public function updatedCityId(): void { $this->village_id = ''; $this->resetPage(); }
    public function updatedVillageId(): void { $this->resetPage(); }
    public function updatedPerPage(): void { $this->resetPage(); }

    public function resetFilters(): void
    {
        $this->reset(['search', 'country_id', 'city_id', 'village_id']);
        $this->sortBy = 'name';
        $this->sortDirection = 'asc';
        $this->perPage = 10;
        $this->resetPage();
    }

    public function delete($userId)
    {
        $user = User::findOrFail($userId);
        $this->authorize('delete', $user);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'لا يمكنك حذف حسابك.');
            return;
        }

        $user->delete();
        session()->flash('message', 'تم حذف المستخدم بنجاح.');
    }

    // Method to close the Bootstrap modal using JavaScript
    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent'); // Dispatch a browser event to close the modal
    }
}
