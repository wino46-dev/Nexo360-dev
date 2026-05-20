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

class DocHotelReceptionInfo extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    protected $appends = [
        'box_photo',
    ];

    public $table = 'doc_hotel_reception_infos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ACCES_TYPE_AFTER_HOUR_SELECT = [
        'Tarjeta' => 'Tarjeta',
        'Llave'   => 'Llave',
        'Código'   => 'Código',
    ];

    protected $fillable = [
        'establecimiento_id',
        'hour_open',
        'hour_close',
        'open_holiday',
        'close_holiday',
        'acces_type_after_hour',
        'box_locate',
        'acces_videoportero',
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

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function getBoxPhotoAttribute()
    {
        $files = $this->getMedia('box_photo');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }
}
