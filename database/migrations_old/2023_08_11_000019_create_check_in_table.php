<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckInTable extends Migration
{
    public function up()
    {
        Schema::create('check_in', function (Blueprint $table) {
            $table->bigIncrements('id');            
            $table->unsignedBigInteger('checkin_id')->nullable();
            $table->unsignedBigInteger('reservation_id');
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('lastname2')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();
            $table->string('document_expedition_date')->nullable();
            $table->string('document_support_number')->nullable();
            $table->string('gender')->nullable();
            $table->string('birthdate')->nullable();
            $table->string('residence_street')->nullable();
            $table->string('zip')->nullable();
            $table->string('residence_city')->nullable();
            $table->string('nationality')->nullable();
            $table->string('country_state')->nullable();            
            $table->string('country_id')->nullable();           
            $table->longText('signature')->nullable();
            //$table->longText('comentarios')->nullable();
            $table->timestamps();   
            //$table->softDeletes();
        });
    }
}
