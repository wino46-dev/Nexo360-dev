<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToDocHotelEstadoCajasTable extends Migration
{
    public function up()
    {
        Schema::table('doc_hotel_estado_cajas', function (Blueprint $table) {
            if (!Schema::hasColumn('doc_hotel_estado_cajas', 'establecimiento_id')) {
                $table->unsignedBigInteger('establecimiento_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_hotel_estado_cajas', 'establecimiento_fk_10736960')) {
                $table->foreign('establecimiento_id', 'establecimiento_fk_10736960')->references('id')->on('establecimientos');
            }

            if (!Schema::hasColumn('doc_hotel_estado_cajas', 'room_id')) {
                $table->unsignedBigInteger('room_id')->nullable();
            }
            if (!$this->foreignKeyExists('doc_hotel_estado_cajas', 'room_fk_10736841')) {
                $table->foreign('room_id', 'room_fk_10736841')->references('id')->on('habitacions');
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
