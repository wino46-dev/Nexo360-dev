<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocInfoHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_info_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreign('hotel_id', 'hotel_fk_10736872')->references('id')->on('establecimientos');
            $table->unsignedBigInteger('pais_id')->nullable();
            $table->foreign('pais_id', 'pais_fk_10736876')->references('id')->on('pais');
            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->foreign('provincia_id', 'provincia_fk_10736877')->references('id')->on('provincia');
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->foreign('ciudad_id', 'ciudad_fk_10736878')->references('id')->on('ciudads');
        });
    }
}
