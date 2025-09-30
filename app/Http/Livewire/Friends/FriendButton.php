<?php

namespace App\Http\Livewire\Friends;

use Livewire\Component;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FriendButton extends Component
{
    public $userId;
    public $friendshipStatus;
    
    public function mount($userId)
    {
        $this->userId = $userId;
        $this->updateFriendshipStatus();
    }
    
    public function updateFriendshipStatus()
    {
        if (!Auth::check()) {
            $this->friendshipStatus = null;
            return;
        }
        
        $currentUser = Auth::user();
        
        if ($currentUser->id === $this->userId) {
            $this->friendshipStatus = 'self';
            return;
        }
        
        $this->friendshipStatus = $currentUser->getFriendshipStatus($this->userId);
    }
    
    public function sendFriendRequest()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        
        if ($currentUser->id === $this->userId) {
            return;
        }
        
        // التحقق من الحظر قبل الإرسال
        $targetUser = User::find($this->userId);
        if ($targetUser && $currentUser->isBlockedBy($targetUser)) {
            session()->flash('error', 'لا يمكنك إرسال طلب صداقة لهذا المستخدم');
            return;
        }
        
        $result = $currentUser->sendFriendRequest($this->userId);
        
        if ($result) {
            // الإشعار يتم إنشاؤه تلقائياً في User::sendFriendRequest()
            $this->updateFriendshipStatus();
            $this->dispatch('friendRequestSent');
            session()->flash('success', 'تم إرسال طلب الصداقة بنجاح');
        } else {
            session()->flash('error', 'حدث خطأ في إرسال طلب الصداقة');
        }
    }
    
    public function acceptFriendRequest()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        $result = $currentUser->acceptFriendRequest($this->userId);
        
        if ($result) {
            // إنشاء إشعار للمرسل بقبول الطلب - يتم في User::acceptFriendRequest()
            $this->updateFriendshipStatus();
            $this->dispatch('friendRequestAccepted');
            session()->flash('success', 'تم قبول طلب الصداقة');
        } else {
            session()->flash('error', 'حدث خطأ في قبول طلب الصداقة');
        }
    }
    
    public function declineFriendRequest()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        $result = $currentUser->declineFriendRequest($this->userId);
        
        if ($result) {
            $this->updateFriendshipStatus();
            $this->dispatch('friendRequestDeclined');
            session()->flash('success', 'تم رفض طلب الصداقة');
        } else {
            session()->flash('error', 'حدث خطأ في رفض طلب الصداقة');
        }
    }
    
    public function cancelFriendRequest()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        $result = $currentUser->cancelFriendRequest($this->userId);
        
        if ($result) {
            $this->updateFriendshipStatus();
            $this->dispatch('friendRequestCancelled');
            session()->flash('success', 'تم إلغاء طلب الصداقة');
        } else {
            session()->flash('error', 'حدث خطأ في إلغاء طلب الصداقة');
        }
    }
    
    public function removeFriend()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        $result = $currentUser->removeFriend($this->userId);
        
        if ($result) {
            $this->updateFriendshipStatus();
            $this->dispatch('friendRemoved');
            session()->flash('success', 'تم إلغاء الصداقة');
        } else {
            session()->flash('error', 'حدث خطأ في إلغاء الصداقة');
        }
    }
    
    public function blockUser()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        $result = $currentUser->blockUser($this->userId);
        
        if ($result) {
            $this->updateFriendshipStatus();
            $this->dispatch('userBlocked');
            session()->flash('success', 'تم حظر المستخدم بنجاح');
        } else {
            session()->flash('error', 'حدث خطأ في حظر المستخدم');
        }
    }
    
    public function getUserUsernameProperty()
    {
        $user = User::find($this->userId);
        return $user ? $user->username : $this->userId;
    }
    
    public function render()
    {
        return view('livewire.friends.friend-button');
    }
}
