<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocHotelReceptionInfosTable extends Migration
{
    public function up()
    {
        Schema::table('doc_hotel_reception_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_hotel_reception_infos', 'establecimiento_id')) {
                $table->unsignedBigInteger('establecimiento_id')->nullable();
            }

            // Avoid failing when a previous attempt partially executed.
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736825')->references('id')->on('establecimientos');
        });
    }
}
