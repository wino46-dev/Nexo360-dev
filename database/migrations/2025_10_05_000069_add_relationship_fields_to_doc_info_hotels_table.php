<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocInfoHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_info_hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_info_hotels', 'hotel_id')) {
                $table->unsignedBigInteger('hotel_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_info_hotels', 'hotel_fk_10736872')) {
                $table->foreign('hotel_id', 'hotel_fk_10736872')->references('id')->on('establecimientos');
            }

            if (!Schema::hasColumn('doc_info_hotels', 'pais_id')) {
                $table->unsignedBigInteger('pais_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_info_hotels', 'pais_fk_10736876')) {
                $table->foreign('pais_id', 'pais_fk_10736876')->references('id')->on('pais');
            }

            if (!Schema::hasColumn('doc_info_hotels', 'provincia_id')) {
                $table->unsignedBigInteger('provincia_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_info_hotels', 'provincia_fk_10736877')) {
                $table->foreign('provincia_id', 'provincia_fk_10736877')->references('id')->on('provincia');
            }

            if (!Schema::hasColumn('doc_info_hotels', 'ciudad_id')) {
                $table->unsignedBigInteger('ciudad_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_info_hotels', 'ciudad_fk_10736878')) {
                $table->foreign('ciudad_id', 'ciudad_fk_10736878')->references('id')->on('ciudads');
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
