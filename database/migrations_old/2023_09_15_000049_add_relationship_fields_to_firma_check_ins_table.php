<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToFirmaCheckInsTable extends Migration
{
    public function up()
    {
        Schema::table('firma_check_ins', function (Blueprint $table) {
            $table->unsignedBigInteger('sesion_id')->nullable();
            $table->foreign('sesion_id', 'sesion_fk_9006679')->references('id')->on('control_sesions');
        });
    }
}
