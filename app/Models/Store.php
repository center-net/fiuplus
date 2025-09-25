<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesSlug;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Store extends Model implements TranslatableContract
{
    use HasFactory, GeneratesSlug, Translatable;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'slug',
        'user_id',
        'logo',
        'phone',
        'email',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience helper to display
     */
    public function getDisplayName(): string
    {
        return $this->name ?: $this->slug;
    }

    /**
     * Scope search by slug or translated name
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('slug', 'like', "%{$search}%")
              ->orWhereHas('translations', function ($t) use ($search) {
                  $t->where('name', 'like', "%{$search}%");
              });
        });
    }
}