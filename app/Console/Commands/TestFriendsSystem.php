<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Friendship;
use App\Models\Notification;

class TestFriendsSystem extends Command
{
    protected $signature = 'test:friends-system';
    protected $description = 'Test the friends system functionality';

    public function handle()
    {
        $this->info('🔍 اختبار نظام الأصدقاء...');
        $this->newLine();

        // اختبار المستخدمين
        $this->info('1️⃣ اختبار المستخدمين:');
        $users = User::whereIn('email', [
            'ahmed@example.com',
            'sara@example.com', 
            'omar@example.com',
            'fatima@example.com',
            'hassan@example.com'
        ])->get();

        if ($users->count() === 5) {
            $this->info('✅ تم العثور على جميع المستخدمين التجريبيين (5)');
            foreach ($users as $user) {
                $this->line("   - {$user->name} ({$user->email})");
            }
        } else {
            $this->error('❌ لم يتم العثور على جميع المستخدمين التجريبيين');
            return;
        }

        $this->newLine();

        // اختبار علاقات الصداقة
        $this->info('2️⃣ اختبار علاقات الصداقة:');
        $friendships = Friendship::all();
        $this->info("✅ إجمالي علاقات الصداقة: {$friendships->count()}");

        $acceptedFriendships = Friendship::where('status', 'accepted')->count();
        $pendingFriendships = Friendship::where('status', 'pending')->count();
        $blockedFriendships = Friendship::where('status', 'blocked')->count();

        $this->line("   - مقبولة: {$acceptedFriendships}");
        $this->line("   - معلقة: {$pendingFriendships}");
        $this->line("   - محظورة: {$blockedFriendships}");

        $this->newLine();

        // اختبار الإشعارات
        $this->info('3️⃣ اختبار الإشعارات:');
        $notifications = Notification::all();
        $this->info("✅ إجمالي الإشعارات: {$notifications->count()}");

        $unreadNotifications = Notification::whereNull('read_at')->count();
        $friendRequestNotifications = Notification::where('type', 'friend_request')->count();
        $friendAcceptedNotifications = Notification::where('type', 'friend_accepted')->count();

        $this->line("   - غير مقروءة: {$unreadNotifications}");
        $this->line("   - طلبات صداقة: {$friendRequestNotifications}");
        $this->line("   - قبول صداقة: {$friendAcceptedNotifications}");

        $this->newLine();

        // اختبار وظائف المستخدم
        $this->info('4️⃣ اختبار وظائف المستخدم:');
        $ahmed = $users->where('email', 'ahmed@example.com')->first();
        
        if ($ahmed) {
            $friendsCount = $ahmed->friends()->count();
            $pendingRequestsCount = $ahmed->pendingFriendRequests()->count();
            $sentRequestsCount = $ahmed->sentPendingRequests()->count();
            $unreadNotificationsCount = $ahmed->unreadNotifications()->count();

            $this->info("✅ إحصائيات أحمد:");
            $this->line("   - الأصدقاء: {$friendsCount}");
            $this->line("   - طلبات واردة: {$pendingRequestsCount}");
            $this->line("   - طلبات مرسلة: {$sentRequestsCount}");
            $this->line("   - إشعارات غير مقروءة: {$unreadNotificationsCount}");
        }

        $this->newLine();

        // اختبار مكونات Livewire
        $this->info('5️⃣ اختبار مكونات Livewire:');
        
        $livewireComponents = [
            'App\Http\Livewire\NotificationDropdown',
            'App\Http\Livewire\Friends\FriendButton',
            'App\Http\Livewire\Friends\FriendsList'
        ];

        foreach ($livewireComponents as $component) {
            if (class_exists($component)) {
                $this->info("✅ {$component}");
            } else {
                $this->error("❌ {$component}");
            }
        }

        $this->newLine();

        // اختبار الملفات المطلوبة
        $this->info('6️⃣ اختبار الملفات المطلوبة:');
        
        $requiredFiles = [
            'resources/views/livewire/notification-dropdown.blade.php',
            'resources/views/livewire/friends/friend-button.blade.php',
            'resources/views/livewire/friends/friends-list.blade.php',
            'resources/views/friends/index.blade.php',
            'resources/views/test-friends.blade.php',
            'resources/lang/ar/friends.php',
            'resources/lang/en/friends.php'
        ];

        foreach ($requiredFiles as $file) {
            $fullPath = base_path($file);
            if (file_exists($fullPath)) {
                $this->info("✅ {$file}");
            } else {
                $this->error("❌ {$file}");
            }
        }

        $this->newLine();
        $this->info('🎉 انتهى اختبار نظام الأصدقاء!');
        $this->info('🌐 يمكنك الآن زيارة: http://127.0.0.1:8000/test-friends للاختبار');
    }
}