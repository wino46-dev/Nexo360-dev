<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocServicioHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_servicio_hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_servicio_hotels', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_servicio_hotels', 'hotel_fk_10736902')) {
                $table->foreign('hotel_id', 'hotel_fk_10736902')->references('id')->on('establecimientos');
            }
        });
    }

    private function foreignKeyExists(string $table, string $constraint): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ? LIMIT 1',
            [$database, $table, $constraint, 'FOREIGN KEY']
        );

        return $result !== null;
    }
}
