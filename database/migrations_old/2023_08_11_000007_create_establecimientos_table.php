<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstablecimientosTable extends Migration
{
    public function up()
    {
        Schema::create('establecimientos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->bigInteger('sociedad_id')->nullable();
            $table->integer('remote_hotel_id')->nullable();  
            $table->longText('mensaje_conectado')->nullable();  
            $table->tinyInteger('ocultar_header_totem')->nullable();  
            
            
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
