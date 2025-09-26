<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use App\Traits\GeneratesSlug;

class StoreCategory extends Model implements TranslatableContract
{
    use HasFactory, Translatable, GeneratesSlug;

    public $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'slug',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function stores()
    {
        return $this->hasMany(Store::class, 'category_id');
    }
}