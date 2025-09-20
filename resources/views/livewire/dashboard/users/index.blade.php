<div>
    @can('viewAny', \App\Models\User::class)
    <div class="card">
        <div class="card-header">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <h3>ادارة المستخدمين</h3>
                </div>
                @can('create', \App\Models\User::class)
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userFormModal"
                        wire:click="$dispatch('openUserFormModal')">
                        <i class="fas fa-plus ms-1"></i> اضافة مستخدم جديد
                    </button>
                </div>
                @endcan
            </div>
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">بحث</label>
                    <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                        placeholder="ابحث ">
                </div>
                @include('layouts.partials._location_dropdowns', ['modelPrefix' => '', 'colSize' => 'col-md-2'])
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
                                @if ($sortBy === 'name')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort"></i>
                                @endif
                            </th>
                            <th wire:click="sort('email')" style="cursor: pointer;">
                                البريد الإلكتروني
                                @if ($sortBy === 'email')
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
                                            onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟');">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                        @can('addPermission', $user)
                                        <button class="btn btn-secondary" wire:loading.attr="disabled"
                                            wire:click="$dispatch('openUserPermissionsModal', [{{ $user->id }}])"
                                            data-bs-toggle="modal" data-bs-target="#userPermissionsModal"
                                            title="صلاحيات المستخدم المباشرة">
                                            <i class="fas fa-key"></i>
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
                عرض {{ $users->firstItem() }} - {{ $users->lastItem() }} من {{ $users->total() }}
            </div>
            <div>
                {{ $users->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-danger">
        ليس لديك الصلاحية لعرض هذه الصفحة.
    </div>
    @endcan

    {{-- User Modals --}}
    <livewire:dashboard.users.form />
    <livewire:dashboard.users.permissions />

    <div wire:loading wire:target="search,country_id,city_id,village_id,perPage,sortBy"
        class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">جاري التحديث...</span>
    </div>
</div>
