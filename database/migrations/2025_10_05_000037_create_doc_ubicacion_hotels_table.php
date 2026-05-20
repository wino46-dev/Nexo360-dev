<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocUbicacionHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_ubicacion_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('tipo')->nullable();
            $table->integer('piso')->nullable();
            $table->boolean('zona_comun')->default(0)->nullable();
            $table->longText('comentarios')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
