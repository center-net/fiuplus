<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Storage;

class Profile extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'cover_photo',
        'date_of_birth',
    ];

    /**
     * Translatable attributes.
     *
     * @var array<int, string>
     */
    public $translatedAttributes = [
        'bio',
        'job_title',
        'education',
    ];

    /**
     * Casts for attributes.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * علاقة الملف الشخصي بالمستخدم.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على رابط صورة الغلاف.
     */

        public function getCoverPhotoUrl()
    {
        if ($this->cover_photo) {
            return asset('storage/' . $this->cover_photo);
        }
        
        // إذا لم يكن هناك صورة، نستخدم صورة افتراضية من UI Avatars
        $name = urlencode($this->getDisplayName());
        return "https://ui-avatars.com/api/?name={$name}&background=random";
    }
}