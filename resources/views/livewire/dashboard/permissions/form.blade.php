<div>
    <div class="modal-header">
        <h5 class="modal-title" id="permissionFormModalLabel">{{ $permission_id ? 'تعديل صلاحية' : 'اضافة صلاحية جديدة' }}</h5>
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
                    <label for="table_name" class="form-label">اسم الجدول</label>
                    <input type="text" class="form-control @error('table_name') is-invalid @enderror" id="table_name" wire:model="table_name">
                    @error('table_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>