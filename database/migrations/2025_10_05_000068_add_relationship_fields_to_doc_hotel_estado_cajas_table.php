<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocHotelEstadoCajasTable extends Migration
{
    public function up()
    {
        Schema::table('doc_hotel_estado_cajas', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736960')->references('id')->on('establecimientos');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id', 'room_fk_10736841')->references('id')->on('habitacions');
        });
    }
}
