<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocHabitacionHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_habitacion_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('tipo')->nullable();
            $table->string('capacidad');
            $table->string('tipo_cerradura')->nullable();
            $table->string('codigo')->nullable();
            $table->longText('comentarios')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
