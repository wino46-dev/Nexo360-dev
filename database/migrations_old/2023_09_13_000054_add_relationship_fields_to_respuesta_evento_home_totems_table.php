<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToRespuestaEventoHomeTotemsTable extends Migration
{
    public function up()
    {
        Schema::table('respuesta_evento_home_totems', function (Blueprint $table) {
            $table->unsignedBigInteger('evento_id')->nullable();
            $table->foreign('evento_id', 'evento_fk_8996610')->references('id')->on('evento_home_totems');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8996616')->references('id')->on('teams');
        });
    }
}
