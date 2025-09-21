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
        // Helper to drop and recreate FK with cascade
        $applyCascade = function (string $table, string $column, string $parent) {
            Schema::table($table, function (Blueprint $table) use ($column, $parent) {
                $fkName = $table->getTable().'_'.$column.'_foreign';
            });

            // Laravel doesn't allow reading fk names via Blueprint easily. We'll try common name then fallback.
            try {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->dropForeign([$column]);
                });
            } catch (\Throwable $e) {
                // ignore if already dropped or named differently
            }

            Schema::table($table, function (Blueprint $table) use ($column, $parent) {
                $table->foreign($column)
                      ->references('id')->on($parent)
                      ->cascadeOnDelete()
                      ->cascadeOnUpdate();
            });
        };

        $applyCascade('user_translations', 'user_id', 'users');
        $applyCascade('role_translations', 'role_id', 'roles');
        $applyCascade('permission_translations', 'permission_id', 'permissions');
        $applyCascade('country_translations', 'country_id', 'countries');
        $applyCascade('city_translations', 'city_id', 'cities');
        $applyCascade('village_translations', 'village_id', 'villages');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to RESTRICT on delete (no cascade)
        $revert = function (string $table, string $column, string $parent) {
            try {
                Schema::table($table, function (Blueprint $table) use ($column) {
                    $table->dropForeign([$column]);
                });
            } catch (\Throwable $e) {}

            Schema::table($table, function (Blueprint $table) use ($column, $parent) {
                $table->foreign($column)
                      ->references('id')->on($parent)
                      ->restrictOnDelete()
                      ->cascadeOnUpdate();
            });
        };

        $revert('user_translations', 'user_id', 'users');
        $revert('role_translations', 'role_id', 'roles');
        $revert('permission_translations', 'permission_id', 'permissions');
        $revert('country_translations', 'country_id', 'countries');
        $revert('city_translations', 'city_id', 'cities');
        $revert('village_translations', 'village_id', 'villages');
    }
};