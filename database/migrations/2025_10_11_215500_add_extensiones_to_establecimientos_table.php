<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (!Schema::hasColumn('establecimientos', 'extensiones')) {
                $afterColumn = Schema::hasColumn('establecimientos', 'categoria') ? 'categoria' : 'nombre';
                $table->text('extensiones')->nullable()->after($afterColumn);
            }
        });
    }

    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (Schema::hasColumn('establecimientos', 'extensiones')) {
                $table->dropColumn('extensiones');
            }
        });
    }
};
