<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationDropdown extends Component
{
    public $notifications = [];
    public $unreadCount = 0;
    public $showDropdown = false;
    
    protected $listeners = [
        'friendRequestSent' => 'refreshNotifications',
        'friendRequestAccepted' => 'refreshNotifications',
        'friendRequestCancelled' => 'refreshNotifications',
        'notificationRead' => 'refreshNotifications'
    ];
    
    public function mount()
    {
        $this->refreshNotifications();
    }
    
    public function refreshNotifications()
    {
        if (!Auth::check()) {
            $this->notifications = [];
            $this->unreadCount = 0;
            return;
        }
        
        $user = Auth::user();
        
        // جلب آخر 10 إشعارات
        $this->notifications = $user->notifications()
            ->with('fromUser')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // عدد الإشعارات غير المقروءة
        $this->unreadCount = $user->unreadNotifications()->count();
    }
    
    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            $this->refreshNotifications();
        }
    }
    
    public function markAsRead($notificationId)
    {
        if (!Auth::check()) {
            return;
        }
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();
        
        if ($notification) {
            $notification->markAsRead();
            $this->refreshNotifications();
            $this->dispatch('notificationRead');
        }
    }
    
    public function markAllAsRead()
    {
        if (!Auth::check()) {
            return;
        }
        
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        $this->refreshNotifications();
        $this->dispatch('notificationRead');
    }
    
    public function handleNotificationAction($notificationId, $action)
    {
        if (!Auth::check()) {
            return;
        }
        
        $notification = Notification::where('id', $notificationId)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$notification) {
            return;
        }
        
        $fromUserId = $notification->from_user_id;
        $currentUser = Auth::user();
        
        if ($action === 'accept' && $notification->type === 'friend_request') {
            $result = $currentUser->acceptFriendRequest($fromUserId);
            if ($result) {
                // إنشاء إشعار للمرسل بقبول الطلب
                $fromUser = \App\Models\User::find($fromUserId);
                if ($fromUser) {
                    Notification::createFriendAccepted($fromUser, $currentUser);
                }
                $this->markAsRead($notificationId);
                session()->flash('success', 'تم قبول طلب الصداقة');
            }
        } elseif ($action === 'decline' && $notification->type === 'friend_request') {
            $result = $currentUser->declineFriendRequest($fromUserId);
            if ($result) {
                $this->markAsRead($notificationId);
                session()->flash('success', 'تم رفض طلب الصداقة');
            }
        }
        
        $this->refreshNotifications();
        $this->dispatch('friendRequestHandled');
    }
    
    public function render()
    {
        return view('livewire.notification-dropdown');
    }
}