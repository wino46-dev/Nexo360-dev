<?php

namespace App\Models;

use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventoHomeTotem extends Model implements HasMedia
{
    use  InteractsWithMedia, HasFactory;

    public $table = 'evento_home_totems';

    protected $appends = [
        'respuesta_imagen',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const CANAL_TRANSMISION_SELECT = [
        'Home Inferior' => 'Home Inferior',
    ];

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'sesion_id',
        'tipo_evento_id',
        'objeto',
        'canal_transmision',
        'respuesta_texto',
        'pms',
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

    public function tipo_evento()
    {
        return $this->belongsTo(TipoEvento::class, 'tipo_evento_id');
    }

    public function getRespuestaImagenAttribute()
    {
        $file = $this->getMedia('respuesta_imagen')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }


}
