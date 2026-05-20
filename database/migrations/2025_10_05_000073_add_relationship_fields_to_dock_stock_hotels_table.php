<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDockStockHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('dock_stock_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('ubicacion_id')->nullable();
            $table->foreign('ubicacion_id', 'ubicacion_fk_10736932')->references('id')->on('doc_ubicacion_hotels');
        });
    }
}
