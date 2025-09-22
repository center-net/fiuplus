<div>
    <x-modal id="villageFormModal" size="modal-lg" title="{{ $village_id ? __('app.edit_village') : __('app.add_new_village') }}">
        <div class="modal-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="name" class="form-label">{{ __('app.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                               wire:model="name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="country_id" class="form-label">{{ __('app.country') }}</label>
                        <select class="form-select @error('country_id') is-invalid @enderror" id="country_id"
                                wire:model.change="country_id">
                            <option value="">{{ __('app.choose_country') }}</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="city_id" class="form-label">{{ __('app.city') }}</label>
                        <select class="form-select @error('city_id') is-invalid @enderror" id="city_id"
                                wire:model.change="city_id" @disabled(!$country_id)>
                            <option value="">{{ $country_id ? __('app.choose_city') : __('app.choose_country_first') }}</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    </x-modal>
    </form>
</div>
