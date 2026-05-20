<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        Schema::table('check_in', function (Blueprint $table) {
            $table->index('remote_id');
            $table->index('number');
            $table->foreign('reservation_id')->references('id')->on('reservations');
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->index('remote_id');
        });

        Schema::table('folios', function (Blueprint $table) {
            $table->index('remote_id');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_in', function (Blueprint $table) {
            //
        });
    }
};
