<?php

namespace App\Http\Livewire\Profile;

use App\Models\User;
use App\Models\Country;
use App\Models\City;
use App\Models\Village;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $user;
    
    // User fields
    public $name;
    public $email;
    public $phone;
    public $avatar;
    public $country_id;
    public $city_id;
    public $village_id;
    
    // Profile fields
    public $bio;
    public $job_title;
    public $education;
    public $date_of_birth;
    public $cover_photo;
    
    // Settings
    public $profile_visibility = 'public';
    public $preferred_locale;
    
    // File uploads
    public $newAvatar;
    public $newCoverPhoto;
    
    // Options
    public $countries = [];
    public $cities = [];
    public $villages = [];
    public $privacyOptions = [];
    public $localeOptions = [];



    protected $listeners = ['openEditProfileModal' => 'handleOpenModal'];

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadOptions();
        $this->loadUserData();
    }

    public function handleOpenModal()
    {
        // إعادة تحميل بيانات المستخدم من قاعدة البيانات
        $this->user = auth()->user()->fresh();
        $this->loadUserData();
    }



    protected function loadUserData()
    {
        $this->user->loadMissing(['profile.translations', 'settings', 'country', 'city', 'village', 'translations']);
        
        // User data
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->phone = $this->user->phone;
        $this->country_id = $this->user->country_id;
        $this->city_id = $this->user->city_id;
        $this->village_id = $this->user->village_id;
        
        // Profile data (translatable fields)
        $currentLocale = app()->getLocale();
        if ($this->user->profile) {
            $this->bio = $this->user->profile->translate($currentLocale)?->bio ?? $this->user->profile->bio;
            $this->job_title = $this->user->profile->translate($currentLocale)?->job_title ?? $this->user->profile->job_title;
            $this->education = $this->user->profile->translate($currentLocale)?->education ?? $this->user->profile->education;
            $this->date_of_birth = $this->user->profile->date_of_birth?->format('Y-m-d');
        }
        
        // Settings
        $this->profile_visibility = $this->user->settings?->profile_visibility ?? 'public';
        $this->preferred_locale = $this->user->settings?->preferred_locale;
        
        $this->loadCitiesForCountry();
        $this->loadVillagesForCity();
    }

    protected function loadOptions()
    {
        $this->countries = Country::with('translations')->get();
        
        $this->privacyOptions = [
            'public' => __('profile.privacy_public'),
            'friends' => __('profile.privacy_friends'),
            'private' => __('profile.privacy_private'),
        ];
        
        $this->localeOptions = [
            'ar' => __('profile.preferences_locale_options.ar'),
            'en' => __('profile.preferences_locale_options.en'),
        ];
    }

    public function updatedCountryId()
    {
        $this->city_id = null;
        $this->village_id = null;
        $this->cities = [];
        $this->villages = [];
        $this->loadCitiesForCountry();
    }

    public function updatedCityId()
    {
        $this->village_id = null;
        $this->villages = [];
        $this->loadVillagesForCity();
    }

    protected function loadCitiesForCountry()
    {
        if ($this->country_id) {
            $this->cities = City::where('country_id', $this->country_id)
                ->with('translations')
                ->get();
        }
    }

    protected function loadVillagesForCity()
    {
        if ($this->city_id) {
            $this->villages = Village::where('city_id', $this->city_id)
                ->with('translations')
                ->get();
        }
    }

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id)],
            'phone' => ['required', 'string', Rule::unique('users')->ignore($this->user->id)],
            'country_id' => ['nullable', 'exists:countries,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'village_id' => ['nullable', 'exists:villages,id'],
            'bio' => ['nullable', 'string', 'max:500'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'education' => ['nullable', 'string', 'max:255'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'profile_visibility' => ['required', 'in:public,friends,private'],
            'preferred_locale' => ['nullable', 'in:ar,en'],
            'newAvatar' => ['nullable', 'image', 'max:2048'],
            'newCoverPhoto' => ['nullable', 'image', 'max:5120'],
        ];
    }

    protected function validationAttributes()
    {
        return [
            'name' => __('profile.form_name_label'),
            'email' => __('profile.form_email_label'),
            'phone' => __('profile.form_phone_label'),
            'bio' => __('profile.form_bio_label'),
            'job_title' => __('profile.form_job_label'),
            'education' => __('profile.form_education_label'),
            'date_of_birth' => __('profile.form_birthdate_label'),
            'newAvatar' => __('profile.form_avatar_label'),
            'newCoverPhoto' => __('profile.form_cover_photo_label'),
            'profile_visibility' => __('profile.form_privacy_label'),
            'preferred_locale' => __('profile.form_locale_label'),
        ];
    }

    public function save()
    {
        $this->validate();

        try {
            // Handle avatar upload
            if ($this->newAvatar) {
                // Delete old avatar
                if ($this->user->avatar) {
                    Storage::disk('public')->delete($this->user->avatar);
                }
                $avatarPath = $this->newAvatar->store('users/avatars', 'public');
            }

            // Handle cover photo upload
            if ($this->newCoverPhoto) {
                // Delete old cover photo
                if ($this->user->profile?->cover_photo) {
                    Storage::disk('public')->delete($this->user->profile->cover_photo);
                }
                $coverPhotoPath = $this->newCoverPhoto->store('users/covers', 'public');
            }

            // Update user data (excluding name as it's translatable)
            $this->user->update([
                'email' => $this->email,
                'phone' => $this->phone,
                'country_id' => $this->country_id,
                'city_id' => $this->city_id,
                'village_id' => $this->village_id,
                'avatar' => isset($avatarPath) ? $avatarPath : $this->user->avatar,
            ]);

            // Update translatable name for current locale
            $currentLocale = app()->getLocale();
            $this->user->translateOrNew($currentLocale)->name = $this->name;
            $this->user->save();

            // Reload user to get updated translations
            $this->user->refresh();

            // Update or create profile
            $profileData = [
                'date_of_birth' => $this->date_of_birth,
            ];

            if (isset($coverPhotoPath)) {
                $profileData['cover_photo'] = $coverPhotoPath;
            }

            $profile = $this->user->profile()->updateOrCreate(
                ['user_id' => $this->user->id],
                $profileData
            );

            // Update translatable profile fields for current locale
            $currentLocale = app()->getLocale();
            $profileTranslation = $profile->translateOrNew($currentLocale);
            $profileTranslation->bio = $this->bio;
            $profileTranslation->job_title = $this->job_title;
            $profileTranslation->education = $this->education;
            $profile->save();

            // Update settings
            $this->user->settings()->updateOrCreate(
                ['user_id' => $this->user->id],
                [
                    'profile_visibility' => $this->profile_visibility,
                    'preferred_locale' => $this->preferred_locale,
                ]
            );

            // Clear uploaded files after successful save
            $this->newAvatar = null;
            $this->newCoverPhoto = null;
            
            // Reload the data to reflect changes
            $this->loadUserData();
            
            $this->dispatch('profileUpdated');
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => __('profile.update_success')
            ]);
            
            // Close the modal after successful save
            $this->dispatch('close-modal');

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => __('profile.update_error')
            ]);
        }
    }

    public function render()
    {
        return view('livewire.profile.edit');
    }
}