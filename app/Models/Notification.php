<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    /**
     * العلاقة مع المستخدم المستقبل للإشعار
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * العلاقة مع المستخدم المرسل للإشعار
     */
    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * تحديد الإشعار كمقروء
     */
    public function markAsRead(): bool
    {
        return $this->update(['read_at' => now()]);
    }

    /**
     * التحقق من قراءة الإشعار
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * التحقق من عدم قراءة الإشعار
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * إنشاء إشعار طلب صداقة
     */
    public static function createFriendRequest(User $receiver, User $sender): self
    {
        return self::create([
            'user_id' => $receiver->id,
            'from_user_id' => $sender->id,
            'type' => 'friend_request',
            'title' => __('notifications.friend_request_title'),
            'message' => __('notifications.friend_request_message', ['name' => $sender->getDisplayName()]),
            'data' => [
                'sender_id' => $sender->id,
                'sender_name' => $sender->getDisplayName(),
                'sender_avatar' => $sender->getAvatarUrl(),
            ],
        ]);
    }

    /**
     * إنشاء إشعار قبول الصداقة
     */
    public static function createFriendAccepted(User $receiver, User $accepter): self
    {
        return self::create([
            'user_id' => $receiver->id,
            'from_user_id' => $accepter->id,
            'type' => 'friend_accepted',
            'title' => __('notifications.friend_accepted_title'),
            'message' => __('notifications.friend_accepted_message', ['name' => $accepter->getDisplayName()]),
            'data' => [
                'accepter_id' => $accepter->id,
                'accepter_name' => $accepter->getDisplayName(),
                'accepter_avatar' => $accepter->getAvatarUrl(),
            ],
        ]);
    }

    /**
     * الحصول على الإشعارات غير المقروءة لمستخدم
     */
    public static function getUnreadForUser(int $userId)
    {
        return self::where('user_id', $userId)
            ->whereNull('read_at')
            ->with('fromUser')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * عدد الإشعارات غير المقروءة لمستخدم
     */
    public static function getUnreadCountForUser(int $userId): int
    {
        return self::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }
}
