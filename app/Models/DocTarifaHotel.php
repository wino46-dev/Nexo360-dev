<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocTarifaHotel extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'doc_tarifa_hotels';

    protected $dates = [
        'fecha',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const REGIMEN_SELECT = [
        'Media Pension' => 'Media Pension',
        'Completa'      => 'Completa',
    ];

    protected $fillable = [
        'establecimiento_id',
        'tarifa',
        'regimen',
        'habitacion_id',
        'importe',
        'fecha',
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

    public function habitacion()
    {
        return $this->belongsTo(DocHabitacionHotel::class, 'habitacion_id');
    }

    public function getFechaAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFechaAttribute($value)
    {
        $this->attributes['fecha'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }
}
