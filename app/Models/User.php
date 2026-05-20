<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens,SoftDeletes, Notifiable, Auditable, HasFactory;

    public const TYPE_INTERNAL = 'internal';
    public const TYPE_EXTERNAL = 'external';
    public const TYPE_TOTEM = 'totem';

    public $table = 'users';

    protected $hidden = [
        'remember_token', 'two_factor_code',
        'password',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
        'two_factor_expires_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'two_factor',
        'two_factor_code',
        'remember_token',
        'totem_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'sip_identity',
        'two_factor_expires_at',
        'login_pms_establecimiento',
        'pms_password',
        'type',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps            = false;
        $this->two_factor_code       = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(15)->format(config('panel.date_format') . ' ' . config('panel.time_format'));
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps            = false;
        $this->two_factor_code       = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }
    public function internalUser()
    {
        // Backwards compatibility: treat type if set, otherwise fallback to roles
        if (!empty($this->type)) {
            $type = strtolower($this->type);
            // Totem is not an internal backoffice user
            if ($type === self::TYPE_TOTEM) {
                return false;
            }
            return in_array($type, [self::TYPE_INTERNAL, self::TYPE_EXTERNAL], true); // internal/external are considered internal users of backoffice
        }
        // If user explicitly has a Totem role by title (any case), do not treat as internal
        if ($this->roles()->whereRaw('LOWER(title) = ?', ['totem'])->exists()) {
            return false;
        }
        return $this->roles()->whereIn('id', [1,2])->exists();
    }

    public function isInternal(): bool
    {
        $type = $this->type ? strtolower($this->type) : null;
        // Totem users (by type or by role) are not internal
        if ($type === self::TYPE_TOTEM) {
            return false;
        }
        if ($this->roles()->whereRaw('LOWER(title) = ?', ['totem'])->exists()) {
            return false;
        }
        if ($type !== null) {
            return $type === self::TYPE_INTERNAL;
        }
        // Backwards compatibility: when type is null we treated users as internal by default
        return true;
    }

    public function isExternal(): bool
    {
        $type = $this->type ? strtolower($this->type) : null;
        // Totem users (by type or by role) are not external
        if ($type === self::TYPE_TOTEM) {
            return false;
        }
        if ($this->roles()->whereRaw('LOWER(title) = ?', ['totem'])->exists()) {
            return false;
        }
        return $type !== null ? ($type === self::TYPE_EXTERNAL) : false;
    }

    public function isTotem(): bool
    {
        // Check by type (case-insensitive)
        if (!empty($this->type) && strtolower($this->type) === self::TYPE_TOTEM) {
            return true;
        }
        // Fallback: check role title case-insensitively
        return $this->roles()->whereRaw('LOWER(title) = ?', ['totem'])->exists();
    }


    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function totem()
    {
        return $this->belongsTo(Totem::class, 'totem_id');
    }

    public function totemEstablecimiento()
    {
        return $this->belongsTo(Totem::class, 'totem_id')->with('establecimiento');
    }

    public function getTwoFactorExpiresAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setTwoFactorExpiresAtAttribute($value)
    {
        $this->attributes['two_factor_expires_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    // Relations for access control
    public function societies()
    {
        return $this->belongsToMany(Sociedad::class, 'sociedad_user');
    }

    public function establecimientos()
    {
        return $this->belongsToMany(Establecimiento::class, 'establecimiento_user');
    }
}
