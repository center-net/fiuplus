@extends('layouts.app')

@section('content')
<div class="container py-5" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-at me-2"></i>
                        اختبار ميزة تحويل @username إلى روابط
                    </h4>
                </div>
                <div class="card-body">
                    <h5 class="mb-3">أمثلة على الاستخدام:</h5>
                    
                    <!-- مثال 1: استخدام linkify_mentions -->
                    <div class="alert alert-info mb-4">
                        <h6 class="fw-bold">مثال 1: استخدام linkify_mentions()</h6>
                        <p class="mb-2"><strong>النص الأصلي:</strong></p>
                        <code>مرحباً @{{ auth()->user()->username }} كيف حالك؟</code>
                        <p class="mb-2 mt-3"><strong>النتيجة:</strong></p>
                        <div class="p-3 bg-white rounded">
                            {!! linkify_mentions('مرحباً @' . auth()->user()->username . ' كيف حالك؟') !!}
                        </div>
                    </div>

                    <!-- مثال 2: استخدام @mentions directive -->
                    <div class="alert alert-success mb-4">
                        <h6 class="fw-bold">مثال 2: استخدام @mentions directive</h6>
                        <p class="mb-2"><strong>الكود:</strong></p>
                        <code>@mentions("شكراً @{{ auth()->user()->username }} على المساعدة!")</code>
                        <p class="mb-2 mt-3"><strong>النتيجة:</strong></p>
                        <div class="p-3 bg-white rounded">
                            @mentions("شكراً @" . auth()->user()->username . " على المساعدة!")
                        </div>
                    </div>

                    <!-- مثال 3: استخدام format_username -->
                    <div class="alert alert-warning mb-4">
                        <h6 class="fw-bold">مثال 3: استخدام format_username()</h6>
                        <p class="mb-2"><strong>مع @ (افتراضي):</strong></p>
                        <div class="p-3 bg-white rounded mb-2">
                            {!! format_username(auth()->user()->username) !!}
                        </div>
                        <p class="mb-2 mt-3"><strong>بدون @:</strong></p>
                        <div class="p-3 bg-white rounded">
                            {!! format_username(auth()->user()->username, false) !!}
                        </div>
                    </div>

                    <!-- مثال 4: استخدام @username directive -->
                    <div class="alert alert-danger mb-4">
                        <h6 class="fw-bold">مثال 4: استخدام @username directive</h6>
                        <p class="mb-2"><strong>الكود:</strong></p>
                        <code>@username(auth()->user()->username)</code>
                        <p class="mb-2 mt-3"><strong>النتيجة:</strong></p>
                        <div class="p-3 bg-white rounded">
                            @username(auth()->user()->username)
                        </div>
                    </div>

                    <!-- مثال 5: نص مع عدة mentions -->
                    <div class="alert alert-primary mb-4">
                        <h6 class="fw-bold">مثال 5: نص مع عدة mentions</h6>
                        <p class="mb-2"><strong>النص:</strong></p>
                        <code>مرحباً @user1 و @user2 و @user3 كيف حالكم؟</code>
                        <p class="mb-2 mt-3"><strong>النتيجة:</strong></p>
                        <div class="p-3 bg-white rounded">
                            {!! linkify_mentions('مرحباً @user1 و @user2 و @user3 كيف حالكم؟') !!}
                        </div>
                    </div>

                    <!-- مثال 6: في سياق منشور -->
                    <div class="alert alert-secondary mb-0">
                        <h6 class="fw-bold">مثال 6: في سياق منشور</h6>
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ auth()->user()->getAvatarUrl() }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <strong>@username(auth()->user()->username)</strong>
                                        <br>
                                        <small class="text-muted">منذ 5 دقائق</small>
                                    </div>
                                </div>
                                <div class="post-content">
                                    @mentions("شكراً @" . auth()->user()->username . " على الدعوة! سأكون سعيداً بالانضمام إلى المجموعة مع @admin و @moderator")
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('invite.friend') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة إلى صفحة دعوة الأصدقاء
                </a>
            </div>
        </div>
    </div>
</div>
@endsection