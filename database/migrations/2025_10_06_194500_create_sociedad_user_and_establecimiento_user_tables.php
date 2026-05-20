<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sociedad_user')) {
            Schema::create('sociedad_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('sociedad_id');
                $table->timestamps();

                $table->primary(['user_id', 'sociedad_id']);

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('sociedad_id')->references('id')->on('sociedads')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('establecimiento_user')) {
            Schema::create('establecimiento_user', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('establecimiento_id');
                $table->timestamps();

                $table->primary(['user_id', 'establecimiento_id']);

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('establecimiento_id')->references('id')->on('establecimientos')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('establecimiento_user');
        Schema::dropIfExists('sociedad_user');
    }
};
