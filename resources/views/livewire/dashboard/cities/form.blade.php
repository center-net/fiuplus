<div>
    <x-modal id="cityFormModal" size="modal-lg" title="{{ $city_id ? 'تعديل مدينة' : 'اضافة مدينة جديدة' }}">
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
                        <label for="country_id" class="form-label">الدولة</label>
                        <select class="form-select @error('country_id') is-invalid @enderror" id="country_id"
                            wire:model="country_id">
                            <option value="">اختر دولة</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="delivery_cost" class="form-label">تكلفة التوصيل</label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0" class="form-control @error('delivery_cost') is-invalid @enderror" id="delivery_cost"
                                   wire:model.lazy="delivery_cost" placeholder="0.00">
                            <span class="input-group-text">₪</span>
                            @error('delivery_cost')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
    </x-modal>
    </form>
</div>
