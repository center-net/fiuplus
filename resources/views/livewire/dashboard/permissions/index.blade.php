<div>
    @can('viewAny', \App\Models\Permission::class)
    <div class="card">
        <div class="card-header">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3>{{ __('app.permissions_manage_title') }}</h3>
                </div>
                @can('create', \App\Models\Permission::class)
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permissionFormModal"
                        wire:click="$dispatch('openPermissionFormModal')">
                        <i class="fas fa-plus ms-1"></i> {{ __('app.add_new_permission') }}
                    </button>
                </div>
                @endcan
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">{{ __('app.search') }}</label>
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="{{ __('app.search') }} / {{ __('app.key_label') }} / {{ __('app.table') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">{{ __('app.page') }}</label>
                    <select wire:model.change="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th wire:click="sort('table_name')" style="cursor: pointer;">{{ __('app.table') }}
                                @if ($sortBy === 'table_name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sort('name')" style="cursor: pointer;">{{ __('app.name') }}
                                @if ($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sort('key')" style="cursor: pointer;">{{ __('app.key_label') }}
                                @if ($sortBy === 'key')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th class="text-center">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $p)
                            <tr wire:key="permission-{{ $p->id }}">
                                <td>{{ $p->table_name }}</td>
                                <td>{{ $p->name }}</td>
                                <td><code>{{ $p->key }}</code></td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @can('update', $p)
                                        <button class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:click="$dispatch('openPermissionFormModal', { permissionId: {{ $p->id }} })"
                                            data-bs-toggle="modal" data-bs-target="#permissionFormModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan
                                        @can('delete', $p)
                                        <button class="btn btn-danger" wire:loading.attr="disabled"
                                            wire:click="delete({{ $p->id }})"
                                            onclick="return confirm('{{ __('app.confirm_delete_permission') }}');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">{{ __('app.no_results') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                {{ __('app.showing_x_to_y_of_z', ['from' => $permissions->firstItem(), 'to' => $permissions->lastItem(), 'total' => $permissions->total()]) }}
            </div>
            <div>
                {{ $permissions->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger">{{ __('app.unauthorized') }}</div>
    @endcan


    {{-- Permission Form Modal --}}
    
        <livewire:dashboard.permissions.form />
    

    <div wire:loading wire:target="search,perPage,sortBy" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">{{ __('app.updating') }}</span>
    </div>
</div>

@script
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('closeModal', () => {
            const modalElement = document.getElementById('permissionFormModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        });
    });
</script>
@endscript