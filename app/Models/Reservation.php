<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'folio_id',
        'remote_id',
        'partner_name',
        'partner_email',
        'partner_phone',
        'partner_id',
        'user_id',
        'checkin',
        'checkout',
        'arrival_hour',
        'departure_hour',
        'room_type_id',
        'pricelist_id',
        'adults',
        'children',
        'state',
        'create_date',
        'reservation_type',
        'price_total',
        'price_tax',
        'discount',
        'nights',
        'notes',
        'created_by',
        'pms',
        'name',
        'room_name'
    ];

    /*
    public function boardServices()
    {
        return $this->hasMany(BoardService::class);
    }

    public function reservationLines()
    {
        return $this->hasMany(ReservationLine::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
        */

    public function folio()
    {
        return $this->belongsTo(Folio::class, 'folio_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }
}
