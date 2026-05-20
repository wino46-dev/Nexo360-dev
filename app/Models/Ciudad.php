<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ciudad extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'ciudads';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'nombre',
        'provincia_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function ciudadDocInfoHotels()
    {
        return $this->hasMany(DocInfoHotel::class, 'ciudad_id', 'id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincium::class, 'provincia_id');
    }
}
