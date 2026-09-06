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
        Schema::create('physiotherapists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('sip')->nullable()->comment('Surat Izin Praktik');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('photo')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('physiotherapists');
    }
};
