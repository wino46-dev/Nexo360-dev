<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocHotelReceptionInfosTable extends Migration
{
    public function up()
    {
        Schema::create('doc_hotel_reception_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->time('hour_open')->nullable();
            $table->time('hour_close')->nullable();
            $table->time('open_holiday')->nullable();
            $table->time('close_holiday')->nullable();
            $table->string('acces_type_after_hour')->nullable();
            $table->longText('box_locate')->nullable();
            $table->longText('acces_videoportero')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
