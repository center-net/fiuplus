<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Set DB default to false (MySQL syntax)
        try {
            \DB::statement('ALTER TABLE stores MODIFY is_active TINYINT(1) NOT NULL DEFAULT 0');
        } catch (\Throwable $e) {
            // Fallback: ignore if DB doesn't support this; app-layer ensures false on create
        }

        // Set all existing stores to inactive by default
        \DB::statement('UPDATE stores SET is_active = 0');
    }

    public function down(): void
    {
        // No-op: do not revert existing data activation state
    }
};