<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToGrabacionTarjetaTable extends Migration
{
    public function up()
    {
        Schema::table('grabacion_tarjeta', function (Blueprint $table) {
            $table->unsignedBigInteger('emisor_id')->nullable();
            $table->foreign('emisor_id', 'emisor_fk_8994484')->references('id')->on('users');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id', 'receptor_fk_8994485')->references('id')->on('users');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8994499')->references('id')->on('teams');
        });
    }
}
