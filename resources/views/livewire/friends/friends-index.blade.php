<div class="friends-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @livewire('friends.friends-list')
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* تحسينات إضافية لصفحة الأصدقاء */
.friends-page {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* تحسين شكل البطاقات */
.friends-container .user-card {
    background: #fff;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.friends-container .user-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

/* تحسين التبويبات */
.friends-tabs .nav-link {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: none;
}

.friends-tabs .nav-link.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #fff;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* تحسين شريط البحث */
.search-container .form-control {
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    border: none;
}

.search-container .input-group-text {
    background: #fff;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* تحسين الحالة الفارغة */
.empty-state {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin: 2rem 0;
}

/* تحسين أزرار الصداقة */
.friend-button-container .btn {
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.friend-button-container .btn:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* تحسين الشارات */
.friends-tabs .badge {
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

/* تأثيرات الحركة */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.user-card {
    animation: fadeInUp 0.6s ease-out;
}

.user-card:nth-child(1) { animation-delay: 0.1s; }
.user-card:nth-child(2) { animation-delay: 0.2s; }
.user-card:nth-child(3) { animation-delay: 0.3s; }
.user-card:nth-child(4) { animation-delay: 0.4s; }
.user-card:nth-child(5) { animation-delay: 0.5s; }
.user-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // تحسين تجربة المستخدم مع الإشعارات
    window.addEventListener('friend-action-success', function(e) {
        // إظهار إشعار نجاح
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'تم بنجاح!',
                text: e.detail.message || 'تم تنفيذ العملية بنجاح',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });
    
    window.addEventListener('friend-action-error', function(e) {
        // إظهار إشعار خطأ
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'حدث خطأ!',
                text: e.detail.message || 'حدث خطأ أثناء تنفيذ العملية',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    });
});
</script>
@endpush