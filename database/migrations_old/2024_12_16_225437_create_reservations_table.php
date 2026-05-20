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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('folio_id');

            $table->string('partner_name')->nullable();
            $table->string('partner_email')->nullable();
            $table->string('partner_phone')->nullable();
            $table->unsignedBigInteger('partner_id')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('checkin');
            $table->date('checkout');
            $table->time('arrival_hour')->nullable();
            $table->time('departure_hour')->nullable();
            $table->unsignedBigInteger('room_type_id')->nullable();
            $table->unsignedBigInteger('pricelist_id')->nullable();
            $table->integer('adults');
            $table->integer('children');
            $table->string('state');
            $table->text('notes')->nullable(); // Comentario interno            
            $table->timestamp('create_date');
            $table->string('reservation_type');
            $table->decimal('price_total', 10, 2);
            $table->decimal('price_tax', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->integer('nights')->nullable();
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
