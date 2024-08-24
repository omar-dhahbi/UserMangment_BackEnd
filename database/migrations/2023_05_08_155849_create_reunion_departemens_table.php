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
        Schema::create('reunion_departemens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departement_id');
            $table->foreign('departement_id')->references('id')->on('departements');
            $table->unsignedBigInteger('reunion_id');
            $table->foreign('reunion_id')->references('id')->on('reunions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reunion_departemens');
    }
};
