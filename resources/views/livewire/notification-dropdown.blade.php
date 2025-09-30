<div class="notification-dropdown" wire:poll.5s="refreshNotifications">
    <!-- زر الإشعارات -->
    <div class="dropdown">
        <button class="fb-action-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"
            aria-label="{{ __('layout.action_notifications') }}" title="{{ __('layout.action_notifications') }}">
            <i class="fas fa-bell" aria-hidden="true"></i>
            @if ($unreadCount > 0)
                <span class="notification-badge">{{ $unreadCount > 99 ? '99+' : $unreadCount }}</span>
            @endif
        </button>

        <!-- قائمة الإشعارات المنسدلة -->
        <div class="dropdown-menu dropdown-menu-end notification-menu" style="width: 350px;">
            <!-- رأس القائمة -->
            <div class="notification-header">
                <h6 class="mb-0">الإشعارات</h6>
                @if ($unreadCount > 0)
                    <button wire:click="markAllAsRead" class="btn btn-link btn-sm p-0 text-primary">
                        تحديد الكل كمقروء
                    </button>
                @endif
            </div>

            <div class="dropdown-divider"></div>

            <!-- قائمة الإشعارات -->
            <div class="notification-list">
                @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification->read_at ? 'read' : 'unread' }}"
                        wire:click="markAsRead({{ $notification->id }})">

                        <!-- صورة المرسل -->
                        <div class="notification-avatar">
                            @if ($notification->fromUser && $notification->fromUser->avatar)
                                <img src="{{ asset('storage/' . $notification->fromUser->avatar) }}"
                                    alt="{{ $notification->fromUser->name }}" class="rounded-circle">
                            @else
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </div>

                        <!-- محتوى الإشعار -->
                        <div class="notification-content">
                            <div class="notification-title">{{ $notification->title }}</div>
                            <div class="notification-message">{{ $notification->message }}</div>
                            <div class="notification-time">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>

                            <!-- أزرار الإجراءات للطلبات -->
                            @if ($notification->type === 'friend_request' && !$notification->read_at)
                                <div class="notification-actions mt-2">
                                    <button
                                        wire:click.stop="handleNotificationAction({{ $notification->id }}, 'accept')"
                                        class="btn btn-success btn-sm me-2">
                                        <i class="fas fa-check"></i> قبول
                                    </button>
                                    <button
                                        wire:click.stop="handleNotificationAction({{ $notification->id }}, 'decline')"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i> رفض
                                    </button>
                                </div>
                            @endif
                        </div>

                        <!-- مؤشر عدم القراءة -->
                        @if (!$notification->read_at)
                            <div class="unread-indicator"></div>
                        @endif
                    </div>
                @empty
                    <div class="notification-empty">
                        <i class="fas fa-bell-slash text-muted"></i>
                        <p class="text-muted mb-0">لا توجد إشعارات</p>
                    </div>
                @endforelse
            </div>

            @if ($notifications->count() > 0)
                <div class="dropdown-divider"></div>
                <div class="notification-footer">
                    <a href="#" class="text-center d-block text-decoration-none">
                        عرض جميع الإشعارات
                    </a>
                </div>
            @endif
        </div>
    </div>


    <style>
        /* أنماط زر الإشعارات */
        .notification-dropdown .fb-action-btn {
            position: relative;
            background: none;
            border: none;
            color: #6c757d;
            font-size: 1.2rem;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .notification-dropdown .fb-action-btn:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        /* شارة عدد الإشعارات */
        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: bold;
            min-width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }

        /* قائمة الإشعارات */
        .notification-menu {
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 0;
            max-height: 500px;
            overflow-y: auto;
        }

        /* رأس الإشعارات */
        .notification-header {
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f8f9fa;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .notification-header h6 {
            font-weight: 600;
            color: #495057;
        }

        /* قائمة الإشعارات */
        .notification-list {
            max-height: 350px;
            overflow-y: auto;
        }

        /* عنصر الإشعار */
        .notification-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: flex-start;
            cursor: pointer;
            transition: background-color 0.2s ease;
            position: relative;
            border-bottom: 1px solid #f1f3f4;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #f0f8ff;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        /* صورة المرسل */
        .notification-avatar {
            width: 40px;
            height: 40px;
            margin-left: 0.75rem;
            flex-shrink: 0;
        }

        .notification-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        /* محتوى الإشعار */
        .notification-content {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }

        .notification-message {
            color: #6c757d;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            line-height: 1.4;
        }

        .notification-time {
            color: #adb5bd;
            font-size: 0.75rem;
        }

        /* أزرار الإجراءات */
        .notification-actions {
            display: flex;
            gap: 0.5rem;
        }

        .notification-actions .btn {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        /* مؤشر عدم القراءة */
        .unread-indicator {
            position: absolute;
            top: 50%;
            right: 0.5rem;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: #007bff;
            border-radius: 50%;
        }

        /* حالة فارغة */
        .notification-empty {
            padding: 2rem 1rem;
            text-align: center;
        }

        .notification-empty i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        /* تذييل الإشعارات */
        .notification-footer {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-radius: 0 0 0.5rem 0.5rem;
        }

        .notification-footer a {
            color: #007bff;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .notification-footer a:hover {
            color: #0056b3;
        }

        /* تحسينات للشاشات الصغيرة */
        @media (max-width: 576px) {
            .notification-menu {
                width: 300px !important;
                margin-left: -250px;
            }

            .notification-item {
                padding: 0.5rem 0.75rem;
            }

            .notification-avatar {
                width: 35px;
                height: 35px;
                margin-left: 0.5rem;
            }
        }

        /* تحسين شريط التمرير */
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }

        .notification-list::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .notification-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .notification-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</div>
