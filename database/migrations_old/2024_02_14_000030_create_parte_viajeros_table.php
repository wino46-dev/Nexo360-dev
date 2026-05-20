<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParteViajerosTable extends Migration
{
    public function up()
    {
        Schema::create('parte_viajeros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('texto_inferior')->nullable();
            $table->longText('firma')->nullable();
            $table->boolean('envio_mail')->default(0)->nullable();
            $table->timestamps();
        });
    }
}
