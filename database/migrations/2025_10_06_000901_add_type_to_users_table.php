<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'type')) {
                // Use enum if supported; fallback to string with check handled at app level
                if (Schema::getConnection()->getDriverName() === 'mysql') {
                    $table->enum('type', ['internal','external','totem'])->default('internal')->after('pms_password');
                } else {
                    $table->string('type')->default('internal')->after('pms_password');
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
