<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToTipoEventosTable extends Migration
{
    public function up()
    {
        Schema::table('tipo_eventos', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8862041')->references('id')->on('teams');
        });
    }
}
