<div>
    @can('viewAny', \App\Models\User::class)
    <div class="card">
        <div class="card-header">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3>{{ __('app.users_manage_title') }}</h3>
                </div>
                @can('create', \App\Models\User::class)
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userFormModal"
                        wire:click="$dispatch('openUserFormModal')">
                        <i class="fas fa-plus ms-1"></i> {{ __('app.add_new_user') }}
                    </button>
                </div>
                @endcan
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">{{ __('app.search') }}</label>
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                        placeholder="{{ __('app.search') }}">
                </div>
                @include('layouts.partials._location_dropdowns', ['modelPrefix' => '', 'colSize' => 'col-md-2'])
                <div class="col-md-1">
                    <label class="form-label">{{ __('app.page') }}</label>
                    <select wire:model.change="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-rotate-left ms-1"></i> {{ __('app.reset_filters') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th wire:click="sort('name')" style="cursor: pointer;">
                                {{ __('app.name') }}
                                @if ($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sort('email')" style="cursor: pointer;">
                                {{ __('app.email') }}
                                @if ($sortBy === 'email')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th>{{ __('app.country') }}</th>
                            <th>{{ __('app.city') }}</th>
                            <th>{{ __('app.village') }}</th>
                            <th class="text-center">{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td class="fw-semibold">
                                    {{ $user->name }}
                                    <div class="d-flex gap-1 mt-1">
                                    @foreach($user->roles as $role)
                                        <span class="badge {{ $role->color ?? 'bg-secondary' }}">{{ $role->name }}</span>
                                    @endforeach
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $user->email }}</div>
                                    <div class="text-muted small">{{ $user->username }}</div>
                                </td>
                                <td>{{ $user->country->name ?? '-' }}</td>
                                <td>{{ $user->city->name ?? '-' }}</td>
                                <td>{{ $user->village->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @can('update', $user)
                                        <button class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:click="$dispatch('openUserFormModal', [{{ $user->id }}])"
                                            data-bs-toggle="modal" data-bs-target="#userFormModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan
                                        @can('delete', $user)
                                        <button class="btn btn-danger" wire:loading.attr="disabled"
                                            wire:click="delete({{ $user->id }})"
                                            onclick="return confirm('{{ __('app.confirm_delete_user') }}');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                        @can('addPermission', $user)
                                        <button class="btn btn-secondary" wire:loading.attr="disabled"
                                            wire:click="$dispatch('openUserPermissionsModal', [{{ $user->id }}])"
                                            data-bs-toggle="modal" data-bs-target="#userPermissionsModal"
                                            title="{{ __('app.direct_user_permissions') }}">
                                            <i class="fas fa-key"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('app.no_results') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                {{ __('app.showing_x_to_y_of_z', ['from' => $users->firstItem(), 'to' => $users->lastItem(), 'total' => $users->total()]) }}
            </div>
            <div>
                {{ $users->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger">
        {{ __('app.unauthorized') }}
    </div>
    @endcan

    {{-- User Modals --}}
    <livewire:dashboard.users.form />
    <livewire:dashboard.users.permissions />
    {{-- Stores Modal for quick creation when setting role to merchant --}}
    <livewire:dashboard.stores.form />

    <div wire:loading wire:target="search,country_id,city_id,village_id,perPage,sortBy"
        class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">{{ __('app.updating') }}</span>
    </div>
</div>

@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Function to close user form modal
            function closeUserModal() {
                const modalElement = document.getElementById('userFormModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
            }

            // Function to close user permissions modal
            function closeUserPermissionsModal() {
                const modalElement = document.getElementById('userPermissionsModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
            }

            // Function to close store form modal
            function closeStoreModal() {
                const modalElement = document.getElementById('storeFormModal');
                if (modalElement) {
                    const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                    modal.hide();
                }
            }

            // Listen for multiple events to close modals
            Livewire.on('closeModal', () => {
                closeUserModal();
                closeUserPermissionsModal();
                closeStoreModal();
            });

            Livewire.on('userSaved', () => {
                setTimeout(() => {
                    closeUserModal();
                    closeUserPermissionsModal();
                }, 1500); // Wait 1.5 seconds to show the success message
            });

            Livewire.on('storeSaved', () => {
                setTimeout(() => {
                    closeStoreModal();
                }, 1500); // Wait 1.5 seconds to show the success message
            });

            Livewire.on('closeModalEvent', (data) => {
                if (data && data[0] === 'userFormModal') {
                    closeUserModal();
                }
            });

            // Listen for success toast and close modal
            Livewire.on('show-toast', (event) => {
                if (event.type === 'success' || event.message.includes('بنجاح')) {
                    setTimeout(() => {
                        if (event.message.includes('صلاحيات')) {
                            closeUserPermissionsModal();
                        } else if (event.message.includes('المتجر')) {
                            closeStoreModal();
                        } else {
                            closeUserModal();
                        }
                    }, 1500);
                }
            });

            // Monitor form submission state
            let isSubmitting = false;
            
            // Watch for loading state changes
            document.addEventListener('livewire:loading', (event) => {
                if (event.detail.component.name === 'dashboard.users.form') {
                    isSubmitting = true;
                }
            });

            document.addEventListener('livewire:loaded', (event) => {
                if (event.detail.component.name === 'dashboard.users.form' && isSubmitting) {
                    // Check if there are no validation errors
                    setTimeout(() => {
                        const errorElements = document.querySelectorAll('#userFormModal .invalid-feedback');
                        const hasErrors = Array.from(errorElements).some(el => el.textContent.trim() !== '');
                        
                        if (!hasErrors) {
                            // No errors, close modal after a short delay
                            setTimeout(() => {
                                closeUserModal();
                                isSubmitting = false;
                            }, 1500);
                        } else {
                            isSubmitting = false;
                        }
                    }, 100);
                }
            });
        });
    </script>
@endscript


