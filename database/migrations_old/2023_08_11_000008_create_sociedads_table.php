<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSociedadsTable extends Migration
{
    public function up()
    {
        Schema::create('sociedads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->longText('pago_html_inferior')->nullable();
            $table->longText('parte_viajero_html')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
