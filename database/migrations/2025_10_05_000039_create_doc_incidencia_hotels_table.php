<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocIncidenciaHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_incidencia_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('fecha');
            $table->string('titulo');
            $table->string('estado');
            $table->string('dni')->nullable();
            $table->string('nombre')->nullable();
            $table->longText('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
