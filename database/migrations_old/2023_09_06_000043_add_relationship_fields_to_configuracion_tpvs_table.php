<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToConfiguracionTpvsTable extends Migration
{
    public function up()
    {
        Schema::table('configuracion_tpvs', function (Blueprint $table) {
            $table->unsignedBigInteger('totem_id')->nullable();
            $table->foreign('totem_id', 'totem_fk_8967798')->references('id')->on('totems');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8967807')->references('id')->on('teams');
        });
    }
}
