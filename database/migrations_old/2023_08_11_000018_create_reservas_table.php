<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservasTable extends Migration
{
    public function up()
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code');
            $table->string('name');
            $table->integer('folio_id');
            $table->string('partner_name');
            $table->integer('nights')->nullable();            
            $table->integer('adults')->nullable();
            $table->integer('children')->nullable();
            $table->datetime('checkin');
            $table->datetime('checkout');            
            $table->decimal('total', 15, 2);           
            $table->timestamps();
        });
    }
}

