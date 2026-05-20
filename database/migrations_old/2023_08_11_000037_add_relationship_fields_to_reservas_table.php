<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToReservasTable extends Migration
{
    public function up()
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_8849226')->references('id')->on('establecimientos');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id', 'cliente_fk_8849291')->references('id')->on('clientes');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8849234')->references('id')->on('teams');
        });
    }
}
