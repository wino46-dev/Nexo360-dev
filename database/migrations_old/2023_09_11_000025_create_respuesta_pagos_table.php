<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespuestaPagosTable extends Migration
{
    public function up()
    {
        Schema::create('respuesta_pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tipo_pago')->nullable();
            $table->decimal('importe', 15, 2)->nullable();
            $table->string('moneda')->nullable();
            $table->string('tarjeta_comercio_recibo')->nullable();
            $table->string('tarjeta_cliente_recibo')->nullable();
            $table->string('marca_tarjeta')->nullable();
            $table->string('caducidad')->nullable();
            $table->string('comercio')->nullable();
            $table->string('terminal')->nullable();
            $table->string('pedido')->nullable();
            $table->string('tipo_tasa_aplicada')->nullable();
            $table->string('identificador_rts')->nullable();
            $table->string('factura')->nullable();
            $table->string('fecha_operacion')->nullable();
            $table->string('estado')->nullable();
            $table->string('resultado')->nullable();
            $table->string('codigo_respuesta')->nullable();
            $table->longText('literales')->nullable();
            $table->longText('firma')->nullable();
            $table->string('operacionemv')->nullable();
            $table->string('conttrans')->nullable();
            $table->string('sectarjeta')->nullable();
            $table->string('idapp')->nullable();
            $table->string('codrespauto')->nullable();
            $table->string('resverificacion')->nullable();
            $table->string('version')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
