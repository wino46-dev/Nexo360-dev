<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionTpv extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'configuracion_tpvs';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'totem_id',
        'comercio',
        'terminal',
        'clave_firma',
        'conf_puerto',
        'version',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function totem()
    {
        return $this->belongsTo(Totem::class, 'totem_id');
    }


}
