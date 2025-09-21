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
    public string $sortBy = 'key';
    public string $sortDirection = 'asc';
    public int $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'key'],
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
                    $qq->where('key', 'like', "%{$this->search}%")
                       ->orWhereHas('translations', function ($tt) {
                           $tt->where('name', 'like', "%{$this->search}%")
                              ->orWhere('table_name', 'like', "%{$this->search}%");
                       });
                });
            })
            ->when(in_array($this->sortBy, ['key']), function ($q) {
                $q->orderBy($this->sortBy, $this->sortDirection);
            }, function ($q) {
                // فرز حسب الترجمة: الجدول ثم الاسم
                $q->with('translations')
                  ->orderBy(Permission::select('permission_translations.table_name')
                    ->whereColumn('permission_translations.permission_id', 'permissions.id')
                    ->where('permission_translations.locale', app()->getLocale())
                    ->limit(1), $this->sortDirection)
                  ->orderBy(Permission::select('permission_translations.name')
                    ->whereColumn('permission_translations.permission_id', 'permissions.id')
                    ->where('permission_translations.locale', app()->getLocale())
                    ->limit(1), $this->sortDirection);
            })
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