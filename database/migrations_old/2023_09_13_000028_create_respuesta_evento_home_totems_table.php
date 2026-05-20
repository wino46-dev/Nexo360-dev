<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestaEventoHomeTotemsTable extends Migration
{
    public function up()
    {
        Schema::create('respuesta_evento_home_totems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('respuesta');
            $table->string('estado');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
