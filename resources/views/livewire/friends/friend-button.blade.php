<div class="friend-button-container">
    @if ($friendshipStatus === 'self')
        <!-- لا نعرض أي زر للمستخدم نفسه -->
    @elseif($friendshipStatus === null || $friendshipStatus === 'none')
        <!-- إرسال طلب صداقة -->
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('profile.show', ['user' => $this->userUsername]) }}" class="btn btn-outline-primary btn-sm" title="الملف الشخصي">
                <i class="fas fa-user"></i>
            </a>
            <button wire:click="sendFriendRequest" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="sendFriendRequest">
                    <i class="fas fa-user-plus"></i> إضافة صديق
                </span>
                <span wire:loading wire:target="sendFriendRequest">
                    <i class="fas fa-spinner fa-spin"></i> جاري الإرسال...
                </span>
            </button>
        </div>
    @elseif($friendshipStatus === 'pending_sent')
        <!-- طلب مرسل في انتظار الموافقة -->
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('profile.show', ['user' => $this->userUsername]) }}" class="btn btn-outline-primary btn-sm" title="الملف الشخصي">
                <i class="fas fa-user"></i>
            </a>
            <div class="btn-group" role="group">
                <button class="btn btn-secondary btn-sm" disabled>
                    <i class="fas fa-clock"></i> طلب مرسل
                </button>
                <button wire:click="cancelFriendRequest" class="btn btn-outline-secondary btn-sm" title="إلغاء الطلب">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @elseif($friendshipStatus === 'pending_received')
        <!-- طلب مستلم - إظهار أزرار القبول والرفض والحظر -->
        <div class="d-flex gap-2 align-items-center">
            <a href="{{ route('profile.show', ['user' => $this->userUsername]) }}" class="btn btn-outline-primary btn-sm" title="الملف الشخصي">
                <i class="fas fa-user"></i>
            </a>
            <div class="btn-group" role="group">
                <button wire:click="acceptFriendRequest" class="btn btn-success btn-sm">
                    <i class="fas fa-check"></i> قبول
                </button>
                <button wire:click="declineFriendRequest" class="btn btn-danger btn-sm">
                    <i class="fas fa-times"></i> رفض
                </button>
                <button wire:click="blockUser" class="btn btn-dark btn-sm" 
                        onclick="return confirm('هل أنت متأكد من حظر هذا المستخدم؟ لن يتمكن من مشاهدة ملفك الشخصي أو إرسال طلبات صداقة أو رسائل لك.')">
                    <i class="fas fa-ban"></i> حظر
                </button>
            </div>
        </div>
    @elseif($friendshipStatus === 'accepted')
        <!-- أصدقاء - إظهار زر إلغاء الصداقة والحظر -->
        <div class="dropdown">
            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-check"></i> أصدقاء
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('profile.show', ['user' => $this->userUsername]) }}" class="dropdown-item">
                        <i class="fas fa-user"></i> الملف الشخصي
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button wire:click="removeFriend" class="dropdown-item text-danger">
                        <i class="fas fa-user-minus"></i> إلغاء الصداقة
                    </button>
                </li>
                <li>
                    <button wire:click="blockUser" class="dropdown-item text-dark"
                            onclick="return confirm('هل أنت متأكد من حظر هذا المستخدم؟ لن يتمكن من مشاهدة ملفك الشخصي أو إرسال طلبات صداقة أو رسائل لك.')">
                        <i class="fas fa-ban"></i> حظر المستخدم
                    </button>
                </li>
            </ul>
        </div>
    @elseif($friendshipStatus === 'declined')
        <!-- طلب مرفوض - يمكن إرسال طلب جديد -->
        <button wire:click="sendFriendRequest" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-user-plus"></i> إرسال طلب مرة أخرى
        </button>
    @elseif($friendshipStatus === 'blocked')
        <!-- محظور -->
        <button class="btn btn-dark btn-sm" disabled>
            <i class="fas fa-ban"></i> محظور
        </button>
    @endif

    <!-- عرض رسائل النجاح والخطأ -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <style>
        .friend-button-container {
            display: inline-block;
        }

        .friend-button-container .btn {
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .friend-button-container .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .friend-button-container .btn-group .btn {
            border-radius: 0;
        }

        .friend-button-container .btn-group .btn:first-child {
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
        }

        .friend-button-container .btn-group .btn:last-child {
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .friend-button-container .alert {
            font-size: 0.875rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0;
        }
        
        /* دعم gap للمتصفحات القديمة */
        .friend-button-container .d-flex.gap-2 > * {
            margin-left: 0.5rem;
        }
        
        .friend-button-container .d-flex.gap-2 > *:first-child {
            margin-left: 0;
        }
        
        /* تحسين زر الملف الشخصي */
        .friend-button-container .btn-outline-primary {
            border-width: 2px;
        }
        
        .friend-button-container .btn-outline-primary:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>
</div>
