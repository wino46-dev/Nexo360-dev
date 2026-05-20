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

class DockStockHotel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'dock_stock_hotels';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const UNIDAD_SELECT = [
        'Unidades' => 'Unidades',
        'Kilos'    => 'Kilos',
        'Litros'   => 'Litros',
    ];

    protected $fillable = [
        'nombre',
        'ubicacion_id',
        'cantidad',
        'comentarios',
        'unidad',
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

    public function ubicacion()
    {
        return $this->belongsTo(DocUbicacionHotel::class, 'ubicacion_id');
    }
}
