<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FtpUploadLog extends Model
{
    use HasFactory, Auditable;

    protected $table = 'ftp_upload_logs';

    protected $fillable = [
        'establecimiento_id',
        'check_in_id',
        'disk',
        'local_path',
        'remote_path',
        'status',
        'attempts',
        'size_local',
        'size_remote',
        'remote_last_modified',
        'uploaded_at',
        'message',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'remote_last_modified' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    public function establecimiento()
    {
        return $this->belongsTo(Establecimiento::class, 'establecimiento_id');
    }

    public function checkIn()
    {
        return $this->belongsTo(CheckIn::class, 'check_in_id');
    }
}
