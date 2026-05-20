<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionTpvsTable extends Migration
{
    public function up()
    {
        Schema::create('configuracion_tpvs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('comercio');
            $table->string('terminal');
            $table->string('clave_firma');
            $table->string('conf_puerto');
            $table->string('version');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
