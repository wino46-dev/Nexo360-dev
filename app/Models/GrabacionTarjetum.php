<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrabacionTarjetum extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'grabacion_tarjeta';

    protected $dates = [
        'date_in',
        'date_out',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'time_in' => 'datetime:Hi',
        'time_out' => 'datetime:Hi',
    ];

    protected $fillable = [
        'emisor_id',
        'receptor_id',
        'sesion_id',
        'status',
        'date_in',
        'time_in',
        'date_out',
        'time_out',
        'room_no',
        'room_no_2',
        'room_no_3',
        'safe_box',
        'common_doors',
        'card_qty',
        'seq_mode',
        'created_at',
        'uid_card',
        'folio_id',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
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

    public function getDateInAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

   /* public function setDateInAttribute($value)
    {
        $this->attributes['date_in'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }*/

    public function getDateOutAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    /*public function setDateOutAttribute($value)
    {
        $this->attributes['date_out'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }*/


}
