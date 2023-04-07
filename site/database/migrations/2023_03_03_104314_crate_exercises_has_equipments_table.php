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
        Schema::create('exercises_has_equipments', function (Blueprint $table) {
            $table->unsignedBigInteger('idExercise')->nullable();
            $table->foreign('idExercise')->references('id')->on('exercises')->onDelete('cascade');
            $table->unsignedBigInteger('idEquipment')->nullable();
            $table->foreign('idEquipment')->references('id')->on('equipments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises_has_equipments');
    }
};
