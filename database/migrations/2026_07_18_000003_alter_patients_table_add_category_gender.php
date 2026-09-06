<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'gender')) {
                $table->dropColumn('gender');
            }
            $table->foreignUuid('patient_category_id')->nullable()->after('medical_record_number')->constrained('patient_categories')->onDelete('set null');
            $table->foreignUuid('gender_id')->nullable()->after('birth_date')->constrained('genders')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropForeign(['patient_category_id']);
            $table->dropForeign(['gender_id']);
            $table->dropColumn(['patient_category_id', 'gender_id']);
            $table->enum('gender', ['L', 'P'])->after('birth_date');
        });
    }
};
