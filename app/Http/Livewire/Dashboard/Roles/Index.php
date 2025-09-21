<?php

namespace App\Http\Livewire\Dashboard\Roles;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'roleSaved' => '$refresh',
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
        $this->authorize('viewAny', Role::class);

        $locale = app()->getLocale();
        $sortBy = $this->sortBy;
        $sortDirection = $this->sortDirection;

        $roles = Role::query()
            ->with(['permissions'])
            ->when($this->search, function ($q) use ($locale) {
                $q->where(function ($qq) use ($locale) {
                    $qq->orWhere('key', 'like', "%{$this->search}%")
                       ->orWhereHas('translations', function ($t) use ($locale) {
                           $t->where('locale', $locale)
                             ->where('name', 'like', "%{$this->search}%");
                       });
                });
            })
            ->when($sortBy === 'name', function ($q) use ($locale, $sortDirection) {
                $q->leftJoin('role_translations as rt', function ($join) use ($locale) {
                    $join->on('rt.role_id', '=', 'roles.id')
                        ->where('rt.locale', '=', $locale);
                })
                ->select('roles.*')
                ->orderByRaw('CASE WHEN rt.name IS NULL THEN 0 ELSE 1 END')
                ->orderBy('rt.name', $sortDirection);
            }, function ($q) use ($sortBy, $sortDirection) {
                $q->orderBy($sortBy, $sortDirection);
            })
            ->withCount('users')
            ->paginate($this->perPage);

        return view('livewire.dashboard.roles.index', compact('roles'))
            ->layout('layouts.app', ['title' => 'الأدوار']);
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

    public function delete($roleId)
    {
        $role = Role::findOrFail($roleId);
        $this->authorize('delete', $role);

        if ($role->users()->exists()) {
            $this->dispatch('show-toast', message: 'لا يمكن حذف الدور المرتبط بمستخدمين.');
            return;
        }

        $role->permissions()->detach();
        $role->delete();
        $this->dispatch('show-toast', message: 'تم حذف الدور بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}