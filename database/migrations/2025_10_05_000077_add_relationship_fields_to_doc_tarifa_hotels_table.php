<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocTarifaHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_tarifa_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736977')->references('id')->on('establecimientos');
            $table->unsignedBigInteger('habitacion_id')->nullable();
            $table->foreign('habitacion_id', 'habitacion_fk_10736980')->references('id')->on('doc_habitacion_hotels');
        });
    }
}
