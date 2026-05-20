<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToConfiguracionVideosTable extends Migration
{
    public function up()
    {
        Schema::table('configuracion_videos', function (Blueprint $table) {
            $table->unsignedBigInteger('totem_id')->nullable();
            $table->foreign('totem_id', 'totem_fk_9006609')->references('id')->on('totems');
        });
    }
}
