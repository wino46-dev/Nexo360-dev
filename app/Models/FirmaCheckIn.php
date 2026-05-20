<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FirmaCheckIn extends Model implements HasMedia
{
    use  InteractsWithMedia, Auditable, HasFactory;

    public $table = 'firma_check_ins';

    protected $appends = [
       // 'documento',
        'firma_imagen',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sesion_id',
        'firma_texto',
        'objeto',
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

    public function sesion()
    {
        return $this->belongsTo(ControlSesion::class, 'sesion_id');
    }

    public function getDocumentoAttribute()
    {
        return $this->getMedia('documento')->last();
    }

    public function getFirmaImagenAttribute()
    {
        $file = $this->getMedia('firma_imagen')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
    }


}
