<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToPagoTotemsTable extends Migration
{
    public function up()
    {
        Schema::table('pago_totems', function (Blueprint $table) {
            $table->unsignedBigInteger('emisor_id')->nullable();
            $table->foreign('emisor_id', 'emisor_fk_8967809')->references('id')->on('users');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id', 'receptor_fk_8967810')->references('id')->on('users');
            $table->unsignedBigInteger('sesion_id')->nullable();
            $table->foreign('sesion_id', 'sesion_fk_8995411')->references('id')->on('control_sesions');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8967817')->references('id')->on('teams');
        });
    }
}
