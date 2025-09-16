<div>
    <div class="modal-header">
        <h5 class="modal-title" id="userFormModalLabel">{{ $user_id ? 'تعديل مستخدم' : 'اضافة مستخدم جديد' }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
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
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        wire:model="username">
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
                            @if(is_string($avatar))
                                {{-- Existing avatar from URL --}}
                                <img src="{{ asset('storage/users/' . $avatar) }}" alt="Avatar Preview" class="img-thumbnail" width="150">
                            @else
                                {{-- New avatar preview --}}
                                <img src="{{ $avatar->temporaryUrl() }}" alt="Avatar Preview" class="img-thumbnail" width="150">
                            @endif
                        @else
                            {{-- Placeholder --}}
                            <img src="https://via.placeholder.com/150" alt="Avatar Placeholder" class="img-thumbnail" width="150">
                        @endif
                    </div>

                    <!-- File Input -->
                    <div
                        x-on:livewire-upload-start="isUploading = true"
                        x-on:livewire-upload-finish="isUploading = false"
                        x-on:livewire-upload-error="isUploading = false"
                        x-on:livewire-upload-progress="progress = $event.detail.progress"
                    >
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" wire:model="avatar">
                    </div>

                    <!-- Progress Bar -->
                    <div x-show="isUploading" class="progress mt-2">
                        <div class="progress-bar" role="progressbar" :style="`width: ${progress}%`" :aria-valuenow="progress" aria-valuemin="0" aria-valuemax="100" x-text="`${progress}%`"></div>
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
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        wire:model="password">
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
                <div class="col-md-4 mb-3">
                    <label for="country_id" class="form-label">الدولة</label>
                    <select class="form-select @error('country_id') is-invalid @enderror" id="country_id"
                        wire:model.live="country_id">
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
                    <label for="city_id" class="form-label">المدينة</label>
                    <select class="form-select @error('city_id') is-invalid @enderror" id="city_id"
                        wire:model.live="city_id" @disabled(!$country_id)>
                        <option value="">اختر مدينة</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="village_id" class="form-label">القرية</label>
                    <select class="form-select @error('village_id') is-invalid @enderror" id="village_id"
                        wire:model="village_id" @disabled(!$city_id)>
                        <option value="">اختر قرية</option>
                        @foreach ($villages as $village)
                            <option value="{{ $village->id }}">{{ $village->name }}</option>
                        @endforeach
                    </select>
                    @error('village_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            {{-- Roles and Permissions (simplified for now) --}}

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
        </form>
    </div>
</div>
