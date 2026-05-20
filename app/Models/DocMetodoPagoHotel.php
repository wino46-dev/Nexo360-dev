<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocMetodoPagoHotel extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'doc_metodo_pago_hotels';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_SELECT = [
        'Efectivo'      => 'Efectivo',
        'Tarjeta'       => 'Tarjeta',
        'Transferéncia' => 'Transferéncia',
    ];

    protected $fillable = [
        'establecimiento_id',
        'nombre',
        'tipo',
        'activo',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
