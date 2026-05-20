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
        Schema::create('avails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id'); // Establecimiento            
            $table->unsignedBigInteger('room_id'); // Habitación
            $table->date('date');
            $table->boolean('available');
            $table->timestamps();
        });

        Schema::table('avails', function (Blueprint $table) {
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
            $table->foreign('room_id')->references('id')->on('rooms');            
            $table->unique(['room_id', 'date'], 'unique_room_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avails');
    }
};
