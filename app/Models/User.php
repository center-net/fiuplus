<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /**
     * القيم التي يمكن تعبئتها جماعياً
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'avatar',
        'phone',
        'role_id',
        'country_id',
        'city_id',
        'village_id',
        'last_seen',
    ];

    /**
     * القيم التي يجب إخفاؤها عند التحويل إلى JSON
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * القيم التي يجب تحويلها إلى أنواع بيانات محددة
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_seen' => 'datetime',
    ];

    /**
     * @var array<int, string>
     */
    protected $dates = [
        'last_seen',
        'email_verified_at',
    ];

    /**
     * تحويل اسم المستخدم إلى أحرف صغيرة قبل الحفظ
     * @param string $value
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = strtolower($value);
    }

    /**
     * علاقة المستخدم بالدور
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * علاقة المستخدم بالصلاحيات المباشرة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * التحقق من امتلاك المستخدم لصلاحية معينة
     * @param string $key
     * @return bool
     */
    public function hasPermission($key)
    {
        // التحقق من عدم حظر المستخدم
        if ($this->role && $this->role->key === 'banned') {
            return false;
        }

        return Cache::remember("user.{$this->id}.permission.{$key}", 3600, function() use ($key) {
            return $this->permissions->contains('key', $key) || 
                   ($this->role && $this->role->permissions->contains('key', $key));
        });
    }

    /**
     * التحقق من دور المستخدم
     * @param string $key
     * @return bool
     */
    public function hasRole($key)
    {
        return $this->role && $this->role->key === $key;
    }

    /**
     * التحقق من امتلاك المستخدم لأي من الأدوار المحددة
     * @param array $keys
     * @return bool
     */
    public function hasAnyRole($keys)
    {
        return $this->role && in_array($this->role->key, $keys);
    }

    /**
     * علاقة المستخدم بالدولة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * علاقة المستخدم بالمدينة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * علاقة المستخدم بالقرية
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    /**
     * التحقق من نشاط المستخدم
     * @return bool
     */
    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->diffInMinutes(now()) < 5;
    }

    /**
     * التحقق من حالة التحقق من البريد الإلكتروني
     * @return bool
     */
    public function isVerified()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * التحقق من حالة حظر المستخدم
     * @return bool
     */
    public function isBanned()
    {
        return $this->role && $this->role->key === 'banned';
    }

    /**
     * الحصول على الموقع الكامل للمستخدم (القرية، المدينة، الدولة)
     * @return string
     */
    public function getFullLocation()
    {
        $location = [];
        
        if ($this->village && $this->village->name) {
            $location[] = $this->village->name;
        }
        
        if ($this->city && $this->city->name) {
            $location[] = $this->city->name;
        }
        
        if ($this->country && $this->country->name) {
            $location[] = $this->country->name;
        }
        
        return implode(', ', $location);
    }

    /**
     * تحديث آخر ظهور للمستخدم
     * @return bool
     */
    public function updateLastSeen()
    {
        return $this->update(['last_seen' => now()]);
    }

    /**
     * الحصول على الاسم الكامل أو اسم المستخدم
     * @return string
     */
    public function getDisplayName()
    {
        return $this->name ?: $this->username;
    }

    /**
     * الحصول على رابط الصورة الرمزية
     * @return string
     */
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        // إذا لم يكن هناك صورة، نستخدم صورة افتراضية من UI Avatars
        $name = urlencode($this->getDisplayName());
        return "https://ui-avatars.com/api/?name={$name}&background=random";
    }

    /**
     * مسح الصلاحيات المخزنة مؤقتاً
     * @return void
     */
    public function clearPermissionsCache()
    {
        Cache::forget("user.{$this->id}.permissions");
        foreach ($this->permissions as $permission) {
            Cache::forget("user.{$this->id}.permission.{$permission->key}");
        }
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // عند حذف المستخدم
        static::deleting(function ($user) {
            // مسح الصلاحيات المخزنة مؤقتاً
            $user->clearPermissionsCache();
            
            // حذف الصورة الرمزية إذا كانت موجودة
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        });

        // عند تحديث المستخدم
        static::updated(function ($user) {
            // إذا تم تغيير الدور أو الصلاحيات، نمسح الصلاحيات المخزنة مؤقتاً
            if ($user->isDirty('role_id')) {
                $user->clearPermissionsCache();
            }
        });
    }

    /**
     * استعلام مخصص للبحث عن المستخدمين
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
