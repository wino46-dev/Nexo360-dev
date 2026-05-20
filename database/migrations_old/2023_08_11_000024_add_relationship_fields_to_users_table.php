<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('totem_id')->nullable();
            $table->foreign('totem_id', 'totem_fk_8860531')->references('id')->on('totems');
            $table->foreign('team_id', 'team_fk_8841805')->references('id')->on('teams');
        });
    }
}
