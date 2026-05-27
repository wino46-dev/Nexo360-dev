<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocMetodoPagoHotelsTable extends Migration
{
    public function up()
    {
        Schema::table('doc_metodo_pago_hotels', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_metodo_pago_hotels', 'establecimiento_id')) {
                $table->unsignedBigInteger('establecimiento_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_metodo_pago_hotels', 'establecimiento_fk_10736913')) {
                $table->foreign('establecimiento_id', 'establecimiento_fk_10736913')->references('id')->on('establecimientos');
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
