<div class="container py-4">
    <h2 class="mb-4">{{ __('app.complete_store_setup') }}</h2>

    <form wire:submit.prevent="save" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">{{ __('app.store_name') }}</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name">
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">{{ __('app.category') }}</label>
                    <select id="category_id" class="form-select @error('category_id') is-invalid @enderror" wire:model="category_id">
                        <option value="">{{ __('app.select_category') }}</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">{{ __('app.email') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" wire:model="email">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" wire:model="phone">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        <div class="card-footer d-flex gap-2">
            <button class="btn btn-primary" type="submit">{{ __('app.save') }}</button>
            <button class="btn btn-success" type="button" wire:click="activateAndFinish">{{ __('app.finish') }}</button>
        </div>
    </form>
</div>