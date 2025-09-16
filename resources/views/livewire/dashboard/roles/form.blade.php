<div>
    <div class="modal-header">
        <h5 class="modal-title" id="roleFormModalLabel">{{ $role_id ? 'تعديل دور' : 'اضافة دور جديد' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <form wire:submit.prevent="save">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">الاسم</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="key" class="form-label">المفتاح</label>
                    <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" wire:model="key">
                    @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="color" class="form-label">اللون (اختياري)</label>
                    <select class="form-select @error('color') is-invalid @enderror" id="color" wire:model="color">
                        <option value="">اختر لونًا</option>
                        <option value="bg-primary text-white" class="bg-primary text-white">أزرق (Primary)</option>
                        <option value="bg-success text-white" class="bg-success text-white">أخضر (Success)</option>
                        <option value="bg-danger text-white" class="bg-danger text-white">أحمر (Danger)</option>
                        <option value="bg-warning text-dark" class="bg-warning text-dark">أصفر (Warning)</option>
                        <option value="bg-secondary text-white" class="bg-secondary text-white">رمادي (Secondary)</option>
                        <option value="bg-info text-dark" class="bg-info text-dark">سماوي (Info)</option>
                        <option value="bg-light text-dark" class="bg-light text-dark">فاتح (Light)</option>
                        <option value="bg-dark text-white" class="bg-dark text-white">داكن (Dark)</option>
                    </select>
                    @error('color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">الصلاحيات</label>
                <select class="form-select @error('selectedPermissions') is-invalid @enderror" multiple wire:model="selectedPermissions" size="10">
                    @php($grouped = $permissions->groupBy('table_name'))
                    @foreach($grouped as $table => $perms)
                        <optgroup label="{{ $table }}">
                            @foreach($perms as $perm)
                                <option value="{{ $perm->id }}">{{ $perm->name }} ({{ $perm->key }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('selectedPermissions') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>