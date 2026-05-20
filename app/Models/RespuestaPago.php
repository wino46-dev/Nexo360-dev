<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespuestaPago extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'respuesta_pagos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'pago_origen_id',
        'tipo_pago',
        'tipo_oper',
        'importe',
        'moneda',
        'tarjeta_comercio_recibo',
        'tarjeta_cliente_recibo',
        'marca_tarjeta',
        'caducidad',
        'comercio',
        'terminal',
        'tarjeta',
        'identificador_rts_base',
        'pedido',
        'tipo_tasa_aplicada',
        'identificador_rts',
        'factura',
        'fecha_operacion',
        'estado',
        'resultado',
        'codigo_respuesta',
        'literales',
        'firma',
        'operacionemv',
        'conttrans',
        'sectarjeta',
        'idapp',
        'etiqueta_app',
        'oper_contact_less',
        'codrespauto',
        'resverificacion',
        'version',
        'respuesta_xml',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pago_origen()
    {
        return $this->belongsTo(PagoTotem::class, 'pago_origen_id');
    }


}
