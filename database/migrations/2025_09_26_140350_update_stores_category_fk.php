<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            // Drop the existing foreign key first
            $table->dropForeign(['category_id']);
        });

        Schema::table('stores', function (Blueprint $table) {
            // Recreate the FK with set null on delete to avoid blocking category deletion
            $table->foreign('category_id')
                ->references('id')->on('store_categories')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('stores', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')->on('store_categories');
        });
    }
};