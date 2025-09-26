<div>
    <form wire:submit.prevent="save">
        <x-modal id="storeCategoryFormModal" size="modal-lg"
            title="{{ $category_id ? __('app.edit_store_category') : __('app.add_new_store_category') }}">
            <div class="mb-3">
                <label class="form-label">{{ __('app.name') }}</label>
                <input type="text" class="form-control" wire:model.live.debounce.400ms="name">
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" class="form-control" wire:model.live.debounce.400ms="slug">
                @error('slug')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">{{ __('app.details') }}</label>
                <textarea class="form-control" rows="3" wire:model.live.debounce.400ms="description"></textarea>
                @error('description')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </x-modal>
    </form>
</div>
