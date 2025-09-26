<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreCategoryTranslation extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name', 'description'
    ];
}