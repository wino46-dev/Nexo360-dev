<?php

namespace App\Models;

use App\Observers\ControlErrorObserver;
use App\Traits\MultiTenantModelTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ControlError extends Model
{
    use  HasFactory;

    public $table = 'control_errors';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'origen',
        'tipo',
        'mensaje',
        'descripcion',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
        'establecimiento_id'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /*   public static function boot()
    {
        parent::boot();
        //self::observe(new \App\Observers\ControlErrorActionObserver);
    }*/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

}
