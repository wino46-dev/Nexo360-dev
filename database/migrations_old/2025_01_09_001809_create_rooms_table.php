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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id'); // Establecimiento            
            $table->unsignedBigInteger('room_type_id'); // Establecimiento            
            $table->string('name');
            $table->unsignedInteger('capacity')->default(0);
            $table->timestamps();
        });

        Schema::table('rooms', function (Blueprint $table) {
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
            $table->foreign('room_type_id')->references('id')->on('room_types');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
