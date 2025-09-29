<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'profile_visibility',
        'preferred_locale',
    ];

    /**
     * علاقة الإعدادات بالمستخدم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}