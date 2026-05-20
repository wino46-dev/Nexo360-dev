<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfiguracionGrabadorsTable extends Migration
{
    public function up()
    {
        Schema::create('configuracion_grabadors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('software_gestion');
            $table->integer('reader_no')->nullable();
            $table->string('track_2')->nullable();
            $table->string('seq_mode');
            $table->string('show_message')->unique();
            $table->string('user_host');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
