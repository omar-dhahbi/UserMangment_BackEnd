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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('cin')->unique();
            $table->string('nom');
            $table->string('prenom');
            $table->string('tel');
            $table->string('email')->unique();
            $table->boolean('verif_email')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('status');
            $table->string('photo')->nullable();
            $table->string('RaisonSociale')->default('Carthage solution');
            $table->string('code')->nullable();
            $table->string('role')->default('employee');
            $table->unsignedBigInteger('departement_id')->nullable();
            $table->foreign('departement_id')->references('id')->on('departements');
            $table->string('adresse');
            $table->integer('NbJourConge')->default(45);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
