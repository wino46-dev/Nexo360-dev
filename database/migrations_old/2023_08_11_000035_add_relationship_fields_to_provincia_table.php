<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToProvinciaTable extends Migration
{
    public function up()
    {
        Schema::table('provincia', function (Blueprint $table) {
            $table->unsignedBigInteger('pais_id')->nullable();
            $table->foreign('pais_id', 'pais_fk_8849197')->references('id')->on('pais');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8849202')->references('id')->on('teams');
        });
    }
}
