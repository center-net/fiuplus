<div class="auth-wrapper" dir="rtl">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="auth-card mx-auto" style="max-width: 450px;">
                    <!-- Header -->
                    <div class="auth-header">
                        <div class="icon-circle">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <h2>{{ __('app.login_title') }}</h2>
                        <p>مرحباً بعودتك! سجل دخولك للمتابعة</p>
                    </div>

                    <!-- Body -->
                    <div class="auth-body">
                        <form wire:submit.prevent="login">
                            <!-- رسالة الخطأ -->
                            @if (session()->has('error'))
                                <div class="alert-modern alert-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <div>{{ session('error') }}</div>
                                </div>
                            @endif

                            <!-- حقل اسم المستخدم أو البريد أو الهاتف -->
                            <div class="form-group-modern">
                                <label for="credential">
                                    <i class="fas fa-user"></i>
                                    {{ __('app.credential_label') }} <span class="required-star">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    class="form-control-modern @error('credential') is-invalid @enderror" 
                                    id="credential" 
                                    wire:model.lazy="credential" 
                                    placeholder="اسم المستخدم، البريد الإلكتروني أو رقم الهاتف"
                                    required 
                                    autofocus
                                >
                                @error('credential') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>

                            <!-- حقل كلمة المرور -->
                            <div class="form-group-modern">
                                <label for="password">
                                    <i class="fas fa-lock"></i>
                                    {{ __('app.password_label') }} <span class="required-star">*</span>
                                </label>
                                <input 
                                    type="password" 
                                    class="form-control-modern @error('password') is-invalid @enderror" 
                                    id="password" 
                                    wire:model.lazy="password" 
                                    placeholder="كلمة المرور"
                                    required
                                >
                                @error('password') 
                                    <div class="invalid-feedback d-block">{{ $message }}</div> 
                                @enderror
                            </div>

                            <!-- تذكرني -->
                            <div class="remember-check">
                                <input 
                                    type="checkbox" 
                                    id="remember" 
                                    wire:model.lazy="remember"
                                >
                                <label for="remember">تذكرني</label>
                            </div>

                            <!-- زر تسجيل الدخول -->
                            <div class="d-grid gap-2 mb-3">
                                <button type="submit" class="btn btn-auth" wire:loading.attr="disabled">
                                    <span wire:loading.remove>
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        تسجيل الدخول
                                    </span>
                                    <span wire:loading>
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        جاري الدخول...
                                    </span>
                                </button>
                            </div>

                            <!-- رابط التسجيل -->
                            <div class="auth-link">
                                <p>
                                    ليس لديك حساب؟ 
                                    <a href="{{ route('register') }}">
                                        أنشئ حسابًا جديدًا
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