<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvinciaTable extends Migration
{
    public function up()
    {
        Schema::create('provincia', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre')->unique();
            $table->string('iso')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
