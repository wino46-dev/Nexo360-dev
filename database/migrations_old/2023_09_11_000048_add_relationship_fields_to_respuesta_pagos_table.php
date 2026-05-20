<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRespuestaPagosTable extends Migration
{
    public function up()
    {
        Schema::table('respuesta_pagos', function (Blueprint $table) {
            $table->unsignedBigInteger('pago_origen_id')->nullable();
            $table->foreign('pago_origen_id', 'pago_origen_fk_8988585')->references('id')->on('pago_totems');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8988615')->references('id')->on('teams');
        });
    }
}
