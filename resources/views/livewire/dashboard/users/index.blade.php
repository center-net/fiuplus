<div>
    <div class="card">
        <div class="card-header">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">بحث</label>
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control" placeholder="ابحث ">
                </div>
                <div class="col-md-2">
                    <label class="form-label">الدولة</label>
                    <select wire:model.change="country_id" class="form-select">
                        <option value="">الكل</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">المدينة</label>
                    <select wire:model.change="city_id" class="form-select" @disabled(!$country_id)>
                        <option value="">الكل</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">القرية</label>
                    <select wire:model.change="village_id" class="form-select" @disabled(!$city_id)>
                        <option value="">الكل</option>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}">{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">صفحة</label>
                    <select wire:model.change="perPage" class="form-select">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-rotate-left ms-1"></i> تصفية افتراضية
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
                                الاسم
                                @if($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sort('email')" style="cursor: pointer;">
                                البريد الإلكتروني
                                @if($sortBy === 'email')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th>الدولة</th>
                            <th>المدينة</th>
                            <th>القرية</th>
                            <th class="text-center">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr wire:key="user-{{ $user->id }}">
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>
                                    <div>{{ $user->email }}</div>
                                    <div class="text-muted small">{{ $user->username }}</div>
                                </td>
                                <td>{{ $user->country->name ?? '-' }}</td>
                                <td>{{ $user->city->name ?? '-' }}</td>
                                <td>{{ $user->village->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-primary" wire:loading.attr="disabled">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger" wire:loading.attr="disabled">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                عرض {{ $users->firstItem() }} - {{ $users->lastItem() }} من {{ $users->total() }}
            </div>
            <div>
                {{ $users->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    <div wire:loading wire:target="search,country_id,city_id,village_id,perPage,sortBy" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">جاري التحديث...</span>
    </div>
</div>