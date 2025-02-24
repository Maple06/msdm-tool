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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('nrp');
            $table->unsignedBigInteger('participant_of')->nullable();
            $table->unsignedBigInteger('volunteer_of')->nullable();
            $table->enum('status',['hadir','tidak hadir']);
            $table->timestamps();
            $table->foreign('nrp')->references('nrp')->on('members');
            $table->foreign('participant_of')->references('id')->on('participants');
            $table->foreign('volunteer_of')->references('id')->on('volunteers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
