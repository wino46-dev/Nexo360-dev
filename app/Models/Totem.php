<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Totem extends Model implements HasMedia
{
    use SoftDeletes,  InteractsWithMedia, Auditable, HasFactory;

    public $table = 'totems';

    protected $appends = [
        'imagenes_pagina_inicial',
        'imagenes',
        'spinner',
        'imagen_pagina_2',
        'imagen_pagina_llamada',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const FUENTE_IMAGENES_SELECT = [
        'Totem'           => 'Totem',
        'Establecimiento' => 'Establecimiento',
        'Sociedad'        => 'Sociedad',
    ];

    public const FUENTE_SPINNER_SELECT = [
        'Totem'           => 'Totem',
        'Establecimiento' => 'Establecimiento',
        'Organización'    => 'Organización',
    ];

    protected $fillable = [
        'establecimiento_id',
        'codigo',
        'fuente_imagenes',
        'fuente_spinner',
        'created_at',
        'texto_superior_pagina_1',
        'fuente_imagenes_pagina_1',
        'pagina_llamada_texto_superior',
        'pagina_llamada_texto_inferior',
        'pagina_llamada_texto_boton',
        'texto_superior_pagina_2',
        'texto_inferior_pagina_2',
        'mostrar_pagina_2',
        'texto_inferior',
        'comentarios',
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

    public function totemUsers()
    {
        return $this->hasMany(User::class, 'totem_id', 'id');
    }

    public function totemConfiguracionTpvs()
    {
        return $this->hasMany(ConfiguracionTpv::class, 'totem_id', 'id');
    }

    public function totemConfiguracionGrabadors()
    {
        return $this->hasMany(ConfiguracionGrabador::class, 'totem_id', 'id');
    }

    public function totemConfiguracionVideos()
    {
        return $this->hasMany(ConfiguracionVideo::class, 'totem_id', 'id');
    }

    public function getImagenesPaginaInicialAttribute()
    {
        $files = $this->getMedia('imagenes_pagina_inicial');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function getImagenesAttribute()
    {
        $files = $this->getMedia('imagenes');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public function getSpinnerAttribute()
    {
        return $this->getMedia('spinner')->last();
    }

    public function getImagenPagina2Attribute()
    {
        $file = $this->getMedia('imagen_pagina_2')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }
    public function getImagenPaginaLlamadaAttribute()
    {
        $file = $this->getMedia('imagen_pagina_llamada')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }

}
