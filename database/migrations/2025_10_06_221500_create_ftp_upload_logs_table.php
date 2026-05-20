<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ftp_upload_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('establecimiento_id')->nullable()->index();
            $table->unsignedBigInteger('check_in_id')->nullable()->index();
            $table->string('disk')->nullable();
            $table->string('local_path')->nullable();
            $table->string('remote_path');
            $table->enum('status', ['success','skip','fail'])->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->unsignedBigInteger('size_local')->nullable();
            $table->unsignedBigInteger('size_remote')->nullable();
            $table->timestamp('remote_last_modified')->nullable();
            $table->timestamp('uploaded_at')->nullable()->index();
            $table->text('message')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id')->on('establecimientos')->onDelete('set null');
            $table->foreign('check_in_id')->references('id')->on('check_in')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ftp_upload_logs');
    }
};
