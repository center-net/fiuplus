<div class="friend-button-container">
    @if ($friendshipStatus === 'self')
        <!-- لا نعرض أي زر للمستخدم نفسه -->
    @elseif($friendshipStatus === null)
        <!-- إرسال طلب صداقة -->
        <button wire:click="sendFriendRequest" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus"></i> إضافة صديق
        </button>
    @elseif($friendshipStatus === 'pending_sent')
        <!-- طلب مرسل في انتظار الموافقة -->
        <button class="btn btn-secondary btn-sm" disabled>
            <i class="fas fa-clock"></i> طلب مرسل
        </button>
    @elseif($friendshipStatus === 'pending_received')
        <!-- طلب مستلم - إظهار أزرار القبول والرفض -->
        <div class="btn-group" role="group">
            <button wire:click="acceptFriendRequest" class="btn btn-success btn-sm">
                <i class="fas fa-check"></i> قبول
            </button>
            <button wire:click="declineFriendRequest" class="btn btn-danger btn-sm">
                <i class="fas fa-times"></i> رفض
            </button>
        </div>
    @elseif($friendshipStatus === 'accepted')
        <!-- أصدقاء - إظهار زر إلغاء الصداقة -->
        <div class="dropdown">
            <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-check"></i> أصدقاء
            </button>
            <ul class="dropdown-menu">
                <li>
                    <button wire:click="removeFriend" class="dropdown-item text-danger">
                        <i class="fas fa-user-minus"></i> إلغاء الصداقة
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
    </style>
</div>
