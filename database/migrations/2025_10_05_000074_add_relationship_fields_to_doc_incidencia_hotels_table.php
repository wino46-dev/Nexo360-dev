<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocIncidenciaHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_incidencia_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736940')->references('id')->on('establecimientos');
        });
    }
}
