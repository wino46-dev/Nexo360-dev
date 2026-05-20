<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocTarifaHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_tarifa_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tarifa');
            $table->string('regimen')->nullable();
            $table->decimal('importe', 15, 2);
            $table->date('fecha')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
