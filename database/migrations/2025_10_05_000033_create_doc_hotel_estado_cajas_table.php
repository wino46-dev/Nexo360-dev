<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocHotelEstadoCajasTable extends Migration
{
    public function up()
    {
        Schema::create('doc_hotel_estado_cajas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('caja');
            $table->string('code')->nullable();
            $table->string('room_status')->nullable();
            $table->string('cliente')->nullable();
            $table->string('documento_cliente')->nullable();
            $table->longText('comments')->nullable();
            $table->string('pay')->nullable();
            $table->date('fecha')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
