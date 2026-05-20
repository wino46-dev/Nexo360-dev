<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToControlSesionsTable extends Migration
{
    public function up()
    {
        Schema::table('control_sesions', function (Blueprint $table) {
            $table->unsignedBigInteger('emisor_id')->nullable();
            $table->foreign('emisor_id', 'emisor_fk_8862112')->references('id')->on('users');
            $table->unsignedBigInteger('receptor_id')->nullable();
            $table->foreign('receptor_id', 'receptor_fk_8862113')->references('id')->on('users');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->foreign('team_id', 'team_fk_8862118')->references('id')->on('teams');
        });
    }
}
