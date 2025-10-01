# استخدام ميزة تحويل @username إلى روابط

## نظرة عامة
تم إضافة ميزة تحويل `@username` إلى روابط تلقائية للملف الشخصي للمستخدم.

## الطرق المتاحة

### 1. استخدام الدالة المساعدة `linkify_mentions()`

تحول أي نص يحتوي على `@username` إلى رابط:

```php
// في PHP
$text = "مرحباً @ahmed كيف حالك؟ هل رأيت @sara اليوم؟";
echo linkify_mentions($text);
// النتيجة: مرحباً <a href="http://fiuplus.test/profile/ahmed">@ahmed</a> كيف حالك؟ هل رأيت <a href="http://fiuplus.test/profile/sara">@sara</a> اليوم؟
```

```blade
{{-- في Blade --}}
{!! linkify_mentions($comment->text) !!}
```

### 2. استخدام Blade Directive `@mentions`

```blade
{{-- في Blade --}}
@mentions($comment->text)

{{-- مثال --}}
<div class="comment-text">
    @mentions("شكراً @admin على المساعدة!")
</div>
```

### 3. استخدام الدالة المساعدة `format_username()`

لتنسيق اسم مستخدم واحد مع رابط:

```php
// مع @ قبل الاسم (افتراضي)
echo format_username('ahmed');
// النتيجة: <a href="http://fiuplus.test/profile/ahmed">@ahmed</a>

// بدون @ قبل الاسم
echo format_username('ahmed', false);
// النتيجة: <a href="http://fiuplus.test/profile/ahmed">ahmed</a>
```

```blade
{{-- في Blade --}}
{!! format_username($user->username) !!}
{!! format_username($user->username, false) !!}
```

### 4. استخدام Blade Directive `@username`

```blade
{{-- في Blade --}}
@username($user->username)

{{-- مثال --}}
<div class="user-info">
    المستخدم: @username($referral->username)
</div>
```

## أمثلة عملية

### مثال 1: في التعليقات
```blade
<div class="comment">
    <div class="comment-author">
        @username($comment->user->username)
    </div>
    <div class="comment-text">
        @mentions($comment->text)
    </div>
</div>
```

### مثال 2: في الإشعارات
```blade
<div class="notification">
    @mentions($notification->message)
</div>
```

### مثال 3: في المنشورات
```blade
<div class="post">
    <div class="post-content">
        {!! linkify_mentions($post->content) !!}
    </div>
</div>
```

## ملاحظات مهمة

1. **الأمان**: استخدم `{!! !!}` بدلاً من `{{ }}` لعرض HTML
2. **التنسيق**: الروابط تأتي مع classes Bootstrap: `text-primary text-decoration-none fw-bold`
3. **الأسماء المدعومة**: يدعم أسماء المستخدمين التي تحتوي على:
   - حروف إنجليزية (a-z, A-Z)
   - أرقام (0-9)
   - شرطة سفلية (_)

## التخصيص

يمكنك تخصيص التنسيق من خلال تعديل ملف `app/Helpers/helpers.php`:

```php
// تغيير classes الروابط
$replacement = '<a href="' . url('/') . '/profile/$1" class="your-custom-classes">@$1</a>';
```

## الملفات المعدلة

1. `app/Helpers/helpers.php` - الدوال المساعدة
2. `app/Providers/AppServiceProvider.php` - Blade Directives
3. `composer.json` - تحميل ملف helpers
4. `app/Http/Livewire/Profile/InviteFriend.php` - إصلاح تحميل البيانات
5. `resources/views/livewire/profile/invite-friend.blade.php` - تحويل @username إلى رابط