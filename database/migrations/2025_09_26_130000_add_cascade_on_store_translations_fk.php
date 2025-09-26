<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing FK and recreate with cascade on delete/update
        try {
            Schema::table('store_translations', function (Blueprint $table) {
                $table->dropForeign(['store_id']);
            });
        } catch (\Throwable $e) {
            // ignore if already dropped
        }

        Schema::table('store_translations', function (Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')->on('stores')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        // Revert to default (restrict on delete)
        try {
            Schema::table('store_translations', function (Blueprint $table) {
                $table->dropForeign(['store_id']);
            });
        } catch (\Throwable $e) {}

        Schema::table('store_translations', function (Blueprint $table) {
            $table->foreign('store_id')
                ->references('id')->on('stores')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }
};