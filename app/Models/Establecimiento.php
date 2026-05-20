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

class Establecimiento extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'establecimientos';

    protected $appends = [
        'logo_establecimiento',
        'imagenes',
        'tour_images',
        'imagenes_pagina_1',
        'spinner',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'sociedad_id',
        'codigo',
        'nombre',
        'mensaje_conectado',
        'ocultar_header_totem',
        'remote_hotel_id',
        'proveedor_cerradura',
        'api_pms',
        'api_pms_url',
        'api_pms_username',
        'api_pms_password',
        // MisterPlan per‑hotel credentials
        'misterplan_api_key',
        'misterplan_channel_id',
        'api_access_key',
        'nif',
        'direccion',
        'ciudad',
        'zip',
        'categoria',
        'extensiones',
        'pms_payment_method_totem',
        'pms_payment_method_manual',
        'departure_time_card',
        'created_at',
        'updated_at',
        'deleted_at',

        'partes_email_recepcion',
        'partes_hora_envio',
        'partes_tipo_envio',
        'partes_ftp_data',
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

    public function establecimientoHabitacions()
    {
        return $this->hasMany(Habitacion::class, 'establecimiento_id', 'id');
    }

    public function establecimientoTotems()
    {
        return $this->hasMany(Totem::class, 'establecimiento_id', 'id');
    }

    public function sociedad()
    {
        return $this->belongsTo(Sociedad::class, 'sociedad_id');
    }

    public function getLogoEstablecimientoAttribute()
    {
        $file = $this->getMedia('logo_establecimiento')->last();
        if ($file) {
            $file->url       = $file->getUrl();
            $file->thumbnail = $file->getUrl('thumb');
            $file->preview   = $file->getUrl('preview');
        }

        return $file;
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

    public function getTourImagesAttribute()
    {
        $files = $this->getMedia('tour_images');
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

    public function getSpinnerAttribute()
    {
        return $this->getMedia('spinner')->last();
    }

    public static function proveedoresCerraduras()
    {
        return [
            'OMNITEC' => 'OMNITEC',
            'ONITY' => 'ONITY',
            'SALTO' => 'SALTO',
        ];
    }

    public static function pmsList()
    {
        return [
            'local' => 'local',
            'roomdoo' => 'roomdoo',
            'misterplan' => 'misterplan',
        ];
    }

    public static function listWithPmsData()
    {
        $query = self::whereNotNull('api_pms')
            ->whereNotNull('api_pms_url')
            ->whereNotNull('api_pms_username')
            ->whereNotNull('api_pms_password');

        // If middleware provided an allowlist for external users, enforce it
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed)) {
            $ids = count($allowed) ? $allowed : [0];
            $query->whereIn('establecimientos.id', $ids);
        }

        return $query->orderBy('nombre', 'asc')->get();
    }

    public static function listWithPmsDataAndLocal()
    {
        $query = self::where(function($q){
                $q->whereNotNull('api_pms')
                  ->whereNotNull('api_pms_url')
                  ->whereNotNull('api_pms_username')
                  ->whereNotNull('api_pms_password');
            })
            ->orWhere('api_pms', 'local');

        // If middleware provided an allowlist for external users, enforce it
        $allowed = config('external.allowed_hotels');
        if (is_array($allowed)) {
            $ids = count($allowed) ? $allowed : [0];
            $query->whereIn('establecimientos.id', $ids);
        }

        return $query->orderBy('nombre', 'asc')->get();
    }

    public function establecimientoDocHotelReceptionInfos()
    {
        return $this->hasMany(DocHotelReceptionInfo::class, 'establecimiento_id', 'id');
    }

    public function hotelDocInfoHotels()
    {
        return $this->hasMany(DocInfoHotel::class, 'hotel_id', 'id');
    }

    public function hotelDocServicioHotels()
    {
        return $this->hasMany(DocServicioHotel::class, 'hotel_id', 'id');
    }

    public function establecimientoDocMetodoPagoHotels()
    {
        return $this->hasMany(DocMetodoPagoHotel::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocUbicacionHotels()
    {
        return $this->hasMany(DocUbicacionHotel::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocIncidenciaHotels()
    {
        return $this->hasMany(DocIncidenciaHotel::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocNoDeseadoHotels()
    {
        return $this->hasMany(DocNoDeseadoHotel::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocHotelEstadoCajas()
    {
        return $this->hasMany(DocHotelEstadoCaja::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocHabitacionHotels()
    {
        return $this->hasMany(DocHabitacionHotel::class, 'establecimiento_id', 'id');
    }

    public function establecimientoDocTarifaHotels()
    {
        return $this->hasMany(DocTarifaHotel::class, 'establecimiento_id', 'id');
    }

}
