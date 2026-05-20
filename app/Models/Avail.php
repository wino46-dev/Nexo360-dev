<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avail extends Model
{
    use HasFactory;

    protected $fillable = [
        'establecimiento_id',
        'room_id',
        'date',
        'available'
    ];
}
