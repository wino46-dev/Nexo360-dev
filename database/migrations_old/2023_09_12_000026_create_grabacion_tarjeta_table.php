<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrabacionTarjetaTable extends Migration
{
    public function up()
    {
        Schema::create('grabacion_tarjeta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date_in');
            $table->time('time_in');
            $table->date('date_out');
            $table->time('time_out');
            $table->integer('room_no');
            $table->integer('room_no_2')->nullable();
            $table->integer('room_no_3')->nullable();
            $table->string('safe_box');
            $table->longText('common_doors')->nullable();
            $table->integer('card_qty');
            $table->string('uid_card');
            $table->string('seq_mode');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
