<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDockStockHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('dock_stock_hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('dock_stock_hotels', 'ubicacion_id')) {
                $table->unsignedBigInteger('ubicacion_id')->nullable();
            }
            if (!$this->foreignKeyExists('dock_stock_hotels', 'ubicacion_fk_10736932')) {
                $table->foreign('ubicacion_id', 'ubicacion_fk_10736932')->references('id')->on('doc_ubicacion_hotels');
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
