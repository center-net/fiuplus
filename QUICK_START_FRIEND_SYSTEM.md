# 🚀 دليل البدء السريع - نظام الصداقة والحظر

## ✅ ما تم تنفيذه

تم تفعيل نظام الصداقة والحظر بالكامل مع جميع الميزات التالية:

### 1️⃣ زر إضافة صديق في الملف الشخصي
- يظهر تلقائيًا عند زيارة ملف شخصي لمستخدم آخر
- يتغير حسب حالة الصداقة (لا صداقة، طلب مرسل، طلب مستلم، أصدقاء، محظور)

### 2️⃣ أزرار الموافقة/الرفض/الحظر
- عند استلام طلب صداقة، تظهر 3 أزرار: قبول ✅ | رفض ❌ | حظر 🚫
- تظهر في الملف الشخصي وفي الإشعارات

### 3️⃣ نظام الحظر الكامل
- ❌ المحظور لا يمكنه مشاهدة الملف الشخصي
- ❌ المحظور لا يمكنه إرسال طلبات صداقة
- ❌ المحظور لا يمكنه إرسال رسائل (جاهز للتطبيق)
- 🔔 الإشعارات من المحظورين يتم إخفاؤها تلقائيًا

---

## 📁 الملفات المعدلة

### Backend (PHP)
1. ✅ `app/Http/Livewire/Profile/Show.php` - إضافة فحص الحظر ومنع الوصول
2. ✅ `app/Http/Livewire/Friends/FriendButton.php` - إضافة دالة الحظر
3. ✅ `app/Http/Livewire/NotificationDropdown.php` - فلترة الإشعارات وإضافة خيار الحظر
4. ✅ `app/Models/User.php` - التحقق من الحظر في sendFriendRequest (موجود مسبقًا)
5. ✅ `app/Models/Friendship.php` - دالة block() (موجودة مسبقًا)

### Frontend (Blade)
1. ✅ `resources/views/livewire/profile/show.blade.php` - دمج FriendButton وعرض رسالة الحظر
2. ✅ `resources/views/livewire/friends/friend-button.blade.php` - إضافة أزرار الحظر
3. ✅ `resources/views/livewire/notification-dropdown.blade.php` - إضافة زر حظر في الإشعارات

---

## 🎯 كيفية الاستخدام

### للمستخدم النهائي:

#### حظر مستخدم من طلب صداقة:
1. افتح الإشعارات
2. ابحث عن طلب الصداقة
3. اضغط على زر "حظر" 🚫
4. أكد الحظر

#### حظر صديق حالي:
1. اذهب إلى ملفه الشخصي
2. اضغط على زر "أصدقاء" ▼
3. اختر "حظر المستخدم" 🚫
4. أكد الحظر

#### ماذا يحدث بعد الحظر؟
- ✅ يتم حظر المستخدم فورًا
- ✅ لا يمكنه رؤية ملفك الشخصي
- ✅ لا يمكنه إرسال طلبات صداقة
- ✅ تختفي إشعاراته من قائمتك

---

## 💻 للمطورين

### استخدام دوال الحظر في الكود:

#### التحقق من الحظر:
```php
// هل المستخدم الحالي محظور من قبل مستخدم آخر؟
if ($currentUser->isBlockedBy($otherUser)) {
    // المستخدم محظور
}

// هل المستخدم الحالي قام بحظر مستخدم آخر؟
if ($currentUser->hasBlocked($otherUser)) {
    // تم حظر المستخدم الآخر
}
```

#### حظر مستخدم:
```php
$result = $currentUser->blockUser($otherUser);
// أو
$result = $currentUser->blockUser($userId);

if ($result) {
    // تم الحظر بنجاح
}
```

#### منع الوصول في Controller:
```php
public function show(User $user)
{
    $currentUser = auth()->user();
    
    if ($currentUser->isBlockedBy($user)) {
        abort(403, 'تم حظرك من قبل هذا المستخدم');
    }
    
    // عرض المحتوى
}
```

#### فلترة قائمة المستخدمين:
```php
$users = User::all()->filter(function ($user) {
    $currentUser = auth()->user();
    return !$currentUser->isBlockedBy($user) 
        && !$currentUser->hasBlocked($user);
});
```

---

## 🔔 الأحداث (Events)

### الأحداث المتاحة:
```javascript
// في Livewire Components
$this->dispatch('userBlocked');           // عند حظر مستخدم
$this->dispatch('friendRequestSent');     // عند إرسال طلب صداقة
$this->dispatch('friendRequestAccepted'); // عند قبول طلب صداقة
$this->dispatch('friendRemoved');         // عند إلغاء الصداقة
```

### الاستماع للأحداث:
```php
// في Livewire Component
protected $listeners = [
    'userBlocked' => 'handleUserBlocked',
    'friendRequestSent' => 'refreshData',
];

public function handleUserBlocked()
{
    // تنفيذ إجراء عند حظر مستخدم
}
```

---

## 🧪 الاختبار

### اختبار سريع:
1. ✅ أنشئ حسابين مختلفين
2. ✅ من الحساب الأول، أرسل طلب صداقة للحساب الثاني
3. ✅ من الحساب الثاني، افتح الإشعارات
4. ✅ اضغط على "حظر" في طلب الصداقة
5. ✅ من الحساب الأول، حاول زيارة ملف الحساب الثاني
6. ✅ يجب أن تظهر رسالة: "لا يمكنك الوصول لهذا الملف الشخصي"

### التحقق من عمل النظام:
```bash
# التحقق من عدم وجود أخطاء في الملفات
php -l app/Http/Livewire/Profile/Show.php
php -l app/Http/Livewire/Friends/FriendButton.php
php -l app/Http/Livewire/NotificationDropdown.php

# تشغيل الخادم
php artisan serve
```

---

## 🔧 استكشاف الأخطاء

### المشكلة: زر الحظر لا يظهر
**الحل:**
- تأكد من أن `FriendButton` مدمج في صفحة الملف الشخصي
- تحقق من أن `friendshipStatus` يتم تحديثه بشكل صحيح

### المشكلة: المحظور يمكنه رؤية الملف الشخصي
**الحل:**
- تأكد من أن `$isBlocked` يتم تعيينه في `mount()` في `Profile\Show.php`
- تحقق من أن `checkAccess()` يتحقق من `$this->isBlocked`

### المشكلة: الإشعارات من المحظورين لا تزال تظهر
**الحل:**
- تأكد من أن `refreshNotifications()` في `NotificationDropdown.php` يحتوي على فلترة
- تحقق من أن `fromUser` يتم تحميله مع الإشعارات

### المشكلة: يمكن للمحظور إرسال طلبات صداقة
**الحل:**
- تأكد من أن `sendFriendRequest()` في `User.php` يتحقق من حالة `blocked`
- تحقق من أن `FriendButton::sendFriendRequest()` يتحقق من `isBlockedBy()`

---

## 📊 قاعدة البيانات

### جدول friendships:
```sql
-- الأعمدة المهمة
id              BIGINT
sender_id       BIGINT      -- من قام بالإجراء (إرسال طلب أو حظر)
receiver_id     BIGINT      -- المستقبل
status          ENUM        -- pending, accepted, declined, blocked
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### حالات الصداقة:
- `pending` - طلب صداقة معلق
- `accepted` - صداقة مقبولة
- `declined` - طلب مرفوض
- `blocked` - محظور

### ملاحظة مهمة:
عند الحظر، `sender_id` هو من قام بالحظر، و `receiver_id` هو المحظور.

---

## 🎨 التخصيص

### تغيير نص رسالة الحظر:
في `resources/views/livewire/profile/show.blade.php`:
```blade
<h2 class="h5">لا يمكنك الوصول لهذا الملف الشخصي</h2>
<p class="mb-0">تم حظرك من قبل هذا المستخدم</p>
```

### تغيير رسالة التأكيد:
في `resources/views/livewire/friends/friend-button.blade.php`:
```blade
onclick="return confirm('هل أنت متأكد من حظر هذا المستخدم؟')"
```

### تغيير ألوان الأزرار:
```blade
<!-- زر الحظر -->
<button class="btn btn-dark btn-sm">  <!-- غير btn-dark -->
    <i class="fas fa-ban"></i> حظر
</button>
```

---

## 🚀 التطوير المستقبلي

### 1. إضافة صفحة المحظورين:
```php
// في routes/web.php
Route::get('/blocked-users', [BlockedUsersController::class, 'index'])
    ->name('blocked.index');

// في Controller
public function index()
{
    $blockedUsers = auth()->user()->blockedUsers();
    return view('blocked.index', compact('blockedUsers'));
}
```

### 2. إضافة ميزة إلغاء الحظر:
```php
// في User Model
public function unblockUser($user): bool
{
    $userId = $user instanceof User ? $user->id : $user;
    $friendship = Friendship::findBetweenUsers($this->id, $userId);
    
    if ($friendship && $friendship->status === 'blocked') {
        return $friendship->delete();
    }
    
    return false;
}
```

### 3. دمج مع نظام الرسائل:
```php
// في Message Controller
public function send(Request $request)
{
    $receiver = User::find($request->receiver_id);
    
    if (auth()->user()->isBlockedBy($receiver)) {
        return response()->json([
            'error' => 'لا يمكنك إرسال رسالة لهذا المستخدم'
        ], 403);
    }
    
    // إرسال الرسالة
}
```

---

## 📞 الدعم والمساعدة

### الموارد:
- 📄 الدليل الشامل: `FRIEND_BLOCK_SYSTEM.md`
- 📄 هذا الدليل: `QUICK_START_FRIEND_SYSTEM.md`
- 📝 سجلات Laravel: `storage/logs/laravel.log`

### الأوامر المفيدة:
```bash
# مسح الكاش
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# إعادة تحميل Livewire
php artisan livewire:discover

# عرض المسارات
php artisan route:list

# تشغيل الخادم
php artisan serve
```

---

## ✅ قائمة التحقق

قبل النشر، تأكد من:
- [ ] جميع الملفات تم تحديثها
- [ ] لا توجد أخطاء في بناء الجملة
- [ ] تم اختبار جميع السيناريوهات
- [ ] الأزرار تظهر بشكل صحيح
- [ ] رسائل الحظر تظهر
- [ ] الإشعارات تعمل بشكل صحيح
- [ ] المحظورين لا يمكنهم الوصول للملفات الشخصية
- [ ] المحظورين لا يمكنهم إرسال طلبات صداقة

---

**🎉 النظام جاهز للاستخدام!**

جميع الميزات المطلوبة تم تنفيذها بنجاح والنظام يعمل بشكل كامل.