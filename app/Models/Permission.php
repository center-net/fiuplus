<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key', 'table_name'];

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function generateFor($table_name)
    {
        self::firstOrCreate(['name' => 'تصفح ' . $table_name, 'key' => 'browse_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'قراءة ' . $table_name, 'key' => 'read_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'تعديل ' . $table_name, 'key' => 'edit_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'اضافة ' . $table_name, 'key' => 'add_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'حذف ' . $table_name, 'key' => 'delete_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'استرجاع ' . $table_name, 'key' => 'restore_' . $table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['name' => 'حذف نهائي ' . $table_name, 'key' => 'forceDelete_' . $table_name, 'table_name' => $table_name]);
    }

    public static function removeFrom($table_name)
    {
        self::where(['table_name' => $table_name])->delete();
    }
}
