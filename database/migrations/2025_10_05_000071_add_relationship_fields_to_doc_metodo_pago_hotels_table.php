<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocMetodoPagoHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_metodo_pago_hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('establecimiento_id')->nullable();
            $table->foreign('establecimiento_id', 'establecimiento_fk_10736913')->references('id')->on('establecimientos');
        });
    }
}
