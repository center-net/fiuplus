<?php

namespace App\Http\Livewire\Dashboard\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $listeners = [
        'permissionSaved' => '$refresh',
        'closeModal' => 'closeBootstrapModal',
    ];

    public string $search = '';
    public string $sortBy = 'table_name';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'table_name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    protected string $paginationTheme = 'bootstrap';

    public function render()
    {
        $this->authorize('viewAny', Permission::class);

        $permissions = Permission::query()
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', "%{$this->search}%")
                       ->orWhere('key', 'like', "%{$this->search}%")
                       ->orWhere('table_name', 'like', "%{$this->search}%");
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.dashboard.permissions.index', compact('permissions'))
            ->layout('layouts.app', ['title' => 'الصلاحيات']);
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

    public function delete($permissionId)
    {
        $permission = Permission::findOrFail($permissionId);
        $this->authorize('delete', $permission);

        // منع حذف صلاحيات نظامية إن رغبت مستقبلاً
        $permission->delete();
        $this->dispatch('show-toast', message: 'تم حذف الصلاحية بنجاح.');
    }

    public function closeBootstrapModal()
    {
        $this->dispatch('closeModalEvent');
    }
}