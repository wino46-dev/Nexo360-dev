<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToClientesTable extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->unsignedBigInteger('pais_id')->nullable();
            $table->foreign('pais_id', 'pais_fk_8849216')->references('id')->on('pais');
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->foreign('provincia_id', 'provincia_fk_8849217')->references('id')->on('provincia');
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->foreign('ciudad_id', 'ciudad_fk_8849218')->references('id')->on('ciudads');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8849187')->references('id')->on('teams');
        });
    }
}
