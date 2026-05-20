<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToConfiguracionGrabadorsTable extends Migration
{
    public function up()
    {
        Schema::table('configuracion_grabadors', function (Blueprint $table) {
            $table->unsignedBigInteger('totem_id')->nullable();
            $table->foreign('totem_id', 'totem_fk_8994511')->references('id')->on('totems');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8994520')->references('id')->on('teams');
        });
    }
}
