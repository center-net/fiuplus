<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class CompleteProfile extends Component
{
    public $showModal = false;
    public $name = '';
    public $email = '';
    public $send_friend_request = false;
    public $referrer_id = null;
    public $referrer_name = '';
    public $notification_sent = false;

    protected $rules = [
        'name' => 'required|string|min:3|max:100',
        'email' => 'required|email|unique:users,email',
    ];

    protected $messages = [
        'name.required' => 'الاسم الشخصي مطلوب',
        'name.min' => 'الاسم الشخصي يجب أن يكون 3 أحرف على الأقل',
        'email.required' => 'البريد الإلكتروني مطلوب',
        'email.email' => 'البريد الإلكتروني غير صحيح',
        'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
    ];

    public function mount()
    {
        $user = Auth::user();
        
        // التحقق من وجود اسم للمستخدم
        if (!$user->name) {
            $this->showModal = true;
            
            // التحقق من وجود مرجع
            if ($user->referred_by) {
                $referrer = \App\Models\User::where('username', $user->referred_by)->first();
                if ($referrer) {
                    $this->referrer_id = $referrer->id;
                    $this->referrer_name = $referrer->getDisplayName();
                    
                    // إرسال إشعار للمرجع عند أول تحميل (مرة واحدة فقط)
                    if (!$this->notification_sent) {
                        $this->sendReferralNotification();
                        $this->notification_sent = true;
                    }
                }
            }
            
            // إذا كان لديه بريد إلكتروني، نملأه تلقائياً
            if ($user->email) {
                $this->email = $user->email;
            }
        }
    }

    public function saveAndFinish()
    {
        // تخصيص قواعد التحقق
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
        
        $this->validate($rules);

        $user = Auth::user();

        // تحديث اسم المستخدم (يتم حفظه في user_translations)
        $user->name = $this->name;
        $user->save();

        // تحديث البريد الإلكتروني
        $user->update(['email' => $this->email]);

        // إرسال طلب صداقة إذا تم اختياره
        if ($this->send_friend_request && $this->referrer_id) {
            $this->sendFriendRequest();
        }

        $this->showModal = false;
        
        // إرسال حدث لإغلاق الـ Modal
        $this->dispatch('profileCompleted');
        
        session()->flash('success', 'تم حفظ بياناتك بنجاح!');
        
        // إعادة تحميل الصفحة
        return redirect()->route('admin.dashboard');
    }

    public function continueToProfile()
    {
        // تخصيص قواعد التحقق
        $rules = [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
        ];
        
        $this->validate($rules);

        $user = Auth::user();

        // تحديث اسم المستخدم (يتم حفظه في user_translations)
        $user->name = $this->name;
        $user->save();

        // تحديث البريد الإلكتروني
        $user->update(['email' => $this->email]);

        // إرسال طلب صداقة إذا تم اختياره
        if ($this->send_friend_request && $this->referrer_id) {
            $this->sendFriendRequest();
        }

        $this->showModal = false;
        
        // إرسال حدث لإغلاق modal إكمال الملف الشخصي وفتح modal التعديل
        $this->dispatch('profileCompleted');
        $this->dispatch('openEditProfileModal');
        
        session()->flash('success', 'تم حفظ بياناتك الأساسية! يمكنك الآن إكمال باقي معلومات ملفك الشخصي.');
    }

    protected function sendFriendRequest()
    {
        $user = Auth::user();
        $referrer = \App\Models\User::find($this->referrer_id);

        if ($referrer) {
            // التحقق من عدم وجود طلب صداقة سابق
            $existingFriendship = \App\Models\Friendship::where(function ($query) use ($user, $referrer) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $referrer->id);
            })->orWhere(function ($query) use ($user, $referrer) {
                $query->where('sender_id', $referrer->id)
                      ->where('receiver_id', $user->id);
            })->first();

            if (!$existingFriendship) {
                // إنشاء طلب صداقة
                \App\Models\Friendship::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $referrer->id,
                    'status' => 'pending',
                ]);

                // إرسال إشعار
                Notification::createFriendRequest($referrer, $user);
            }
        }
    }

    protected function sendReferralNotification()
    {
        $user = Auth::user();
        $referrer = \App\Models\User::find($this->referrer_id);

        if ($referrer) {
            // التحقق من عدم وجود إشعار سابق
            $existingNotification = Notification::where('user_id', $referrer->id)
                ->where('from_user_id', $user->id)
                ->where('type', 'referral_registered')
                ->first();

            if (!$existingNotification) {
                Notification::create([
                    'user_id' => $referrer->id,
                    'from_user_id' => $user->id,
                    'type' => 'referral_registered',
                    'title' => 'تسجيل مستخدم جديد',
                    'message' => 'قام ' . $user->getDisplayName() . ' بالتسجيل باستخدام الاسم المرجعي الخاص بك',
                    'data' => [
                        'new_user_id' => $user->id,
                        'new_user_name' => $user->getDisplayName(),
                        'new_user_avatar' => $user->getAvatarUrl(),
                    ],
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.profile.complete-profile');
    }
}