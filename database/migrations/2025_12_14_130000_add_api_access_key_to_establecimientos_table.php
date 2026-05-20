<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (!Schema::hasColumn('establecimientos', 'api_access_key')) {
                $table->string('api_access_key', 128)->nullable()->after('misterplan_channel_id');
                $table->index('api_access_key', 'establecimientos_api_access_key_idx');
            }
        });
    }

    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            if (Schema::hasColumn('establecimientos', 'api_access_key')) {
                $table->dropIndex('establecimientos_api_access_key_idx');
                $table->dropColumn('api_access_key');
            }
        });
    }
};
