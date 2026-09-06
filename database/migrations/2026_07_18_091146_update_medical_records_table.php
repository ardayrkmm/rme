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
        Schema::table('medical_records', function (Blueprint $table) {
            $table->string('visit_number')->nullable()->after('appointment_id');
            $table->foreignUuid('service_id')->nullable()->after('visit_number')->constrained('service_masters')->onDelete('set null');
            
            // Rename columns
            $table->renameColumn('complaint', 'anamnesis');
            $table->renameColumn('treatment', 'therapy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->dropColumn(['visit_number', 'service_id']);
            
            $table->renameColumn('anamnesis', 'complaint');
            $table->renameColumn('therapy', 'treatment');
        });
    }
};
