<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDockStockHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('dock_stock_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->float('cantidad', 15, 2)->nullable();
            $table->longText('comentarios')->nullable();
            $table->string('unidad')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
