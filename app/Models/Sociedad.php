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

class Sociedad extends Model implements HasMedia
{
    use SoftDeletes,  InteractsWithMedia, Auditable, HasFactory;

    public $table = 'sociedads';

    protected $appends = [
        'imagenes',
        'imagenes_pagina_1',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'codigo',
        'nombre',
        'pago_html_inferior',
        'parte_viajero_html',
        'parte_viajero_html_es',
        'parte_viajero_html_en',
        'created_at',
        'updated_at',
        'deleted_at',
        'show_btns_capture_document'
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

    public function sociedadEstablecimientos()
    {
        return $this->hasMany(Establecimiento::class, 'sociedad_id', 'id');
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

    public function getImagenesPagina1Attribute()
    {
        $files = $this->getMedia('imagenes_pagina_1');
        $files->each(function ($item) {
            $item->url       = $item->getUrl();
            $item->thumbnail = $item->getUrl('thumb');
            $item->preview   = $item->getUrl('preview');
        });

        return $files;
    }

    public static function show_btns_capture_document_list(){
        return [
            'back' => 'CallManager',
            'front' => 'Totem',
            'both' => 'CallManager y Totem'
        ];
    }

}
