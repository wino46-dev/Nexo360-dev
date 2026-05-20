<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pai extends Model
{
    use Auditable, HasFactory;

    public $table = 'pais';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'codigo',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function paisProvincia()
    {
        return $this->hasMany(Provincium::class, 'pais_id', 'id');
    }

    public function paisDocInfoHotels()
    {
        return $this->hasMany(DocInfoHotel::class, 'pais_id', 'id');
    }
}
