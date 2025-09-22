<div>
    <x-modal id="countryFormModal" size="modal-lg" title="{{ $country_id ? __('app.edit_country') : __('app.add_new_country') }}">
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
                        <label for="iso3" class="form-label">ISO3</label>
                        <input type="text" class="form-control @error('iso3') is-invalid @enderror" id="iso3"
                            wire:model="iso3">
                        @error('iso3')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
    </x-modal>
    </form>
</div>
