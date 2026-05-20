<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracionVideo extends Model
{
    use  Auditable, HasFactory;

    public $table = 'configuracion_videos';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'totem_id',
        'sip_identity',
        'display_name',
        'sip_registar',
        'username',
        'password',
        'created_at',
        'sip_identity_destino',
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
