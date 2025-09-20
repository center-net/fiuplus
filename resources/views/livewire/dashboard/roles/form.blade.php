<div>
    <x-modal id="roleFormModal" size="modal-lg" title="{{ $role_id ? 'تعديل دور' : 'اضافة دور جديد' }}">
        <div class="modal-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">الاسم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            wire:model="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="key" class="form-label">المفتاح</label>
                        <input type="text" class="form-control @error('key') is-invalid @enderror" id="key"
                            wire:model="key">
                        @error('key')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="color" class="form-label">اللون (اختياري)</label>
                        <select class="form-select @error('color') is-invalid @enderror" id="color"
                            wire:model="color">
                            <option value="">اختر لونًا</option>
                            <option value="bg-primary text-white" class="bg-primary text-white">أزرق (Primary)</option>
                            <option value="bg-success text-white" class="bg-success text-white">أخضر (Success)</option>
                            <option value="bg-danger text-white" class="bg-danger text-white">أحمر (Danger)</option>
                            <option value="bg-warning text-dark" class="bg-warning text-dark">أصفر (Warning)</option>
                            <option value="bg-secondary text-white" class="bg-secondary text-white">رمادي (Secondary)
                            </option>
                            <option value="bg-info text-dark" class="bg-info text-dark">سماوي (Info)</option>
                            <option value="bg-light text-dark" class="bg-light text-dark">فاتح (Light)</option>
                            <option value="bg-dark text-white" class="bg-dark text-white">داكن (Dark)</option>
                        </select>
                        @error('color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">الصلاحيات</label>
                    <div class="row g-3">
                            @php
                                $grouped = $permissions->groupBy(fn($p) => $p->table_name ?? 'عام');
                            @endphp
                            @foreach($grouped as $group => $list)
                                <div class="col-12">
                                    <div class="border rounded p-2">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">{{ $group === 'عام' ? 'صلاحيات عامة' : 'صلاحيات: ' . $group }}</h6>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    wire:click="$set('selectedPermissions', array_values(array_unique(array_merge((array)$selectedPermissions, {{ $list->pluck('id') }}->toArray()))))">
                                                    تحديد الكل
                                                </button>
                                                <button type="button" class="btn btn-outline-secondary"
                                                    wire:click="$set('selectedPermissions', array_values(array_diff((array)$selectedPermissions, {{ $list->pluck('id') }}->toArray())))">
                                                    إلغاء الكل
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                                            @foreach($list as $permission)
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value="{{ $permission->id }}"
                                                            id="perm-{{ $permission->id }}"
                                                            wire:model.live="selectedPermissions">
                                                        <label class="form-check-label" for="perm-{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @error('selectedPermissions')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

    </x-modal>

    </form>
</div>
</div>
