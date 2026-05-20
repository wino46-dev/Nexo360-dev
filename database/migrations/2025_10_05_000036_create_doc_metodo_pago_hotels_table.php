<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocMetodoPagoHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_metodo_pago_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre')->nullable();
            $table->string('tipo')->nullable();
            $table->boolean('activo')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
