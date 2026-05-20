<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomTypePrice extends CrudModel
{
    use HasFactory;

    protected $fillable = [
        'establecimiento_id',
        'room_type_id',
        'price_list_id',
        'date',
        'price'
    ];

}
