<div>

    {{-- <x-modal id="userFormModal" size="modal-lg" title="{{ $user_id ? __('app.edit_user') : __('app.add_new_user') }}">
        <div class="modal-body">
            <form wire:submit.prevent="save"> --}}

    <x-modal id="storeFormModal" size="modal-lg" title="{{ $store_id ? __('app.edit_store') : __('app.add_new_store') }}">
        <div class="modal-body">
            <form wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">{{ __('app.name') }}</label>
                        <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                            wire:model="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="user_id" class="form-label">{{ __('app.owner') }}</label>
                        <select id="user_id" class="form-select @error('user_id') is-invalid @enderror"
                            wire:model="user_id">
                            <option value="">{{ __('app.select_owner') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name ?: $user->username }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                            wire:model="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                        <input type="text" id="phone" class="form-control @error('phone') is-invalid @enderror"
                            wire:model="phone">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

    </x-modal>
    </form>
</div>
