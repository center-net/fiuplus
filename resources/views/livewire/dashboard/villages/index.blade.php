<div>
    @can('viewAny', \App\Models\Village::class)
        <div class="card">
            <div class="card-header">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <h3>إدارة القرى</h3>
                    </div>
                    @can('create', \App\Models\Village::class)
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#villageFormModal"
                                wire:click="$dispatch('openVillageFormModal')">
                                <i class="fas fa-plus ms-1"></i> اضافة قرية جديدة
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
                    <div class="col-md-3">
                        <label class="form-label">المدينة</label>
                        <select wire:model.change="cityId" class="form-select" @disabled(!$countryId)>
                            <option value="">{{ $countryId ? 'الكل' : 'اختر دولة أولاً' }}</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
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
                                <th>المدينة</th>
                                <th>الدولة</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($villages as $village)
                                <tr wire:key="village-{{ $village->id }}">
                                    <td class="fw-semibold">{{ $village->name }}</td>
                                    <td>{{ $village->city?->name }}</td>
                                    <td>{{ $village->city?->country?->name }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('update', $village)
                                                <button class="btn btn-primary" wire:loading.attr="disabled"
                                                    wire:click="$dispatch('openVillageFormModal', { villageId: {{ $village->id }} })"
                                                    data-bs-toggle="modal" data-bs-target="#villageFormModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('delete', $village)
                                                <button class="btn btn-danger" wire:loading.attr="disabled"
                                                    wire:click="delete({{ $village->id }})"
                                                    onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذه القرية؟');">
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
                    عرض {{ $villages->firstItem() }} - {{ $villages->lastItem() }} من {{ $villages->total() }}
                </div>
                <div>
                    {{ $villages->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div>
    @endcan

    <livewire:dashboard.villages.form />

    <div wire:loading wire:target="search,perPage,sortBy,countryId,cityId" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">جاري التحديث...</span>
    </div>
</div>
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('closeModal', () => {
                const modalElement = document.getElementById('villageFormModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
@endscript