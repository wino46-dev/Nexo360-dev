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

class DocInfoHotel extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'doc_info_hotels';

    public const PET_FRIENDLY_SELECT = [
        'SI' => 'SI',
        'NO' => 'NO',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const CATEGORIA_SELECT = [
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
    ];

    protected $fillable = [
        'hotel_id',
        'descripcion',
        'exteriores',
        'categoria',
        'pais_id',
        'provincia_id',
        'ciudad_id',
        'direccion',
        'codigo_postal',
        'latitud',
        'longitud',
        'telefono',
        'emergencias',
        'email',
        'web',
        'enlace_fotos',
        'aparcamiento',
        'recomendaciones',
        'horas_chekin',
        'observaciones',
        'cuenta_bancaria',
        'modos_cobro',
        'datos_responsable',
        'regional_manager',
        'revenue_manager',
        'pet_friendly',
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

    public function hotel()
    {
        return $this->belongsTo(Establecimiento::class, 'hotel_id');
    }

    public function pais()
    {
        return $this->belongsTo(Pai::class, 'pais_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincium::class, 'provincia_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }
}
