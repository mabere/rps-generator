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
        Schema::create('rps_weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rps_document_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('week_number');
            $table->text('sub_cpmk');
            $table->text('learning_methods')->nullable();
            $table->text('assessment_criteria')->nullable();
            $table->unsignedTinyInteger('weight')->default(0);
            $table->text('resources')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rps_weeks');
    }
};
