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
        Schema::table('users', function (Blueprint $table) {
            // First, drop the foreign key constraint
            $table->dropForeign(['role_id']);
            // Then, drop the column
            $table->dropColumn('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')
                  ->after('village_id') // Or wherever it was before
                  ->comment('دور المستخدم')
                  ->constrained('roles')
                  ->onUpdate('cascade')
                  ->restrictOnDelete();
        });
    }
};