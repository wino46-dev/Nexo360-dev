<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PagoTotem extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'pago_totems';

    public const TIPO_OPERACION_SELECT = [
        'PAGO' => 'PAGO',
    ];

    public const ORIGEN_SELECT = [
        'totem' => 'TOTEM',
        'manual' => 'MANUAL',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'importe',
        'factura',
        'tipo_operacion',
        'sesion_id',
        'estado',
        'origen',
        'folio_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'notas',
        'pms'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function pagoOrigenRespuestaPagos()
    {
        return $this->hasMany(RespuestaPago::class, 'pago_origen_id', 'id');
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }
    public function sesion()
    {
        return $this->belongsTo(ControlSesion::class, 'sesion_id');
    }


}
