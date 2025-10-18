<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Set DB default to false (PostgreSQL syntax)
        try {
            // Check if we're using PostgreSQL
            if (DB::connection()->getDriverName() === 'pgsql') {
                DB::statement('ALTER TABLE stores ALTER COLUMN is_active SET DEFAULT 0');
                DB::statement('ALTER TABLE stores ALTER COLUMN is_active SET NOT NULL');
            } else {
                // MySQL syntax
                DB::statement('ALTER TABLE stores MODIFY is_active TINYINT(1) NOT NULL DEFAULT 0');
            }
        } catch (\Throwable $e) {
            // Fallback: ignore if DB doesn't support this; app-layer ensures false on create
        }

        // Set all existing stores to inactive by default
        try {
            DB::statement('UPDATE stores SET is_active = 0');
        } catch (\Throwable $e) {
            // Log error but continue migration
            \Log::error('Failed to update stores is_active: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        // No-op: do not revert existing data activation state
    }
};
