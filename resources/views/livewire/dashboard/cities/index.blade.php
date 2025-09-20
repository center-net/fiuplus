<div>
    @can('viewAny', \App\Models\City::class)
        <div class="card">
            <div class="card-header">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <h3>إدارة المدن</h3>
                    </div>
                    @can('create', \App\Models\City::class)
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cityFormModal"
                                wire:click="$dispatch('openCityFormModal')">
                                <i class="fas fa-plus ms-1"></i> اضافة مدينة جديدة
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">بحث</label>
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                            placeholder="ابحث بالاسم أو الرمز">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">الدولة</label>
                        <select wire:model.change="countryId" class="form-select">
                            <option value="">الكل</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">صفحة</label>
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
                                <th wire:click="sort('name')" style="cursor: pointer;">الاسم
                                    @if ($sortBy === 'name')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @else
                                        <i class="fas fa-sort"></i>
                                    @endif
                                </th>
                                <th>الدولة</th>
                                <th>القرى</th>
                                <th>تكلفة التوصيل</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cities as $city)
                                <tr wire:key="city-{{ $city->id }}">
                                    <td class="fw-semibold">{{ $city->name }}</td>
                                    <td>{{ $city->country?->name }}</td>
                                    <td>{{ $city->villages_count }}</td>
                                    <td>{{ number_format((float) ($city->delivery_cost ?? 0), 2) }} <small class="text-muted">₪</small></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('update', $city)
                                                <button class="btn btn-primary" wire:loading.attr="disabled"
                                                    wire:click="$dispatch('openCityFormModal', { cityId: {{ $city->id }} })"
                                                    data-bs-toggle="modal" data-bs-target="#cityFormModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('delete', $city)
                                                <button class="btn btn-danger" wire:loading.attr="disabled"
                                                    wire:click="delete({{ $city->id }})"
                                                    onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه المدينة؟');">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">لا يوجد نتائج حسب التصفية الحالية</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    عرض {{ $cities->firstItem() }} - {{ $cities->lastItem() }} من {{ $cities->total() }}
                </div>
                <div>
                    {{ $cities->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div>
    @endcan

    <livewire:dashboard.cities.form />

    <div wire:loading wire:target="search,perPage,sortBy,countryId" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">جاري التحديث...</span>
    </div>
</div>
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('closeModal', () => {
                const modalElement = document.getElementById('cityFormModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
@endscript