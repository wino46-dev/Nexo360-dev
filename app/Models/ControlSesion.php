<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControlSesion extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'control_sesions';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'estado_sesion',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function sesionPagoTotems()
    {
        return $this->hasMany(PagoTotem::class, 'sesion_id', 'id');
    }

    public function sesionGrabacionTarjeta()
    {
        return $this->hasMany(GrabacionTarjetum::class, 'sesion_id', 'id');
    }

    public function sesionEventoHomeTotems()
    {
        return $this->hasMany(EventoHomeTotem::class, 'sesion_id', 'id');
    }

    public function sesionFirmaCheckIns()
    {
        return $this->hasMany(FirmaCheckIn::class, 'sesion_id', 'id');
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, 'emisor_id');
    }

    public function receptor()
    {
        return $this->belongsTo(User::class, 'receptor_id');
    }


}
