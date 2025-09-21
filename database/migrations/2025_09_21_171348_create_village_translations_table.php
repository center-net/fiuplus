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
        Schema::create('village_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->constrained('villages');
            $table->string('locale')->index();
            $table->string('name')->unique();
            $table->unique(['village_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_translations');
    }
};
