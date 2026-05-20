<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocServicioHotel extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'doc_servicio_hotels';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_SELECT = [
        'Prepago'  => 'Prepago',
        'Postpago' => 'Postpago',
    ];

    protected $fillable = [
        'hotel_id',
        'nombre',
        'codigo',
        'precio',
        'por_persona',
        'por_dia',
        'tipo',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function hotel()
    {
        return $this->belongsTo(Establecimiento::class, 'hotel_id');
    }
}
