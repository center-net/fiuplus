<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Add unique indexes for email and phone if not already unique
            // Note: nullable unique columns allow multiple NULLs by default in MySQL. If you need single NULL, handle at app layer.
            if (!Schema::hasColumn('stores', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('stores', 'phone')) {
                $table->string('phone')->nullable();
            }
            $table->unique('email', 'stores_email_unique');
            $table->unique('phone', 'stores_phone_unique');
        });

        Schema::table('store_translations', function (Blueprint $table) {
            // Ensure name unique per locale (store_id, locale) already unique; we need (locale, name) unique to prevent duplicates per locale
            $table->unique(['locale', 'name'], 'store_translations_locale_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropUnique('stores_email_unique');
            $table->dropUnique('stores_phone_unique');
        });

        Schema::table('store_translations', function (Blueprint $table) {
            $table->dropUnique('store_translations_locale_name_unique');
        });
    }
};