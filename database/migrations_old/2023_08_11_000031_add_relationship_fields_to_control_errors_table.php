<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToControlErrorsTable extends Migration
{
    public function up()
    {
        Schema::table('control_errors', function (Blueprint $table) {
            $table->foreign('team_id', 'team_fk_8848929')->references('id')->on('teams');
        });
    }
}
