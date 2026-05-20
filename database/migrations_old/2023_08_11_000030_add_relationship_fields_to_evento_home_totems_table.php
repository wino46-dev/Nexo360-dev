<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEventoHomeTotemsTable extends Migration
{
    public function up()
    {
        Schema::table('evento_home_totems', function (Blueprint $table) {
            $table->unsignedBigInteger('emisor_id')->nullable();
            $table->foreign('emisor_id', 'emisor_fk_8848893')->references('id')->on('users');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id', 'receptor_fk_8848894')->references('id')->on('users');
            $table->unsignedBigInteger('sesion_id')->nullable();
            $table->foreign('sesion_id', 'sesion_fk_9000837')->references('id')->on('control_sesions');
            $table->unsignedBigInteger('tipo_evento_id')->nullable();
            $table->foreign('tipo_evento_id', 'tipo_evento_fk_8862042')->references('id')->on('tipo_eventos');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8848901')->references('id')->on('teams');
        });
    }
}
