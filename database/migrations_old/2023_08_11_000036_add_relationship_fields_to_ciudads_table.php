<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToCiudadsTable extends Migration
{
    public function up()
    {
        Schema::table('ciudads', function (Blueprint $table) {
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->foreign('provincia_id', 'provincia_fk_8849211')->references('id')->on('provincia');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8849215')->references('id')->on('teams');
        });
    }
}
