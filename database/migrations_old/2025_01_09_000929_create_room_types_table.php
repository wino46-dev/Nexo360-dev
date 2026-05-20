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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id'); // Establecimiento            
            $table->string('name');
            $table->decimal('price', 15, 2)->default(0);            
            $table->decimal('min_price', 15, 2)->default(0);
            $table->unsignedInteger('default_max_avail')->default(0);
            $table->unsignedInteger('default_quaota')->default(0);
            $table->timestamps();
        });

        Schema::table('room_types', function (Blueprint $table) {
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
