<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    /**
     * العلاقة مع المرسل
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * العلاقة مع المستقبل
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * قبول طلب الصداقة
     */
    public function accept(): bool
    {
        return $this->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);
    }

    /**
     * رفض طلب الصداقة
     */
    public function decline(): bool
    {
        return $this->update(['status' => 'declined']);
    }

    /**
     * حظر المستخدم
     */
    public function block(): bool
    {
        return $this->update(['status' => 'blocked']);
    }

    /**
     * التحقق من حالة الصداقة
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isDeclined(): bool
    {
        return $this->status === 'declined';
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * البحث عن صداقة بين مستخدمين
     */
    public static function findBetweenUsers(int $userId1, int $userId2): ?self
    {
        return self::where(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($query) use ($userId1, $userId2) {
            $query->where('sender_id', $userId2)->where('receiver_id', $userId1);
        })->first();
    }

    /**
     * الحصول على الطرف الآخر في الصداقة
     */
    public function getOtherUser(int $currentUserId): User
    {
        return $this->sender_id === $currentUserId ? $this->receiver : $this->sender;
    }
}
