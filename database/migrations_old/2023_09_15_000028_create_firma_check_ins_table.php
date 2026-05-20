<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFirmaCheckInsTable extends Migration
{
    public function up()
    {
        Schema::create('firma_check_ins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('firma_texto')->nullable();
            $table->timestamps();
        });
    }
}
