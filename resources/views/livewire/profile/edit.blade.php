@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile-edit.css') }}">
@endpush

<div>
    <!-- Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel">
                        <i class="fas fa-edit me-2"></i>
                        {{ __('profile.edit_profile_button') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form wire:submit.prevent="save" id="profileEditForm">
                    <div class="modal-body">
                        <!-- Username Display (Read-only) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <div class="flex-grow-1">
                                        <strong>{{ __('profile.form_username_label') }}:</strong> @{{ $user->username }}
                                        <br>
                                        <small>{{ __('profile.username_readonly_description') }}</small>
                                    </div>
                                    <i class="fas fa-lock text-muted" title="{{ __('profile.username_readonly_hint') }}"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-user me-2"></i>
                            {{ __('profile.form_basic_info_heading') }}
                        </h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ __('profile.form_name_label') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" wire:model="name" placeholder="{{ __('profile.form_name_placeholder') }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">{{ __('profile.form_email_label') }} <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" wire:model="email" placeholder="{{ __('profile.form_email_placeholder') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">{{ __('profile.form_phone_label') }} <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" wire:model="phone" placeholder="{{ __('profile.form_phone_placeholder') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">{{ __('profile.form_birthdate_label') }}</label>
                                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" wire:model="date_of_birth">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Location Information -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ __('profile.form_location_heading') }}
                        </h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="country_id" class="form-label">{{ __('profile.form_country_label') }}</label>
                                <select class="form-select @error('country_id') is-invalid @enderror" 
                                        id="country_id" wire:model="country_id">
                                    <option value="">{{ __('profile.form_country_placeholder') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="city_id" class="form-label">{{ __('profile.form_city_label') }}</label>
                                <select class="form-select @error('city_id') is-invalid @enderror" 
                                        id="city_id" wire:model="city_id" @if(empty($cities)) disabled @endif>
                                    <option value="">{{ __('profile.form_city_placeholder') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('city_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="village_id" class="form-label">{{ __('profile.form_village_label') }}</label>
                                <select class="form-select @error('village_id') is-invalid @enderror" 
                                        id="village_id" wire:model="village_id" @if(empty($villages)) disabled @endif>
                                    <option value="">{{ __('profile.form_village_placeholder') }}</option>
                                    @foreach($villages as $village)
                                        <option value="{{ $village->id }}">{{ $village->name }}</option>
                                    @endforeach
                                </select>
                                @error('village_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Profile Information -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-id-card me-2"></i>
                            {{ __('profile.form_profile_info_heading') }}
                        </h6>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">{{ __('profile.form_bio_label') }}</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" wire:model="bio" rows="3" 
                                      placeholder="{{ __('profile.form_bio_placeholder') }}"></textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="job_title" class="form-label">{{ __('profile.form_job_label') }}</label>
                                <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                                       id="job_title" wire:model="job_title" placeholder="{{ __('profile.form_job_placeholder') }}">
                                @error('job_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="education" class="form-label">{{ __('profile.form_education_label') }}</label>
                                <input type="text" class="form-control @error('education') is-invalid @enderror" 
                                       id="education" wire:model="education" placeholder="{{ __('profile.form_education_placeholder') }}">
                                @error('education')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Photos -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-images me-2"></i>
                            {{ __('profile.form_photos_heading') }}
                        </h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="newAvatar" class="form-label">{{ __('profile.form_avatar_label') }}</label>
                                <input type="file" class="form-control @error('newAvatar') is-invalid @enderror" 
                                       id="newAvatar" wire:model="newAvatar" accept="image/*">
                                @error('newAvatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($newAvatar)
                                    <div class="mt-2">
                                        <img src="{{ $newAvatar->temporaryUrl() }}" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">
                                    </div>
                                @elseif($user->avatar)
                                    <div class="mt-2">
                                        <img src="{{ $user->getAvatarUrl() }}" class="img-thumbnail" width="100" height="100" style="object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="newCoverPhoto" class="form-label">{{ __('profile.form_cover_photo_label') }}</label>
                                <input type="file" class="form-control @error('newCoverPhoto') is-invalid @enderror" 
                                       id="newCoverPhoto" wire:model="newCoverPhoto" accept="image/*">
                                @error('newCoverPhoto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($newCoverPhoto)
                                    <div class="mt-2">
                                        <img src="{{ $newCoverPhoto->temporaryUrl() }}" class="img-thumbnail" width="150" height="75" style="object-fit: cover;">
                                    </div>
                                @elseif($user->profile?->cover_photo)
                                    <div class="mt-2">
                                        <img src="{{ $user->profile->getCoverPhotoUrl() }}" class="img-thumbnail" width="150" height="75" style="object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Settings -->
                        <h6 class="fw-bold text-primary mb-3">
                            <i class="fas fa-cog me-2"></i>
                            {{ __('profile.form_preferences_heading') }}
                        </h6>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="profile_visibility" class="form-label">{{ __('profile.form_privacy_label') }}</label>
                                <select class="form-select @error('profile_visibility') is-invalid @enderror" 
                                        id="profile_visibility" wire:model="profile_visibility">
                                    @foreach($privacyOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('profile_visibility')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="preferred_locale" class="form-label">{{ __('profile.form_locale_label') }}</label>
                                <select class="form-select @error('preferred_locale') is-invalid @enderror" 
                                        id="preferred_locale" wire:model="preferred_locale">
                                    <option value="">{{ __('profile.form_locale_placeholder') }}</option>
                                    @foreach($localeOptions as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('preferred_locale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>
                            {{ __('profile.form_cancel_button') }}
                        </button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-save me-2"></i>
                                {{ __('profile.form_save_button') }}
                            </span>
                            <span wire:loading>
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                {{ __('profile.form_saving_button') }}
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to close modal
    function closeModal() {
        const modalElement = document.getElementById('editProfileModal');
        if (modalElement) {
            const modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            modal.hide();
        }
    }

    // Function to open modal
    function openModal() {
        const modalElement = document.getElementById('editProfileModal');
        if (modalElement) {
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        }
    }

    document.addEventListener('livewire:init', function () {
        // Handle toast notifications
        Livewire.on('show-toast', (event) => {
            const toast = document.createElement('div');
            toast.className = `alert alert-${event.type} alert-dismissible fade show position-fixed`;
            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            toast.innerHTML = `
                ${event.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 5000);
            
            // Close modal if success toast
            if (event.type === 'success') {
                setTimeout(() => {
                    closeModal();
                }, 1500); // Wait 1.5 seconds to show the success message
            }
        });
        
        // Handle profile update success
        Livewire.on('profileUpdated', function () {
            closeModal();
        });
        
        // Handle modal close event
        Livewire.on('close-modal', function () {
            closeModal();
        });
        
        // Handle open edit profile modal event (from CompleteProfile component)
        Livewire.on('openEditProfileModal', function () {
            setTimeout(() => {
                openModal();
            }, 300); // Small delay to ensure complete profile modal is closed first
        });
        
        // Monitor form submission and close modal on success
        let isSubmitting = false;
        const form = document.getElementById('profileEditForm');
        if (form) {
            form.addEventListener('submit', function() {
                isSubmitting = true;
            });
        }
        
        // Listen for Livewire loading state changes
        document.addEventListener('livewire:loading', function() {
            if (isSubmitting) {
                // Form is being submitted
            }
        });
        
        document.addEventListener('livewire:loaded', function() {
            if (isSubmitting) {
                // Check if there are no validation errors
                const errorElements = document.querySelectorAll('#editProfileModal .invalid-feedback');
                const hasErrors = Array.from(errorElements).some(el => el.textContent.trim() !== '');
                
                if (!hasErrors) {
                    // No errors, close modal after a short delay
                    setTimeout(() => {
                        closeModal();
                        isSubmitting = false;
                    }, 1500);
                } else {
                    isSubmitting = false;
                }
            }
        });
    });
</script>
@endpush