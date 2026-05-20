<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DocHabitacionHotel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'doc_habitacion_hotels';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_CERRADURA_SELECT = [
        'LLave'   => 'LLave',
        'Tarjeta' => 'Tarjeta',
        'Código'  => 'Código',
    ];

    public const TIPO_SELECT = [
        'Económica' => 'Económica',
        'Estándar'  => 'Estándar',
        'Premium'   => 'Premium',
    ];

    public const CAPACIDAD_SELECT = [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6',
        '7' => '7',
        '8' => '8',
    ];

    protected $fillable = [
        'establecimiento_id',
        'nombre',
        'tipo',
        'capacidad',
        'tipo_cerradura',
        'codigo',
        'comentarios',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function habitacionDocTarifaHotels()
    {
        return $this->hasMany(DocTarifaHotel::class, 'habitacion_id', 'id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
