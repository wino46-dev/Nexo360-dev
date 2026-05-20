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
            $table->renameColumn('email_partes_recepcion', 'partes_email_recepcion');
            $table->renameColumn('hora_envio_partes', 'partes_hora_envio');
            $table->string('partes_tipo_envio')->nullable();            
            $table->json('partes_ftp_data')->nullable();
        });       

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
