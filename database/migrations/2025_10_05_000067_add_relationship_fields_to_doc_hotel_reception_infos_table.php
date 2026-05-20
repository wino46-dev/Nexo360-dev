<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocHotelReceptionInfosTable extends Migration
{
    public function up()
    {
        Schema::table('doc_hotel_reception_infos', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736825')->references('id')->on('establecimientos');
        });
    }
}
