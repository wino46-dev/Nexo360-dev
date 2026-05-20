<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RespuestaEventoHomeTotem extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'respuesta_evento_home_totems';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const ESTADO_SELECT = [
        'success' => 'success',
        'fail'    => 'fail',
    ];

    protected $fillable = [
        'evento_id',
        'respuesta',
        'estado',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function evento()
    {
        return $this->belongsTo(EventoHomeTotem::class, 'evento_id');
    }


}
