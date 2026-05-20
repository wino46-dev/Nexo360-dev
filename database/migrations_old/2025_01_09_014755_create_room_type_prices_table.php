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
        Schema::create('room_type_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id'); // Establecimiento            
            $table->unsignedBigInteger('room_type_id'); 
            $table->unsignedBigInteger('price_list_id')->default(0); 
            $table->date('date');            
            $table->decimal('price', 15, 2)->default(0);         
            $table->timestamps();
        });

        Schema::table('room_type_prices', function (Blueprint $table) {
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
            $table->foreign('room_type_id')->references('id')->on('room_types');
            $table->unique(['room_type_id', 'date', 'price_list_id'], 'unique_room_type_date_price_list_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_prices');
    }
};
