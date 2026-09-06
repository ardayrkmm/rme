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
        Schema::create('exercise_programs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('body_area')->nullable();
            $table->integer('repetitions')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('duration')->nullable(); // in minutes
            $table->string('frequency')->nullable();
            $table->string('video_url')->nullable();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_programs');
    }
};
