<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (!Schema::hasColumn('establecimientos', 'misterplan_api_key')) {
                $afterColumn = Schema::hasColumn('establecimientos', 'api_pms_password')
                    ? 'api_pms_password'
                    : (Schema::hasColumn('establecimientos', 'extensiones') ? 'extensiones' : 'nombre');

                $table->string('misterplan_api_key')->nullable()->after($afterColumn);
            }
            if (!Schema::hasColumn('establecimientos', 'misterplan_channel_id')) {
                $afterColumn = Schema::hasColumn('establecimientos', 'misterplan_api_key')
                    ? 'misterplan_api_key'
                    : (Schema::hasColumn('establecimientos', 'extensiones') ? 'extensiones' : 'nombre');

                $table->string('misterplan_channel_id')->nullable()->after($afterColumn);
            }
        });
    }

    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (Schema::hasColumn('establecimientos', 'misterplan_channel_id')) {
                $table->dropColumn('misterplan_channel_id');
            }
            if (Schema::hasColumn('establecimientos', 'misterplan_api_key')) {
                $table->dropColumn('misterplan_api_key');
            }
        });
    }
};
