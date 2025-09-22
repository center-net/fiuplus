<div>
    @can('viewAny', \App\Models\Country::class)
        <div class="card">
            <div class="card-header">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <h3>{{ __('app.countries_manage_title') }}</h3>
                    </div>
                    @can('create', \App\Models\Country::class)
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#countryFormModal"
                                wire:click="$dispatch('openCountryFormModal')">
                                <i class="fas fa-plus ms-1"></i> {{ __('app.add_new_country') }}
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('app.search') }}</label>
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                            placeholder="{{ __('app.search_placeholder') }}">
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
                                <th wire:click="sort('name')" style="cursor: pointer;">{{ __('app.name') }}
                                    @if ($sortBy === 'name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </th>
                                <th>{{ __('app.cities') }}</th>
                                <th>{{ __('app.villages') }}</th>
                                <th class="text-center">{{ __('app.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($countries as $country)
                                <tr wire:key="country-{{ $country->id }}">
                                    <td class="fw-semibold">{{ $country->name }}</td>
                                    <td>{{ $country->cities_count }}</td>
                                    <td>{{ $country->villages_count }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('update', $country)
                                                <button class="btn btn-primary" wire:loading.attr="disabled"
                                                    wire:click="$dispatch('openCountryFormModal', { countryId: {{ $country->id }} })"
                                                    data-bs-toggle="modal" data-bs-target="#countryFormModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('delete', $country)
                                                <button class="btn btn-danger" wire:loading.attr="disabled"
                                                    wire:click="delete({{ $country->id }})"
                                                    onclick="return confirm('{{ __('app.confirm_delete_country') }}');">
                                                    <i class="fas fa-trash"></i>
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
                    {{ __('app.showing_x_to_y_of_z', ['from' => $countries->firstItem(), 'to' => $countries->lastItem(), 'total' => $countries->total()]) }}
                </div>
                <div>
                    {{ $countries->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">{{ __('app.unauthorized') }}</div>
    @endcan

    <livewire:dashboard.countries.form />

    <div wire:loading wire:target="search,perPage,sortBy" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">{{ __('app.updating') }}</span>
    </div>
</div>
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('closeModal', () => {
                const modalElement = document.getElementById('countryFormModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
@endscript