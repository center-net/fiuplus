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
        Schema::table('permission_user', function (Blueprint $table) {
            // effect: allow | deny (default allow)
            $table->enum('effect', ['allow', 'deny'])->default('allow')->after('permission_id');
            $table->index(['user_id', 'permission_id', 'effect'], 'permission_user_effect_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permission_user', function (Blueprint $table) {
            $table->dropIndex('permission_user_effect_idx');
            $table->dropColumn('effect');
        });
    }
};