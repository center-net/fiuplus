<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Models\Role;
use App\Models\Country;
use App\Models\City;
use App\Models\Village;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    // بيانات التسجيل
    public string $username = '';
    public string $contact_info = ''; // حقل ذكي (رقم هاتف أو بريد إلكتروني)
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';
    
    // بيانات الموقع
    public ?int $country_id = null;
    public ?int $city_id = null;
    public ?int $village_id = null;
    
    // الاسم المرجعي (اختياري)
    public ?string $referred_by = null;
    
    // قوائم الاختيار
    public $countries = [];
    public $cities = [];
    public $villages = [];
    
    // حالة التحقق من المرجع
    public ?string $referrerName = null;
    public bool $referrerValid = false;
    public bool $hasReferralInUrl = false; // هل يوجد اسم مرجعي في الرابط؟

    /**
     * Mount the component
     */
    public function mount(): void
    {
        // التحقق من وجود معامل ref في الرابط
        if (request()->has('ref')) {
            $this->referred_by = request()->get('ref');
            $this->hasReferralInUrl = true;
            $this->checkReferrer();
        }
        
        // تحميل الدول
        $this->loadCountries();
    }

    /**
     * التحقق من صحة المرجع
     */
    public function checkReferrer(): void
    {
        if (empty($this->referred_by)) {
            $this->referrerValid = false;
            $this->referrerName = null;
            return;
        }

        $referrer = User::where('username', $this->referred_by)->first();
        
        if ($referrer) {
            $this->referrerValid = true;
            $this->referrerName = $referrer->getDisplayName();
        } else {
            $this->referrerValid = false;
            $this->referrerName = null;
        }
    }

    /**
     * تحميل الدول
     */
    public function loadCountries(): void
    {
        $this->countries = Country::with('translations')
            ->get()
            ->sortBy('name');
    }

    /**
     * تحميل المدن عند اختيار الدولة
     */
    public function updatedCountryId($value): void
    {
        $this->city_id = null;
        $this->village_id = null;
        $this->cities = [];
        $this->villages = [];
        
        if ($value) {
            $this->cities = City::with('translations')
                ->where('country_id', $value)
                ->get()
                ->sortBy('name');
        }
    }

    /**
     * تحميل القرى عند اختيار المدينة
     */
    public function updatedCityId($value): void
    {
        $this->village_id = null;
        $this->villages = [];
        
        if ($value) {
            $this->villages = Village::with('translations')
                ->where('city_id', $value)
                ->get()
                ->sortBy('name');
        }
    }

    /**
     * التحقق من المرجع عند تغيير الحقل
     */
    public function updatedReferredBy(): void
    {
        $this->checkReferrer();
    }

    /**
     * تحليل الحقل الذكي (contact_info) عند التغيير
     */
    public function updatedContactInfo(): void
    {
        $this->parseContactInfo();
    }

    /**
     * تحليل الحقل الذكي وتحديد نوعه (رقم هاتف أو بريد إلكتروني)
     */
    protected function parseContactInfo(): void
    {
        $value = trim($this->contact_info);
        
        // إعادة تعيين القيم
        $this->email = '';
        $this->phone = '';
        
        if (empty($value)) {
            return;
        }
        
        // التحقق: هل هو رقم هاتف (10 أرقام بالضبط)؟
        if (preg_match('/^\d{10}$/', $value)) {
            $this->phone = $value;
            return;
        }
        
        // التحقق: هل هو بريد إلكتروني؟
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->email = $value;
            return;
        }
        
        // إذا لم يكن أي منهما، لا نفعل شيء (سيظهر خطأ في التحقق)
    }

    /**
     * قواعد التحقق
     */
    protected function rules(): array
    {
        return [
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:users,username', 'regex:/^[a-zA-Z0-9_]+$/'],
            'contact_info' => [
                'required', 
                'string',
                function ($attribute, $value, $fail) {
                    $value = trim($value);
                    
                    // التحقق: هل هو رقم هاتف (10 أرقام بالضبط)؟
                    if (preg_match('/^\d{10}$/', $value)) {
                        // التحقق من عدم تكرار رقم الهاتف
                        if (User::where('phone', $value)->exists()) {
                            $fail('رقم الهاتف مستخدم بالفعل');
                        }
                        return;
                    }
                    
                    // التحقق: هل هو بريد إلكتروني؟
                    if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        // التحقق من عدم تكرار البريد الإلكتروني
                        if (User::where('email', $value)->exists()) {
                            $fail('البريد الإلكتروني مستخدم بالفعل');
                        }
                        return;
                    }
                    
                    // إذا لم يكن أي منهما
                    $fail('يجب إدخال رقم هاتف (10 أرقام) أو بريد إلكتروني صحيح');
                }
            ],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'country_id' => ['nullable', 'exists:countries,id'],
            'city_id' => ['nullable', 'exists:cities,id'],
            'village_id' => ['nullable', 'exists:villages,id'],
            'referred_by' => ['nullable', 'string', 'exists:users,username'],
        ];
    }

    /**
     * رسائل التحقق المخصصة
     */
    protected function messages(): array
    {
        return [
            'username.required' => 'اسم المستخدم مطلوب',
            'username.min' => 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل',
            'username.unique' => 'اسم المستخدم مستخدم بالفعل',
            'username.regex' => 'اسم المستخدم يجب أن يحتوي على أحرف وأرقام فقط',
            'contact_info.required' => 'رقم الهاتف أو البريد الإلكتروني مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.confirmed' => 'كلمة المرور غير متطابقة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'referred_by.exists' => 'اسم المستخدم المرجعي غير موجود',
        ];
    }

    /**
     * تسجيل مستخدم جديد
     */
    public function register()
    {
        // تحليل الحقل الذكي قبل التحقق
        $this->parseContactInfo();
        
        $this->validate();

        // إنشاء المستخدم
        $user = User::create([
            'username' => strtolower($this->username),
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'password' => Hash::make($this->password),
            'country_id' => null,
            'city_id' => null,
            'village_id' => null,
            'referred_by' => $this->referred_by,
        ]);

        // إضافة دور "زبون" (customer) للمستخدم الجديد
        $customerRole = Role::where('key', 'user')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        }

        // إنشاء ملف شخصي للمستخدم
        $user->profile()->create([]);

        // إنشاء إعدادات المستخدم
        $user->settings()->create([
            'profile_visibility' => 'public',
            'preferred_locale' => app()->getLocale(),
        ]);

        // تسجيل الدخول تلقائياً
        Auth::login($user);

        // إرسال بريد التحقق (اختياري)
        // $user->sendEmailVerificationNotification();

        // رسالة نجاح
        session()->flash('success', 'تم التسجيل بنجاح! مرحباً بك في ' . config('app.name'));

        // إعادة التوجيه إلى الصفحة الرئيسية
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.auth', ['title' => __('auth.register')]);
    }
}