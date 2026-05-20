<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folio extends Model
{
    use HasFactory;

    protected $fillable = [
        'establecimiento_id',
        'remote_id',
        'partner_name',
        'partner_phone',
        'partner_email',
        'partner_id',
        'state',
        'amount_total',
        'reservation_type',
        'pending_amount',
        'first_checkin',
        'last_checkout',
        'created_by',        
        'pricelist_id',
        'sale_channel_id',        
        'internal_comment',        
        'language',
    ];

    /*public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }*/
}
