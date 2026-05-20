<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends CrudModel
{
    use HasFactory;

    protected $fillable = [
        'establecimiento_id',
        'name',
        'price',
        'min_price',
        'default_max_avail',
        'default_quaota'
    ];
}
