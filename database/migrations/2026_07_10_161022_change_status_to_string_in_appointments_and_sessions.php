<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE appointments MODIFY COLUMN status VARCHAR(255) DEFAULT 'pending'");
        DB::statement("ALTER TABLE therapy_sessions MODIFY COLUMN status VARCHAR(255) DEFAULT 'scheduled'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ignore reverse for simplicity in local dev
    }
};
