<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('check_in', 'remote_id')) {
            Schema::table('check_in', function (Blueprint $table) {
                $table->unsignedBigInteger('remote_id')->nullable()->after('checkin_id');
                $table->index('remote_id');
            });
        }

        // Keep compatibility with existing data that still uses checkin_id.
        if (Schema::hasColumn('check_in', 'checkin_id') && Schema::hasColumn('check_in', 'remote_id')) {
            DB::table('check_in')
                ->whereNull('remote_id')
                ->whereNotNull('checkin_id')
                ->update(['remote_id' => DB::raw('checkin_id')]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('check_in', 'remote_id')) {
            Schema::table('check_in', function (Blueprint $table) {
                $table->dropIndex(['remote_id']);
                $table->dropColumn('remote_id');
            });
        }
    }
};
