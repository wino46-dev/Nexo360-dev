<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocServicioHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_servicio_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->decimal('precio', 15, 2)->nullable();
            $table->boolean('por_persona')->default(0)->nullable();
            $table->boolean('por_dia')->default(0)->nullable();
            $table->string('tipo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
