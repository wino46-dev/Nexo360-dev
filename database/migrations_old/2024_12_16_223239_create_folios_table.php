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
        Schema::create('folios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('establecimiento_id'); // Establecimiento            
            $table->string('partner_name')->nullable(); // Nombre del socio
            $table->string('partner_phone')->nullable(); // Teléfono del socio
            $table->string('partner_email')->nullable(); // Email del socio
            $table->unsignedBigInteger('partner_id')->nullable(); // ID del socio relacionado
            $table->string('state')->default('pending'); // Estado (por defecto: 'pending')
            $table->decimal('amount_total', 15, 2)->nullable(); // Monto total
            $table->string('reservation_type')->nullable(); // Tipo de reservación
            $table->decimal('pending_amount', 15, 2)->nullable(); // Monto pendiente
            $table->date('first_checkin')->nullable(); // Fecha de primer check-in
            $table->date('last_checkout')->nullable(); // Fecha de último check-out
            $table->unsignedBigInteger('created_by')->nullable(); // Usuario que lo creó            
            $table->unsignedBigInteger('pricelist_id')->nullable(); // ID de la lista de precios
            $table->unsignedBigInteger('sale_channel_id')->nullable(); // ID del canal de ventas                                    
            $table->text('internal_comment')->nullable(); // Comentario interno            
            $table->string('language', 10)->nullable(); // Idioma
            $table->timestamps(); // created_at y updated_at            
        });
        Schema::table('folios', function (Blueprint $table) {
            $table->foreign('establecimiento_id')->references('id')->on('establecimientos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('folios');
    }
};
