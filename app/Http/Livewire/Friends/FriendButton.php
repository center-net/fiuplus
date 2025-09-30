<?php

namespace App\Http\Livewire\Friends;

use Livewire\Component;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class FriendButton extends Component
{
    public $targetUser;
    public $friendshipStatus;
    
    public function mount($userId)
    {
        $this->targetUser = User::findOrFail($userId);
        $this->updateFriendshipStatus();
    }
    
    public function updateFriendshipStatus()
    {
        if (!Auth::check()) {
            $this->friendshipStatus = null;
            return;
        }
        
        $currentUser = Auth::user();
        
        if ($currentUser->id === $this->targetUser->id) {
            $this->friendshipStatus = 'self';
            return;
        }
        
        $this->friendshipStatus = $currentUser->getFriendshipStatus($this->targetUser->id);
    }
    
    public function sendFriendRequest()
    {
        if (!Auth::check()) {
            return;
        }
        
        $currentUser = Auth::user();
        
        if ($currentUser->id === $this->targetUser->id) {
            return;
        }
        
        $result = $currentUser->sendFriendRequest($this->targetUser->id);
        
        if ($result) {
            // إنشاء إشعار للمستخدم المستهدف
            Notification::createFriendRequest($this->targetUser, $currentUser);
            
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
        $result = $currentUser->acceptFriendRequest($this->targetUser->id);
        
        if ($result) {
            // إنشاء إشعار للمرسل بقبول الطلب
            Notification::createFriendAccepted($this->targetUser, $currentUser);
            
            $this->updateFriendshipStatus();
            $this->dispatch('friendRequestAccepted');
            session()->flash('success', 'friend_accepted');
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
        $result = $currentUser->declineFriendRequest($this->targetUser->id);
        
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
        $result = $currentUser->cancelFriendRequest($this->targetUser->id);
        
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
        $result = $currentUser->removeFriend($this->targetUser->id);
        
        if ($result) {
            $this->updateFriendshipStatus();
            $this->dispatch('friendRemoved');
            session()->flash('success', 'تم إلغاء الصداقة');
        } else {
            session()->flash('error', 'حدث خطأ في إلغاء الصداقة');
        }
    }
    
    public function render()
    {
        return view('livewire.friends.friend-button');
    }
}
