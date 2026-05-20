<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocInfoHotelsTable extends Migration
{
    public function up()
    {
        Schema::create('doc_info_hotels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('descripcion');
            $table->longText('exteriores')->nullable();
            $table->string('categoria')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->string('telefono')->nullable();
            $table->string('emergencias')->nullable();
            $table->string('email')->nullable();
            $table->string('web')->nullable();
            $table->string('enlace_fotos')->nullable();
            $table->longText('aparcamiento')->nullable();
            $table->longText('recomendaciones')->nullable();
            $table->longText('horas_chekin')->nullable();
            $table->longText('observaciones')->nullable();
            $table->string('cuenta_bancaria')->nullable();
            $table->string('modos_cobro')->nullable();
            $table->longText('datos_responsable')->nullable();
            $table->longText('regional_manager')->nullable();
            $table->longText('revenue_manager')->nullable();
            $table->string('pet_friendly')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
