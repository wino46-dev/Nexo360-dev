<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocServicioHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_servicio_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('hotel_id')->nullable();
            $table->foreign('hotel_id', 'hotel_fk_10736902')->references('id')->on('establecimientos');
        });
    }
}
