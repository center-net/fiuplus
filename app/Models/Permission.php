<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Permission extends Model implements TranslatableContract
{
    use HasFactory, Translatable;

    public $translatedAttributes = ['name', 'table_name'];

    protected $fillable = ['key'];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function generateFor(string $table): void
    {
        $labels = [
            'browse' => ['ar' => "تصفح {$table}", 'en' => "Browse {$table}"],
            'read' => ['ar' => "قراءة {$table}", 'en' => "Read {$table}"],
            'edit' => ['ar' => "تعديل {$table}", 'en' => "Edit {$table}"],
            'add' => ['ar' => "اضافة {$table}", 'en' => "Add {$table}"],
            'delete' => ['ar' => "حذف {$table}", 'en' => "Delete {$table}"],
            'restore' => ['ar' => "استرجاع {$table}", 'en' => "Restore {$table}"],
            'forceDelete' => ['ar' => "حذف نهائي {$table}", 'en' => "Force delete {$table}"],
        ];

        $createOne = function (string $key, array $labelByLocale) use ($table) {
            $perm = self::firstOrCreate(['key' => $key]);
            foreach (['ar', 'en'] as $loc) {
                $perm->translateOrNew($loc)->name = $labelByLocale[$loc] ?? $labelByLocale['ar'];
                $perm->translateOrNew($loc)->table_name = $table;
            }
            $perm->save();
        };

        $createOne("browse_{$table}", $labels['browse']);
        $createOne("read_{$table}", $labels['read']);
        $createOne("edit_{$table}", $labels['edit']);
        $createOne("add_{$table}", $labels['add']);
        $createOne("delete_{$table}", $labels['delete']);
        $createOne("restore_{$table}", $labels['restore']);
        $createOne("forceDelete_{$table}", $labels['forceDelete']);
    }

    public static function removeFrom(string $table): void
    {
        self::whereHas('translations', function ($q) use ($table) {
            $q->where('table_name', $table);
        })->delete();
    }
}
