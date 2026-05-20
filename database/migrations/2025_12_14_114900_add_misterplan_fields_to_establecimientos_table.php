<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (!Schema::hasColumn('establecimientos', 'misterplan_api_key')) {
                $table->string('misterplan_api_key')->nullable()->after('api_pms_password');
            }
            if (!Schema::hasColumn('establecimientos', 'misterplan_channel_id')) {
                $table->string('misterplan_channel_id')->nullable()->after('misterplan_api_key');
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
