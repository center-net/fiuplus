<div class="container py-4" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- بطاقة دعوة صديق -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        دعوة صديق
                    </h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-gift text-primary" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">شارك رابط الدعوة مع أصدقائك</h5>
                        <p class="text-muted">
                            عندما يسجل صديقك باستخدام رابط الدعوة الخاص بك، سيتم إضافته كمرجع لك
                        </p>
                    </div>

                    <!-- رابط الدعوة -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">رابط الدعوة الخاص بك:</label>
                        <div class="input-group">
                            <input 
                                type="text" 
                                class="form-control" 
                                value="{{ $referralLink }}" 
                                id="referralLink"
                                readonly
                            >
                            <button 
                                class="btn btn-primary" 
                                type="button"
                                wire:click="copyLink"
                                onclick="copyToClipboard()"
                            >
                                <i class="fas fa-copy me-1"></i>
                                {{ $copied ? 'تم النسخ!' : 'نسخ' }}
                            </button>
                        </div>
                        <small class="text-muted">انسخ هذا الرابط وشاركه مع أصدقائك</small>
                    </div>

                    <!-- إحصائيات الدعوات -->
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-0">عدد الأصدقاء المدعوين</h6>
                                <h3 class="mb-0 text-primary">{{ $referralsCount }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- مشاركة عبر وسائل التواصل -->
                    <div class="text-center">
                        <p class="fw-bold mb-2">شارك عبر:</p>
                        <div class="btn-group" role="group">
                            <a 
                                href="https://wa.me/?text={{ urlencode('انضم إلى ' . config('app.name') . ' باستخدام رابط الدعوة الخاص بي: ' . $referralLink) }}" 
                                target="_blank"
                                class="btn btn-success"
                            >
                                <i class="fab fa-whatsapp"></i> واتساب
                            </a>
                            <a 
                                href="https://t.me/share/url?url={{ urlencode($referralLink) }}&text={{ urlencode('انضم إلى ' . config('app.name')) }}" 
                                target="_blank"
                                class="btn btn-info text-white"
                            >
                                <i class="fab fa-telegram"></i> تيليجرام
                            </a>
                            <a 
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($referralLink) }}" 
                                target="_blank"
                                class="btn btn-primary"
                            >
                                <i class="fab fa-facebook"></i> فيسبوك
                            </a>
                            <a 
                                href="https://twitter.com/intent/tweet?url={{ urlencode($referralLink) }}&text={{ urlencode('انضم إلى ' . config('app.name')) }}" 
                                target="_blank"
                                class="btn btn-dark"
                            >
                                <i class="fab fa-twitter"></i> تويتر
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- قائمة الأصدقاء المدعوين -->
            @if($referralsCount > 0)
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        الأصدقاء المدعوين (آخر 10)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($referrals as $referral)
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <img 
                                    src="{{ $referral->getAvatarUrl() }}" 
                                    alt="{{ $referral->username }}"
                                    class="rounded-circle me-3"
                                    style="width: 50px; height: 50px; object-fit: cover;"
                                >
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">
                                        <a href="{{ route('profile.show', $referral->username) }}" class="text-decoration-none">
                                            {{ $referral->getDisplayName() }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                            @username($referral->username)
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $referral->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard() {
        const input = document.getElementById('referralLink');
        input.select();
        input.setSelectionRange(0, 99999); // للأجهزة المحمولة
        
        try {
            document.execCommand('copy');
            
            // إظهار رسالة نجاح
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            
            Toast.fire({
                icon: 'success',
                title: 'تم نسخ الرابط بنجاح!'
            });
        } catch (err) {
            console.error('فشل النسخ:', err);
        }
    }

    // إعادة تعيين حالة النسخ بعد 3 ثوانٍ
    Livewire.on('resetCopied', () => {
        setTimeout(() => {
            @this.resetCopied();
        }, 3000);
    });
</script>
@endpush