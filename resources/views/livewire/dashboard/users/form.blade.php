<div>
    {{--  --}}
    <x-modal id="userFormModal" size="modal-lg" title="{{ $user_id ? __('app.edit_user') : __('app.add_new_user') }}" formId="userForm">
        <form id="userForm" wire:submit.prevent="save">
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
                    <label for="username" class="form-label">{{ __('app.username') }}</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror"
                        id="username" wire:model="username">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="email" class="form-label">{{ __('app.email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        wire:model="email">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3" x-data="{ isUploading: false, progress: 0 }">
                    <label for="avatar" class="form-label">{{ __('app.user_avatar') }}</label>

                    <!-- Image Preview -->
                    <div class="mb-2">
                        @if ($new_avatar)
                            {{-- New avatar preview --}}
                            <img src="{{ $new_avatar->temporaryUrl() }}" alt="{{ __('app.user_image_alt') }}" class="img-thumbnail"
                                width="150">
                        @elseif ($avatar)
                            {{-- Existing avatar from URL --}}
                            <img src="{{ asset('storage/' . $avatar) }}" alt="{{ __('app.user_image_alt') }}"
                                class="img-thumbnail" width="150">
                        @else
                            {{-- Placeholder --}}
                            <img src="https://via.placeholder.com/150" alt="{{ __('app.user_image_alt') }}"
                                class="img-thumbnail" width="150">
                        @endif
                    </div>

                    <!-- File Input -->
                    <div x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress">
                        <input type="file" class="form-control @error('new_avatar') is-invalid @enderror"
                            id="new_avatar" wire:model="new_avatar">
                    </div>

                    <!-- Progress Bar -->
                    <div x-show="isUploading" class="progress mt-2">
                        <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`"
                            :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" x-text="`${progress}%`">
                        </div>
                    </div>

                    @error('new_avatar')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label mb-2">{{ __('app.roles') }}</label>

                    @php
                        $selectedRoleIds = collect($selectedRoles ?? [])
                            ->map(fn ($id) => (int) $id)
                            ->all();
                    @endphp

                    <div class="row g-2">
                        @forelse ($roles as $role)
                            @php
                                $isSelected = in_array((int) $role->id, $selectedRoleIds, true);
                            @endphp
                            <div class="col-12 col-sm-6">
                                <div
                                    class="border rounded p-3 h-100 d-flex align-items-center justify-content-between gap-3">
                                    <div class="flex-grow-1 min-w-0">
                                        <span class="fw-semibold d-block text-truncate">{{ $role->name }}</span>
                                        @if (!empty($role->description))
                                            <small class="text-muted d-block text-truncate">{{ $role->description }}</small>
                                        @endif
                                    </div>
                                    <div class="flex-shrink-0">
                                        <input type="checkbox" class="btn-check" id="role-{{ $role->id }}"
                                            value="{{ $role->id }}" wire:model="selectedRoles" autocomplete="off">
                                        <label class="btn btn-sm {{ $isSelected ? 'btn-success' : 'btn-outline-danger' }}"
                                            for="role-{{ $role->id }}">
                                            {{ $isSelected ? __('app.allow') : __('app.deny') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning mb-0" role="alert">
                                    {{ __('app.no_roles_available') }}
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @error('selectedRoles')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">{{ __('app.password_label') }}</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                        id="password" wire:model="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        wire:model="phone">
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Country, City, Village dropdowns --}}
            <div class="row">
                @include('layouts.partials._location_dropdowns', [
                    'modelPrefix' => '',
                    'colSize' => 'col-md-4',
                    'useLive' => true,
                    'showErrors' => true,
                    'fieldPrefix' => '',
                    'isSearch' => false,
                ])
            </div>
            {{-- Roles and Permissions (simplified for now) --}}
        </form>
    </x-modal>
</div>

