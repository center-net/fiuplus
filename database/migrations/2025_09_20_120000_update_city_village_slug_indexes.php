<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Cities: change unique(slug) -> unique(country_id, slug)
        Schema::table('cities', function (Blueprint $table) {
            // Drop existing unique index on slug if present
            try {
                $table->dropUnique('cities_slug_unique');
            } catch (\Throwable $e) {
                // ignore if not exists
            }
            // Add composite unique index
            $table->unique(['country_id', 'slug'], 'cities_country_id_slug_unique');
        });

        // Villages: change unique(slug) -> unique(city_id, slug)
        Schema::table('villages', function (Blueprint $table) {
            try {
                $table->dropUnique('villages_slug_unique');
            } catch (\Throwable $e) {
                // ignore if not exists
            }
            $table->unique(['city_id', 'slug'], 'villages_city_id_slug_unique');
        });
    }

    public function down(): void
    {
        Schema::table('cities', function (Blueprint $table) {
            try {
                $table->dropUnique('cities_country_id_slug_unique');
            } catch (\Throwable $e) {}
            $table->unique('slug', 'cities_slug_unique');
        });

        Schema::table('villages', function (Blueprint $table) {
            try {
                $table->dropUnique('villages_city_id_slug_unique');
            } catch (\Throwable $e) {}
            $table->unique('slug', 'villages_slug_unique');
        });
    }
};