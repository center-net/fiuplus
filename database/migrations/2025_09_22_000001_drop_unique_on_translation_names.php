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
        // ملاحظة: أسماء الفهارس التلقائية من Laravel تكون <table>_<column>_unique عادةً
        Schema::table('user_translations', function (Blueprint $table) {
            try { $table->dropUnique('user_translations_name_unique'); } catch (\Throwable $e) {}
        });

        Schema::table('role_translations', function (Blueprint $table) {
            try { $table->dropUnique('role_translations_name_unique'); } catch (\Throwable $e) {}
        });

        Schema::table('permission_translations', function (Blueprint $table) {
            try { $table->dropUnique('permission_translations_name_unique'); } catch (\Throwable $e) {}
        });

        Schema::table('country_translations', function (Blueprint $table) {
            try { $table->dropUnique('country_translations_name_unique'); } catch (\Throwable $e) {}
        });

        Schema::table('city_translations', function (Blueprint $table) {
            try { $table->dropUnique('city_translations_name_unique'); } catch (\Throwable $e) {}
        });

        Schema::table('village_translations', function (Blueprint $table) {
            try { $table->dropUnique('village_translations_name_unique'); } catch (\Throwable $e) {}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });

        Schema::table('role_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });

        Schema::table('permission_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });

        Schema::table('country_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });

        Schema::table('city_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });

        Schema::table('village_translations', function (Blueprint $table) {
            try { $table->unique('name'); } catch (\Throwable $e) {}
        });
    }
};