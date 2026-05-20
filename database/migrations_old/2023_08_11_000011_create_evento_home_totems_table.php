<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventoHomeTotemsTable extends Migration
{
    public function up()
    {
        Schema::create('evento_home_totems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('objeto')->nullable();
            $table->string('canal_transmision')->nullable();
            $table->timestamps();
        });
    }
}
