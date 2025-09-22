<div>
<x-modal id="permissionFormModal" size="" title="{{ $permission_id ? __('app.edit') . ' ' . __('app.permissions') : __('app.add_new_permission') }}">
    <div class="modal-body">
        <form wire:submit.prevent="save">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">{{ __('app.name') }}</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="key" class="form-label">{{ __('app.key_label') }}</label>
                    <input type="text" class="form-control @error('key') is-invalid @enderror" id="key" wire:model="key">
                    @error('key') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="table_name" class="form-label">{{ __('app.table_name_label') }}</label>
                    <input type="text" class="form-control @error('table_name') is-invalid @enderror" id="table_name" wire:model="table_name">
                    @error('table_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

          </x-modal>
        </form>
    </div>
</div>