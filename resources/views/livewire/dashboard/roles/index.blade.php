<div>
    @can('browse_roles')
        <div class="card">
            <div class="card-header">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-6">
                        <h3>إدارة الأدوار</h3>
                    </div>
                    @can('add_roles')
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roleFormModal"
                                wire:click="$dispatch('openRoleFormModal')">
                                <i class="fas fa-plus ms-1"></i> اضافة دور جديد
                            </button>
                        </div>
                    @endcan
                </div>
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">بحث</label>
                        <input wire:model.live.debounce.400ms="search" type="text" class="form-control"
                            placeholder="ابحث بالاسم أو المفتاح">
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
                                <th>الصلاحيات</th>
                                <th class="text-center">المستخدمون</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr wire:key="role-{{ $role->id }}">
                                    <td class="fw-semibold"><span
                                            class="badge  {{ $role->color }}">{{ $role->name ?? '—' }}</span></td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($role->permissions as $perm)
                                                <span class="badge bg-secondary">{{ $perm->name }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-link p-0" data-bs-toggle="modal"
                                            data-bs-target="#usersModal"
                                            wire:click="$dispatch('openUsersModal', { roleId: {{ $role->id }} })">
                                            {{ $role->users_count }}
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @can('update', $role)
                                                <button class="btn btn-primary" wire:loading.attr="disabled"
                                                    wire:click="$dispatch('openRoleFormModal', { roleId: {{ $role->id }} })"
                                                    data-bs-toggle="modal" data-bs-target="#roleFormModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endcan
                                            @can('delete', $role)
                                                <button class="btn btn-danger" wire:loading.attr="disabled"
                                                    wire:click="delete({{ $role->id }})"
                                                    onclick="return confirm('هل أنت متأكد من رغبتك في حذف هذا الدور؟');">
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
                    عرض {{ $roles->firstItem() }} - {{ $roles->lastItem() }} من {{ $roles->total() }}
                </div>
                <div>
                    {{ $roles->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div>
    @endcan

    {{-- Role Form Modal --}}
    
        <livewire:dashboard.roles.form />

    <div wire:loading wire:target="search,perPage,sortBy" class="position-fixed bottom-0 end-0 m-3">
        <span class="badge bg-info">جاري التحديث...</span>
    </div>


    {{-- Users Modal --}}
    <x-modal id="usersModal" size="modal-lg" title="المستخدمون">
        <livewire:dashboard.roles.users-modal />
    </x-modal>
</div>
@script
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('closeModal', () => {
                const modalElement = document.getElementById('roleFormModal');
                const modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            });
        });
    </script>
@endscript
