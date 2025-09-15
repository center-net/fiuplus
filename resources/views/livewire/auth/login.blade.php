<div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">تسجيل الدخول</h3>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="login">
                        @if (session()->has('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="credential" class="form-label">اسم المستخدم أو البريد الإلكتروني أو الهاتف</label>
                            <input type="text" class="form-control @error('credential') is-invalid @enderror" id="credential" wire:model.lazy="credential" required autofocus>
                            @error('credential') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" wire:model.lazy="password" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" wire:model.lazy="remember">
                            <label class="form-check-label" for="remember">تذكرني</label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>تسجيل الدخول</span>
                                <span wire:loading>جاري الدخول...</span>
                            </button>
                        </div>

                        <div class="mt-3 text-center">
                            <span>ليس لديك حساب؟</span>
                            {{-- <a href="{{ route('register') }}">أنشئ حسابًا جديدًا</a> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>