<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use  Auditable, HasFactory;

    public $table = 'reservas';

    protected $dates = [
        'checkin',
        'checkout',
        'created_at',   
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        
        'reservation_id',
        'name',
        'folio_id',
        'cliente_id',
        'partner_name',
        'nights',
        'adults',
        'children',
        'checkin_date',
        'checkout_date',
        'adults',
        'total',     
        'establecimiento_id',
        'room_name',
        'type',
        'checkin_hour',
        'checkout_hour',
        'services',        
        'created_at',
        'updated_at',
        'deleted_at',


    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function reservaCheckIns()
    {
        return $this->hasMany(CheckIn::class, 'reserva_id', 'id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function getEntradaAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEntradaAttribute($value)
    {
        $this->attributes['entrada'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getSalidaAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setSalidaAttribute($value)
    {
        $this->attributes['salida'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }


}
