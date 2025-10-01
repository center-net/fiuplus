<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class InviteFriend extends Component
{
    /**
     * رابط الدعوة
     */
    public string $referralLink = '';

    /**
     * عدد الأصدقاء المدعوين
     */
    public int $referralsCount = 0;

    /**
     * قائمة الأصدقاء المدعوين
     */
    public $referrals = [];

    /**
     * رسالة النسخ
     */
    public bool $copied = false;

    /**
     * Mount the component
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->referralLink = $user->getReferralLink();
        $this->referralsCount = $user->getReferralsCount();
        $this->loadReferrals();
    }

    /**
     * تحميل قائمة الأصدقاء المدعوين
     */
    public function loadReferrals(): void
    {
        $this->referrals = Auth::user()
            ->referrals()
            ->with('translations')
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * نسخ رابط الدعوة
     */
    public function copyLink(): void
    {
        $this->copied = true;
        $this->dispatch('linkCopied');
        
        // إعادة تعيين الحالة بعد 3 ثوانٍ
        $this->dispatch('resetCopied');
    }

    /**
     * إعادة تعيين حالة النسخ
     */
    public function resetCopied(): void
    {
        $this->copied = false;
    }

    public function render()
    {
        return view('livewire.profile.invite-friend')
            ->layout('layouts.app', ['title' => __('profile.invite_friend')]);
    }
}