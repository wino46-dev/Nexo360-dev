<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sociedads', function (Blueprint $table) {
            if (!Schema::hasColumn('sociedads', 'parte_viajero_html_es')) {
                $table->longText('parte_viajero_html_es')->nullable()->after('parte_viajero_html');
            }
            if (!Schema::hasColumn('sociedads', 'parte_viajero_html_en')) {
                $table->longText('parte_viajero_html_en')->nullable()->after('parte_viajero_html_es');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sociedads', function (Blueprint $table) {
            if (Schema::hasColumn('sociedads', 'parte_viajero_html_en')) {
                $table->dropColumn('parte_viajero_html_en');
            }
            if (Schema::hasColumn('sociedads', 'parte_viajero_html_es')) {
                $table->dropColumn('parte_viajero_html_es');
            }
        });
    }
};
