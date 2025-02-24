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
        Schema::create('members', function (Blueprint $table) {
            $table->string('nrp')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->enum('role',['staff','headofdivision','headofdepartement','secretary','finance','vicechairman','chairman'])->default('staff');
            $table->string('division_code')->nullable();
            $table->string('departement_code')->nullable();
            $table->timestamps();
            $table->foreign('division_code')->references('id')->on('divisions');
            $table->foreign('departement_code')->references('id')->on('departements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
