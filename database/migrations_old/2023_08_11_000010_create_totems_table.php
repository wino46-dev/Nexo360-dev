<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTotemsTable extends Migration
{
    public function up()
    {
        Schema::create('totems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->unique();
            $table->string('fuente_imagenes');
            $table->string('fuente_spinner')->nullable();
            $table->longText('texto_superior_pagina_1')->nullable();
            $table->longText('texto_superior_pagina_2')->nullable();
            $table->longText('texto_inferior_pagina_2')->nullable();
            $table->boolean('mostrar_pagina_2')->default(0)->nullable();
            $table->longText('texto_inferior')->nullable();
            $table->longText('comentarios')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
