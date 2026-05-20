<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlSesionsTable extends Migration
{
    public function up()
    {
        Schema::create('control_sesions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('estado_sesion')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
