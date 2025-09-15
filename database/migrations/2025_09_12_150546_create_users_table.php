<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // المفتاح الرئيسي
            $table->id();
            
            // معلومات الحساب الأساسية
            $table->string('username')->unique()->index()->comment('اسم المستخدم الفريد');
            $table->string('email')->unique()->comment('البريد الإلكتروني');
            $table->string('password')->comment('كلمة المرور المشفرة');
            
            // المعلومات الشخصية
            $table->string('name')->comment('الاسم الكامل');
            $table->string('avatar')->nullable()->comment('صورة المستخدم');
            $table->string('phone')->unique()->comment('رقم الهاتف');
            
            // العلاقات
            $table->foreignId('role_id')
                 ->comment('دور المستخدم')
                 ->constrained('roles')
                 ->onUpdate('cascade')
                 ->restrictOnDelete();
                 
            $table->foreignId('country_id')
                 ->nullable()
                 ->comment('الدولة')
                 ->constrained('countries')
                 ->cascadeOnUpdate()
                 ->nullOnDelete();
                 
            $table->foreignId('city_id')
                 ->nullable()
                 ->comment('المدينة')
                 ->constrained('cities')
                 ->cascadeOnUpdate()
                 ->nullOnDelete();
                 
            $table->foreignId('village_id')
                 ->nullable()
                 ->comment('القرية')
                 ->constrained('villages')
                 ->cascadeOnUpdate()
                 ->nullOnDelete();
            
            // الأوقات والتواريخ
            $table->timestamp('email_verified_at')
                 ->nullable()
                 ->comment('وقت التحقق من البريد');
            $table->timestamp('last_seen')
                 ->nullable()
                 ->comment('آخر ظهور للمستخدم');
            $table->rememberToken()->comment('رمز تذكر تسجيل الدخول');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
