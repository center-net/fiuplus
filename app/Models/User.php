<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class User extends Authenticatable implements MustVerifyEmail, TranslatableContract
{
    use HasApiTokens, HasFactory, Notifiable, Translatable;


    public $translatedAttributes = ['name'];
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
        'email',
        'password',
        'username',
        'avatar',
        'phone',
        'country_id',
        'city_id',
        'village_id',
        'last_seen',
        'referred_by',
    ];

    /**
     * العلاقة مع الملف الشخصي.
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * العلاقة مع إعدادات المستخدم.
     */
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    /**
     * الحصول على تفضيلات الملف الشخصي (مع إنشاء افتراضي عند الحاجة).
     */
    public function getProfileVisibilityAttribute(): string
    {
        return $this->settings?->profile_visibility ?? 'public';
    }

    /**
     * اللغة المفضلة للمستخدم.
     */
    public function getPreferredLocaleAttribute(): ?string
    {
        return $this->settings?->preferred_locale;
    }

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
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * علاقة المستخدم بالصلاحيات المباشرة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        // direct permissions with pivot 'effect' (allow/deny)
        return $this->belongsToMany(Permission::class)
            ->withPivot('effect')
            ->withTimestamps();
    }

    /**
     * Check if the user has a specific permission.
     *
     * Uses a versioned cache key so we can invalidate all keys by bumping the version.
     *
     * @param string $key
     * @return bool
     */
    public function hasPermission($key)
    {
        if ($this->isBanned()) {
            return false;
        }

        $version = Cache::get("user.{$this->id}.perm_version", 1);
        $cacheKey = "user.{$this->id}.permission.v{$version}.{$key}";

        return Cache::remember($cacheKey, 600, function () use ($key) {
            // Avoid N+1 by ensuring relations are loaded once
            $this->loadMissing(['permissions', 'roles.permissions']);

            // 1) Direct DENY overrides everything
            $directDeny = $this->permissions->first(function ($p) use ($key) {
                return $p->key === $key && optional($p->pivot)->effect === 'deny';
            });
            if ($directDeny) {
                return false;
            }

            // 2) Direct ALLOW
            $directAllow = $this->permissions->first(function ($p) use ($key) {
                return $p->key === $key && optional($p->pivot)->effect !== 'deny';
            });
            if ($directAllow) {
                return true;
            }

            // 3) Role-based permissions
            foreach ($this->roles as $role) {
                if ($role->permissions->contains('key', $key)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * Grant a direct permission to the user.
     */
    public function allowPermission(int|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;
        $this->permissions()->syncWithoutDetaching([$permissionId => ['effect' => 'allow']]);
        $this->clearPermissionsCache();
    }

    /**
     * Explicitly deny a permission to the user.
     */
    public function denyPermission(int|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;
        $this->permissions()->syncWithoutDetaching([$permissionId => ['effect' => 'deny']]);
        $this->clearPermissionsCache();
    }

    /**
     * Remove any direct assignment (allow/deny) for a permission from the user.
     */
    public function revokeDirectPermission(int|Permission $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;
        $this->permissions()->detach($permissionId);
        $this->clearPermissionsCache();
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $key
     * @return bool
     */
    public function hasRole($key)
    {
        return $this->roles->contains('key', $key);
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array $keys
     * @return bool
     */
    public function hasAnyRole($keys)
    {
        return $this->roles()->whereIn('key', $keys)->exists();
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
     * علاقة المستخدم بمتجره (واحد إلى واحد)
     */
    public function store()
    {
        return $this->hasOne(Store::class);
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
     * Check if the user is banned.
     *
     * @return bool
     */
    public function isBanned()
    {
        return $this->hasRole('banned');
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
     * - نزيد رقم إصدار الكاش بدل حذف مفاتيح متعددة
     * @return void
     */
    public function clearPermissionsCache()
    {
        $version = Cache::get("user.{$this->id}.perm_version", 1);
        Cache::forever("user.{$this->id}.perm_version", $version + 1);
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


    }

    /**
     * استعلام مخصص للبحث عن المستخدمين
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('username', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhereHas('translations', function ($t) use ($search) {
                  $t->where('name', 'like', "%{$search}%");
              });
        });
    }

    // ==================== علاقات الأصدقاء ====================

    /**
     * طلبات الصداقة المرسلة
     */
    public function sentFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    /**
     * طلبات الصداقة المستقبلة
     */
    public function receivedFriendRequests()
    {
        return $this->hasMany(Friendship::class, 'receiver_id');
    }

    /**
     * جميع علاقات الصداقة (مرسلة ومستقبلة)
     */
    public function friendships()
    {
        return $this->sentFriendRequests()->union($this->receivedFriendRequests()->getQuery());
    }

    /**
     * الأصدقاء المقبولين (كعلاقة)
     */
    public function friends()
    {
        // استخدام belongsToMany مع custom query لدعم العلاقة ثنائية الاتجاه
        return $this->belongsToMany(User::class, 'friendships', 'sender_id', 'receiver_id')
            ->wherePivot('status', 'accepted')
            ->withPivot('status', 'created_at', 'accepted_at')
            ->orWhere(function ($query) {
                $query->where('friendships.receiver_id', $this->id)
                      ->where('friendships.status', 'accepted');
            });
    }

    /**
     * الحصول على قائمة الأصدقاء كـ Collection
     */
    public function getFriends()
    {
        $sentFriends = $this->sentFriendRequests()
            ->where('status', 'accepted')
            ->with('receiver')
            ->get()
            ->pluck('receiver');

        $receivedFriends = $this->receivedFriendRequests()
            ->where('status', 'accepted')
            ->with('sender')
            ->get()
            ->pluck('sender');

        return $sentFriends->merge($receivedFriends);
    }

    /**
     * طلبات الصداقة المعلقة المستقبلة (كعلاقة)
     */
    public function pendingFriendRequests()
    {
        return $this->receivedFriendRequests()->where('status', 'pending');
    }

    /**
     * الحصول على طلبات الصداقة المعلقة كـ Collection
     */
    public function getPendingFriendRequests()
    {
        return $this->receivedFriendRequests()
            ->where('status', 'pending')
            ->with('sender')
            ->get();
    }

    /**
     * إرسال طلب صداقة
     */
    public function sendFriendRequest($user): ?Friendship
    {
        // تحويل المعامل إلى ID إذا كان User object
        $userId = $user instanceof User ? $user->id : $user;
        
        // التحقق من عدم إرسال طلب لنفس المستخدم
        if ($this->id === $userId) {
            return null;
        }

        // التحقق من عدم وجود علاقة صداقة مسبقة
        $existingFriendship = Friendship::findBetweenUsers($this->id, $userId);
        if ($existingFriendship) {
            // إذا كان محظور، لا يمكن إرسال طلب
            if ($existingFriendship->status === 'blocked') {
                return null;
            }
            return null;
        }

        // إنشاء طلب الصداقة
        $friendship = Friendship::create([
            'sender_id' => $this->id,
            'receiver_id' => $userId,
            'status' => 'pending',
        ]);

        // إنشاء إشعار - نحتاج User object هنا
        $userObject = $user instanceof User ? $user : User::find($userId);
        if ($userObject) {
            Notification::createFriendRequest($userObject, $this);
        }

        return $friendship;
    }

    /**
     * قبول طلب صداقة
     */
    public function acceptFriendRequest($sender): bool
    {
        $senderId = $sender instanceof User ? $sender->id : $sender;
        
        $friendship = Friendship::where('sender_id', $senderId)
            ->where('receiver_id', $this->id)
            ->where('status', 'pending')
            ->first();

        if (!$friendship) {
            return false;
        }

        $accepted = $friendship->accept();

        if ($accepted) {
            // إنشاء إشعار للمرسل
            $senderUser = $sender instanceof User ? $sender : User::find($senderId);
            if ($senderUser) {
                Notification::createFriendAccepted($senderUser, $this);
            }
        }

        return $accepted;
    }

    /**
     * رفض طلب صداقة
     */
    public function declineFriendRequest($sender): bool
    {
        $senderId = $sender instanceof User ? $sender->id : $sender;
        
        $friendship = Friendship::where('sender_id', $senderId)
            ->where('receiver_id', $this->id)
            ->where('status', 'pending')
            ->first();

        return $friendship ? $friendship->decline() : false;
    }

    /**
     * إلغاء طلب صداقة مرسل
     */
    public function cancelFriendRequest($receiver): bool
    {
        $receiverId = $receiver instanceof User ? $receiver->id : $receiver;
        
        $friendship = Friendship::where('sender_id', $this->id)
            ->where('receiver_id', $receiverId)
            ->where('status', 'pending')
            ->first();

        return $friendship ? $friendship->delete() : false;
    }

    /**
     * إلغاء الصداقة
     */
    public function removeFriend($friend): bool
    {
        $friendId = $friend instanceof User ? $friend->id : $friend;
        
        $friendship = Friendship::findBetweenUsers($this->id, $friendId);
        return $friendship ? $friendship->delete() : false;
    }

    /**
     * حظر مستخدم
     */
    public function blockUser($user): bool
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        $friendship = Friendship::findBetweenUsers($this->id, $userId);
        
        if ($friendship) {
            return $friendship->block();
        }

        // إنشاء علاقة حظر جديدة
        return (bool) Friendship::create([
            'sender_id' => $this->id,
            'receiver_id' => $userId,
            'status' => 'blocked',
        ]);
    }

    /**
     * التحقق من حالة الصداقة مع مستخدم آخر
     */
    public function getFriendshipStatus($user): ?string
    {
        $userId = $user instanceof User ? $user->id : $user;
        
        if ($this->id === $userId) {
            return 'self';
        }

        $friendship = Friendship::findBetweenUsers($this->id, $userId);
        
        if (!$friendship) {
            return 'none';
        }

        // إذا كانت الحالة pending، نحدد ما إذا كان الطلب مرسل أو مستلم
        if ($friendship->status === 'pending') {
            if ($friendship->sender_id === $this->id) {
                return 'pending_sent';
            } else {
                return 'pending_received';
            }
        }

        return $friendship->status;
    }

    /**
     * التحقق من كون المستخدم صديق
     */
    public function isFriendWith(User $user): bool
    {
        return $this->getFriendshipStatus($user) === 'accepted';
    }

    /**
     * التحقق من وجود طلب صداقة معلق
     */
    public function hasPendingFriendRequestWith(User $user): bool
    {
        return $this->getFriendshipStatus($user) === 'pending';
    }

    /**
     * الطلبات المرسلة المعلقة
     */
    public function sentPendingRequests()
    {
        return $this->sentFriendRequests()->where('status', 'pending');
    }

    /**
     * التحقق من كون المستخدم محظور
     */
    public function isBlockedBy(User $user): bool
    {
        $friendship = Friendship::findBetweenUsers($this->id, $user->id);
        return $friendship && $friendship->status === 'blocked' && $friendship->sender_id === $user->id;
    }

    /**
     * التحقق من حظر مستخدم آخر
     */
    public function hasBlocked(User $user): bool
    {
        $friendship = Friendship::findBetweenUsers($this->id, $user->id);
        return $friendship && $friendship->status === 'blocked' && $friendship->sender_id === $this->id;
    }

    /**
     * عدد الأصدقاء
     */
    public function getFriendsCount(): int
    {
        $sentCount = $this->sentFriendRequests()->where('status', 'accepted')->count();
        $receivedCount = $this->receivedFriendRequests()->where('status', 'accepted')->count();
        return $sentCount + $receivedCount;
    }

    /**
     * عدد طلبات الصداقة المعلقة
     */
    public function getPendingFriendRequestsCount(): int
    {
        return $this->receivedFriendRequests()->where('status', 'pending')->count();
    }

    // ==================== علاقات الإشعارات ====================

    /**
     * إشعارات المستخدم
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * الإشعارات غير المقروءة
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * عدد الإشعارات غير المقروءة
     */
    public function getUnreadNotificationsCount(): int
    {
        return $this->unreadNotifications()->count();
    }

    // ==================== علاقات الإحالة (Referral System) ====================

    /**
     * المستخدم الذي قام بدعوة هذا المستخدم
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by', 'username');
    }

    /**
     * المستخدمين الذين تم دعوتهم من قبل هذا المستخدم
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by', 'username');
    }

    /**
     * عدد المستخدمين الذين تم دعوتهم
     */
    public function getReferralsCount(): int
    {
        return $this->referrals()->count();
    }

    /**
     * الحصول على رابط الدعوة
     */
    public function getReferralLink(): string
    {
        return route('register', ['ref' => $this->username]);
    }
}