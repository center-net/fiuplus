<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\GeneratesSlug;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Country extends Model implements TranslatableContract
{
    use HasFactory, GeneratesSlug, Translatable;

    public $translatedAttributes = ['name'];

    /**
     * Override slug max length for countries (10 chars as required).
     */
    protected function slugMax(): int
    {
        return 10;
    }

    /**
     * الحقول التي يمكن تعبئتها جماعياً
     * @var array<string>
     */
    protected $fillable = [
        'slug',
        'iso3',
    ];

    /**
     * القيم التي يجب تحويلها إلى أنواع بيانات
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * تحويل رمز ISO3 إلى حروف كبيرة
     * @param string $value
     */
    public function setIso3Attribute($value)
    {
        $this->attributes['iso3'] = strtoupper($value);
    }

    /**
     * علاقة الدولة بالمدن
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * علاقة الدولة بالقرى من خلال المدن
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function villages()
    {
        return $this->hasManyThrough(Village::class, City::class);
    }

    /**
     * علاقة الدولة بالمستخدمين
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * الحصول على رابط علم الدولة
     * @return string
     */
    public function getFlagUrl()
    {
        return $this->flag ? asset('storage/' . $this->flag) : 
            "https://flagcdn.com/w80/" . strtolower($this->slug) . ".png";
    }

    /**
     * الحصول على رقم الهاتف بالصيغة الدولية
     * @param string $number
     * @return string
     */
    public function formatPhoneNumber($number)
    {
        return "+{$this->calling_code}" . ltrim($number, '0');
    }

    /**
     * استعلام للبحث في الدول
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->orWhere('slug', 'like', "%{$search}%")
              ->orWhere('iso3', 'like', "%{$search}%")
              ->orWhereHas('translations', function ($t) use ($search) {
                  $t->where('name', 'like', "%{$search}%");
              });
        });
    }

    /**
     * الحصول على عدد السكان (المستخدمين) في الدولة
     * @return int
     */
    public function getPopulationCount()
    {
        return $this->users()->count();
    }

    /**
     * الحصول على عدد المدن في الدولة
     * @return int
     */
    public function getCitiesCount()
    {
        return $this->cities()->count();
    }

    /**
     * الحصول على عدد القرى في الدولة
     * @return int
     */
    public function getVillagesCount()
    {
        return $this->villages()->count();
    }
}
