<div class="auth-wrapper" dir="rtl">
    <div >
        <div class=" justify-content-center">
            <div class="col-12">
                <div class="auth-card">
                    <!-- Header -->
                    <div class="auth-header">
                        <div class="icon-circle">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h2>إنشاء حساب جديد</h2>
                        <p>انضم إلينا وابدأ رحلتك الآن</p>
                    </div>

                    <!-- Body -->
                    <div class="auth-body">
                        <!-- رسالة المرجع -->
                        @if($hasReferralInUrl && $referrerValid)
                            <div class="alert-modern alert-success">
                                <i class="fas fa-user-friends"></i>
                                <div>
                                    <strong>تمت دعوتك بواسطة:</strong> {{ $referrerName }}
                                </div>
                            </div>
                        @elseif($hasReferralInUrl && !$referrerValid)
                            <div class="alert-modern alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <strong>تنبيه:</strong> اسم المستخدم المرجعي غير صحيح
                                </div>
                            </div>
                        @endif

                        <form wire:submit.prevent="register">
                            <!-- اسم المستخدم والحقل الذكي في سطر واحد -->
                            <div class="row">
                                <!-- اسم المستخدم -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="username">
                                            <i class="fas fa-user"></i>
                                            اسم المستخدم <span class="required-star">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control-modern @error('username') is-invalid @enderror" 
                                            id="username"
                                            wire:model.defer="username"
                                            placeholder="اسم المستخدم"
                                            required
                                        >
                                        @error('username')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="helper-text">أحرف إنجليزية وأرقام فقط</small>
                                    </div>
                                </div>

                                <!-- الحقل الذكي (رقم هاتف أو بريد إلكتروني) -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="contact_info">
                                            <i class="fas fa-id-card"></i>
                                            الهاتف أو البريد <span class="required-star">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control-modern @error('contact_info') is-invalid @enderror" 
                                            id="contact_info"
                                            wire:model.lazy="contact_info"
                                            placeholder="هاتف أو بريد إلكتروني"
                                            required
                                        >
                                        @error('contact_info')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- عرض نوع البيانات المدخلة -->
                                        @if($phone)
                                            <div class="feedback-badge success">
                                                <i class="fas fa-check-circle"></i>
                                                هاتف: {{ $phone }}
                                            </div>
                                        @elseif($email)
                                            <div class="feedback-badge success">
                                                <i class="fas fa-check-circle"></i>
                                                بريد: {{ $email }}
                                            </div>
                                        @else
                                            <div class="feedback-badge info">
                                                <i class="fas fa-info-circle"></i>
                                                10 أرقام أو بريد صحيح
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- كلمة المرور وتأكيدها في سطر واحد -->
                            <div class="row">
                                <!-- كلمة المرور -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="password">
                                            <i class="fas fa-lock"></i>
                                            كلمة المرور <span class="required-star">*</span>
                                        </label>
                                        <input 
                                            type="password" 
                                            class="form-control-modern @error('password') is-invalid @enderror" 
                                            id="password"
                                            wire:model.defer="password"
                                            placeholder="8 أحرف على الأقل"
                                            required
                                        >
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- تأكيد كلمة المرور -->
                                <div class="col-md-6">
                                    <div class="form-group-modern">
                                        <label for="password_confirmation">
                                            <i class="fas fa-lock"></i>
                                            تأكيد كلمة المرور <span class="required-star">*</span>
                                        </label>
                                        <input 
                                            type="password" 
                                            class="form-control-modern" 
                                            id="password_confirmation"
                                            wire:model.defer="password_confirmation"
                                            placeholder="أعد إدخال كلمة المرور"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- الاسم المرجعي (يظهر فقط إذا لم يكن في الرابط) -->
                            @if(!$hasReferralInUrl)
                            <div class="form-group-modern">
                                <label for="referred_by">
                                    <i class="fas fa-user-friends"></i>
                                    اسم المستخدم المرجعي (اختياري)
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control-modern @error('referred_by') is-invalid @enderror" 
                                    id="referred_by"
                                    wire:model.lazy="referred_by"
                                    placeholder="اسم المستخدم الذي دعاك"
                                >
                                @error('referred_by')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @if($referrerValid)
                                    <div class="feedback-badge success">
                                        <i class="fas fa-check-circle"></i>
                                        المرجع: {{ $referrerName }}
                                    </div>
                                @endif
                                <small class="helper-text">إذا تمت دعوتك من قبل صديق، أدخل اسم المستخدم الخاص به</small>
                            </div>
                            @endif

                            <!-- زر التسجيل -->
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-auth" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-user-plus me-2"></i>
                                        إنشاء حساب
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري التسجيل...
                                    </span>
                                </button>
                            </div>

                            <!-- رابط تسجيل الدخول -->
                            <div class="auth-link">
                                <p>
                                    لديك حساب بالفعل؟ 
                                    <a href="{{ route('login') }}">
                                        تسجيل الدخول
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>