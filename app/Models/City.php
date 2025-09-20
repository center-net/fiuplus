<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesSlug;

class City extends Model
{
    use HasFactory, GeneratesSlug;

    /**
     * الحقول التي يمكن تعبئتها جماعياً
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'country_id',
        'delivery_cost',
    ];

    /**
     * القيم التي يجب تحويلها إلى أنواع بيانات
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'delivery_cost' => 'decimal:2',
    ];

    /**
     * علاقة المدينة بالدولة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * علاقة المدينة بالقرى التابعة لها
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function villages()
    {
        return $this->hasMany(Village::class);
    }

    /**
     * علاقة المدينة بالمستخدمين
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * الحصول على المسار الكامل للمدينة (الدولة، المدينة)
     * @return string
     */
    public function getFullPath()
    {
        return $this->country ? "{$this->country->name}, {$this->name}" : $this->name;
    }

    /**
     * استعلام للبحث في المدن
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
    }

    /**
     * استعلام للحصول على المدن حسب الدولة
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $countryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCountry($query, $countryId)
    {
        return $query->where('country_id', $countryId);
    }

    /**
     * الحصول على عدد السكان (عدد المستخدمين في المدينة)
     * @return int
     */
    public function getPopulationCount()
    {
        return $this->users()->count();
    }

    /**
     * الحصول على عدد القرى في المدينة
     * @return int
     */
    public function getVillagesCount()
    {
        return $this->villages()->count();
    }
}
