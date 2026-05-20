<?php

namespace App\Models;

use App\Traits\Auditable;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes,  Auditable, HasFactory;

    public $table = 'clientes';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'apellidos',
        'nif',
        'telefono',
        'pais_id',
        'provincia_id',
        'ciudad_id',
        'cod_postal',
        'direccion',
        'email',
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function clienteReservas()
    {
        return $this->hasMany(Reservation::class, 'partner_id', 'id');
    }

    public function clienteCheckIns()
    {
        return $this->hasMany(CheckIn::class, 'cliente_id', 'id');
    }

    public function pais()
    {
        return $this->belongsTo(Pai::class, 'pais_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincium::class, 'provincia_id');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }


}
