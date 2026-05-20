<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagoTotemsTable extends Migration
{
    public function up()
    {
        Schema::create('pago_totems', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('importe', 15, 2);
            $table->string('factura');
            $table->string('tipo_operacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
