<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->string('email_partes_recepcion')->nullable(); 
            $table->time('hora_envio_partes')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            //
        });
    }
};
