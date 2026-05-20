<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToLayoutHomesTable extends Migration
{
    public function up()
    {
        Schema::table('layout_homes', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8849066')->references('id')->on('teams');
        });
    }
}
