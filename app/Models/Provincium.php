<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provincium extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'provincia';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'pais_id',
        'iso',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function provinciaCiudads()
    {
        return $this->hasMany(Ciudad::class, 'provincia_id', 'id');
    }

    public function provinciaDocInfoHotels()
    {
        return $this->hasMany(DocInfoHotel::class, 'provincia_id', 'id');
    }

    public function pais()
    {
        return $this->belongsTo(Pai::class, 'pais_id');
    }
}
