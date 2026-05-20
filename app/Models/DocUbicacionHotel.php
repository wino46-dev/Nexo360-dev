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

class DocUbicacionHotel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'doc_ubicacion_hotels';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TIPO_SELECT = [
        'Cuarto Limpieza' => 'Cuarto Limpieza',
        'Guardilla'       => 'Guardilla',
        'Trastero'        => 'Trastero',
    ];

    protected $fillable = [
        'establecimiento_id',
        'nombre',
        'tipo',
        'piso',
        'zona_comun',
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

    public function ubicacionDockStockHotels()
    {
        return $this->hasMany(DockStockHotel::class, 'ubicacion_id', 'id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
