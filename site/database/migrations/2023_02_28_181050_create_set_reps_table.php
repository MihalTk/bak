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
        Schema::create('set_reps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idTrainings_Exercise')->nullable();
            $table->foreign('idTrainings_Exercise')->references('id')->on('trainings_exercises')->onDelete('cascade');
            $table->tinyInteger('num_of_set');
            $table->tinyInteger('num_of_reps');
            $table->decimal('weight',5,2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_reps');
    }
};
