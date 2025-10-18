<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stores', function (Blueprint $table) {
        $table->boolean('is_active')->default(false)->change();
    });

    // تحديث البيانات في migration منفصل أو عبر seeders
    DB::table('stores')->update(['is_active' => false]);
    }

    public function down(): void
    {
        // No-op: do not revert existing data activation state
    }
};
