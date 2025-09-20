<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesSlug;

class Village extends Model
{
    use HasFactory, GeneratesSlug;

    /**
     * الحقول التي يمكن تعبئتها جماعياً
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'city_id',
        'population',
        'area',
        'latitude',
        'longitude'
    ];

    /**
     * القيم التي يجب تحويلها إلى أنواع بيانات
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'population' => 'integer',
        'area' => 'float',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * علاقة القرية بالمدينة
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * علاقة القرية بالمستخدمين
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * علاقة القرية بالدولة من خلال المدينة
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function country()
    {
        return $this->hasOneThrough(
            Country::class,
            City::class,
            'id',
            'id',
            'city_id',
            'country_id'
        );
    }

    /**
     * الحصول على المسار الكامل للقرية (الدولة، المدينة، القرية)
     * @return string
     */
    public function getFullPath()
    {
        $path = [$this->name];
        if ($this->city) {
            array_unshift($path, $this->city->name);
            if ($this->city->country) {
                array_unshift($path, $this->city->country->name);
            }
        }
        return implode(', ', $path);
    }

    /**
     * استعلام للبحث في القرى
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
     * استعلام للحصول على القرى حسب المدينة
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $cityId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCity($query, $cityId)
    {
        return $query->where('city_id', $cityId);
    }

    /**
     * الحصول على إحداثيات القرية
     * @return array|null
     */
    public function getCoordinates()
    {
        if ($this->latitude && $this->longitude) {
            return [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }
        return null;
    }

    /**
     * الحصول على كثافة السكان في القرية
     * @return float|null
     */
    public function getPopulationDensity()
    {
        if ($this->population && $this->area) {
            return round($this->population / $this->area, 2);
        }
        return null;
    }

    /**
     * الحصول على عدد المستخدمين في القرية
     * @return int
     */
    public function getUsersCount()
    {
        return $this->users()->count();
    }
}
