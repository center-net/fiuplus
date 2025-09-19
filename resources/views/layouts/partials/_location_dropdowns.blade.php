{{-- Partial View للـ dropdowns المشتركة للدولة، المدينة، والقرية --}}
{{-- $modelPrefix لتخصيص wire:model --}}
{{-- $colSize لحجم العمود --}}
{{-- $useLive لاستخدام wire:model.live بدلاً من change --}}
{{-- $showErrors لإظهار @error --}}
{{-- $fieldPrefix للـ id و for --}}
{{-- $isSearch لتحديد النص في الـ option الأولى --}}
@php
    $useLive = $useLive ?? false;
    $showErrors = $showErrors ?? false;
    $countryModel = $useLive ? 'live' : 'change';
    $fieldPrefix = $fieldPrefix ?? '';
    $isSearch = $isSearch ?? false;
@endphp
<div class="{{ $colSize ?? 'col-md-4' }} ">
    <label for="{{ $fieldPrefix }}country_id" class="form-label">الدولة</label>
    <select class="form-select{{ $showErrors && $errors->has($modelPrefix . 'country_id') ? ' is-invalid' : '' }}" id="{{ $fieldPrefix }}country_id"
        wire:model.{{ $countryModel }}="{{ $modelPrefix }}country_id">
        <option value="">{{ $isSearch ? 'الكل' : 'اختر دولة' }}</option>
        @foreach ($countries as $country)
            <option value="{{ $country->id }}">{{ $country->name }}</option>
        @endforeach
    </select>
    @if($showErrors)
        @error($modelPrefix . 'country_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>
<div class="{{ $colSize ?? 'col-md-4' }} ">
    <label for="{{ $fieldPrefix }}city_id" class="form-label">المدينة</label>
    <select class="form-select{{ $showErrors && $errors->has($modelPrefix . 'city_id') ? ' is-invalid' : '' }}" id="{{ $fieldPrefix }}city_id"
        wire:model.change="{{ $modelPrefix }}city_id"@if(!data_get($this, $modelPrefix . 'country_id')) disabled @endif>
        <option value="">{{ $isSearch ? 'الكل' : 'اختر مدينة' }}</option>
        @foreach ($cities as $city)
            <option value="{{ $city->id }}">{{ $city->name }}</option>
        @endforeach
    </select>
    @if($showErrors)
        @error($modelPrefix . 'city_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>
<div class="{{ $colSize ?? 'col-md-4' }} ">
    <label for="{{ $fieldPrefix }}village_id" class="form-label">القرية</label>
    <select class="form-select{{ $showErrors && $errors->has($modelPrefix . 'village_id') ? ' is-invalid' : '' }}" id="{{ $fieldPrefix }}village_id"
        wire:model.change="{{ $modelPrefix }}village_id"@if(!data_get($this, $modelPrefix . 'city_id')) disabled @endif>
        <option value="">{{ $isSearch ? 'الكل' : 'اختر قرية' }}</option>
        @foreach ($villages as $village)
            <option value="{{ $village->id }}">{{ $village->name }}</option>
        @endforeach
    </select>
    @if($showErrors)
        @error($modelPrefix . 'village_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    @endif
</div>