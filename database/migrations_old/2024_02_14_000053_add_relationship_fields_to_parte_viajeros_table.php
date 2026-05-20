<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToParteViajerosTable extends Migration
{
    public function up()
    {
        Schema::table('parte_viajeros', function (Blueprint $table) {
            $table->unsignedBigInteger('reserva_id')->nullable();
            $table->foreign('reserva_id', 'reserva_fk_9478453')->references('id')->on('reservas');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id', 'cliente_fk_9478454')->references('id')->on('clientes');
        });
    }
}
