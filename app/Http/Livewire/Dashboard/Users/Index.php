<?php

namespace App\Http\Livewire\Dashboard\Users;

use App\Models\User;
use App\Traits\HasLocationLogic;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests, HasLocationLogic;

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

        // Load location data if not loaded
        if (empty($this->countries)) {
            $this->loadCountries();
        }
        $this->loadCities($this->country_id);
        $this->loadVillages($this->city_id);

        $sortBy = $this->sortBy;
        $sortDirection = $this->sortDirection;
        $locale = app()->getLocale();

        $users = User::query()
            ->with(['country', 'city', 'village', 'roles'])
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->country_id, fn($q) => $q->where('country_id', $this->country_id))
            ->when($this->city_id, fn($q) => $q->where('city_id', $this->city_id))
            ->when($this->village_id, fn($q) => $q->where('village_id', $this->village_id))
            ->when($sortBy === 'name', function ($q) use ($locale, $sortDirection) {
                $q->leftJoin('user_translations as ut', function ($join) use ($locale) {
                    $join->on('ut.user_id', '=', 'users.id')
                        ->where('ut.locale', '=', $locale);
                })
                ->select('users.*')
                ->orderBy('ut.name', $sortDirection);
            }, function ($q) use ($sortBy, $sortDirection) {
                $q->orderBy($sortBy, $sortDirection);
            })
            ->paginate($this->perPage);

        return view('livewire.dashboard.users.index', compact('users'))
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
            $this->dispatch('show-toast', message: 'لا يمكنك حذف حسابك.');
            return;
        }

        $user->delete();
        $this->dispatch('show-toast', message: 'تم حذف المستخدم بنجاح.');
    }

    // Method to close the Bootstrap modal using JavaScript
    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent'); // Dispatch a browser event to close the modal
    }
}
