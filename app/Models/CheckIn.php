<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    use HasFactory;

    public $table = 'check_in';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'remote_id',
        'reservation_id',
        'name',
        'firstname',
        'lastname',
        'lastname2',
        'email',
        'mobile',
        'document_type',
        'document_number',
        'document_expedition_date',
        'document_support_number',
        'document_country_id',
        'gender',
        'birthdate',
        'residence_street',
        'zip',
        'residence_city',
        'nationality',
        'country_state',
        'country_id',
        'document_type_name',
        'nationality_name',
        'country_name',
        'country_state_name',
        'signature',
        'accept_checkin',
        'accept_personal_data',
        'responsible_checkin_partner_id',
        'relationship',
        'checkin_partner_state',
        'pms'
    ];


    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }





}
