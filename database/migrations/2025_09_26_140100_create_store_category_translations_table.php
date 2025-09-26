<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_category_id')->constrained('store_categories');
            $table->string('locale')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unique(['store_category_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_category_translations');
    }
};