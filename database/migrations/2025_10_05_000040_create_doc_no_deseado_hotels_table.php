<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocNoDeseadoHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_no_deseado_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->datetime('fecha')->nullable();
            $table->longText('datos_cliente');
            $table->boolean('no_deseado')->default(0)->nullable();
            $table->string('motivo');
            $table->longText('comentarios')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
