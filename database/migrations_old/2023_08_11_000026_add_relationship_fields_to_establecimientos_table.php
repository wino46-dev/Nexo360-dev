<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEstablecimientosTable extends Migration
{
    public function up()
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->unsignedBigInteger('sociedad_id')->nullable();
            $table->foreign('sociedad_id', 'sociedad_fk_8841865')->references('id')->on('sociedads');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8841825')->references('id')->on('teams');
        });
    }
}
