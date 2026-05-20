<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        // Drop Firma Check-Ins first to avoid potential FKs referencing clientes
        if (Schema::hasTable('firma_check_ins')) {
            Schema::drop('firma_check_ins');
        }
        if (Schema::hasTable('clientes')) {
            Schema::drop('clientes');
        }
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Recreate minimal tables to allow rollback; no relationships are restored
        if (!Schema::hasTable('clientes')) {
            Schema::create('clientes', function (Blueprint $table) {
                $table->bigIncrements('id');
                // Minimal columns only; original schema intentionally not restored
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('firma_check_ins')) {
            Schema::create('firma_check_ins', function (Blueprint $table) {
                $table->bigIncrements('id');
                // Minimal columns only; original schema intentionally not restored
                $table->timestamps();
                // If the original had media (Spatie), those relations live in media table and are unaffected
            });
        }
    }
};
