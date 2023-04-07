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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_sk');
            $table->string('path')->nullable();
            $table->unsignedBigInteger('idprimary_exercised')->nullable();
            $table->foreign('idprimary_exercised')->references('id')->on('part_exercised')->onDelete('cascade');
            $table->unsignedBigInteger('idsecondary_exercised')->nullable();
            $table->foreign('idsecondary_exercised')->references('id')->on('part_exercised')->onDelete('cascade');
            $table->unsignedBigInteger('idDifficulty');
            $table->foreign('idDifficulty')->references('id')->on('difficulties')->onDelete('cascade');
            $table->unsignedBigInteger('idType');
            $table->foreign('idType')->references('id')->on('types')->onDelete('cascade');
            $table->unsignedBigInteger('idWhen_to_exercise');
            $table->foreign('idWhen_to_exercise')->references('id')->on('when_to_exercise')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
