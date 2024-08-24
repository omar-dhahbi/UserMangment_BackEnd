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
        Schema::create('stagiaires', function (Blueprint $table) {
            $table->id();
            $table->string('Cin');
            $table->string('Nom');
            $table->string('Prenom');
            $table->integer('telephone');
            $table->string('Email');
            $table->string('Ecole');
            $table->string('TypeStage');
            $table->string('NiveauEtude');
            $table->string('class');
            $table->date('DateDebut');
            $table->date('DateFin');
            $table->string('sujet');
            $table->text('DescriptionSujet');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stagiaires');
    }
};
