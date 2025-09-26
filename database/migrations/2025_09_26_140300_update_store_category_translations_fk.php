<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_category_translations', function (Blueprint $table) {
            // Drop existing FK first
            $table->dropForeign(['store_category_id']);
        });

        Schema::table('store_category_translations', function (Blueprint $table) {
            // Recreate with cascade on delete so translations are deleted with the category
            $table->foreign('store_category_id')
                ->references('id')->on('store_categories')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('store_category_translations', function (Blueprint $table) {
            $table->dropForeign(['store_category_id']);
        });

        Schema::table('store_category_translations', function (Blueprint $table) {
            // Recreate without cascade (default restrict)
            $table->foreign('store_category_id')
                ->references('id')->on('store_categories');
        });
    }
};