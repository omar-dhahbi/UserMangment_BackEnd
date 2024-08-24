<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        //  Schema::create('users', function (Blueprint $table) {
        //      $table->id();
        //      $table->string('cin')->unique();
        //      $table->string('nom');
        //      $table->string('prenom');
        //      $table->string('tel');
        //      $table->string('email')->unique();
        //      $table->timestamp('email_verified_at')->nullable();
        //      $table->string('password');
        //      $table->boolean('status');
        //      $table->boolean('verif_email');
        //      $table->string('photo');
        //      $table->string('RaisonSociale')->default('Carthage solution');
        //      $table->string('code')->nullable();
        //      $table->string('role')->default('employee');
        //      $table->unsignedBigInteger('congés_id');
        //      $table->foreign('congés_id')->references('id')->on('congés');
        //      $table->rememberToken();
        //      $table->timestamps();
        //  });
        Schema::dropIfExists('users');

        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
