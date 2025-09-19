<div>
    {{--  --}}
    <x-modal id="userFormModal" size="modal-lg" title="{{ $user_id ? 'تعديل مستخدم' : 'اضافة مستخدم جديد' }}">
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
                        <label for="username" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror"
                            id="username" wire:model="username">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            wire:model="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3" x-data="{ isUploading: false, progress: 0 }">
                        <label for="avatar" class="form-label">صورة المستخدم</label>

                        <!-- Image Preview -->
                        <div class="mb-2">
                            @if ($avatar)
                                @if (is_string($avatar))
                                    {{-- Existing avatar from URL --}}
                                    <img src="{{ asset('storage/' . $avatar) }}" alt="Avatar Preview"
                                        class="img-thumbnail" width="150">
                                @else
                                    {{-- New avatar preview --}}
                                    <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar Preview" class="img-thumbnail"
                                        width="150">
                                @endif
                            @else
                                {{-- Placeholder --}}
                                <img src="https://via.placeholder.com/150" alt="Avatar Placeholder"
                                    class="img-thumbnail" width="150">
                            @endif
                        </div>

                        <!-- File Input -->
                        <div x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false"
                            x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <input type="file" class="form-control @error('avatar') is-invalid @enderror"
                                id="avatar" wire:model="avatar">
                        </div>

                        <!-- Progress Bar -->
                        <div x-show="isUploading" class="progress mt-2">
                            <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`"
                                :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" x-text="`${progress}%`">
                            </div>
                        </div>

                        @error('avatar')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الأدوار</label>
                        <select class="form-select @error('selectedRoles') is-invalid @enderror" multiple
                            wire:model="selectedRoles" id="roles">
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedRoles')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="password" wire:model="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">رقم الهاتف</label>
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
    </x-modal>

    </form>
</div>
</div>
