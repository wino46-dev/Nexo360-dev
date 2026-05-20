<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionVideosTable extends Migration
{
    public function up()
    {
        Schema::create('configuracion_videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sip_identity');
            $table->string('display_name');
            $table->string('sip_registar');
            $table->string('username');
            $table->string('password');
            $table->string('sip_identity_destino')->nullable();
            $table->timestamps();
        });
    }
}
