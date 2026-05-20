<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionGrabador extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'configuracion_grabadors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const SOFTWARE_GESTION_SELECT = [
        'GestHotel' => 'GestHotel',
        'OsAccess'  => 'OsAccess',
        'tesa' => 'Tesa'
    ];

    protected $fillable = [
        'totem_id',
        'software_gestion',
        'reader_no',
        'track_2',
        'seq_mode',
        'show_message',
        'user_host',
        'user_port',
        'created_at',
        'updated_at',
        'deleted_at',
        'json_grabador'

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
