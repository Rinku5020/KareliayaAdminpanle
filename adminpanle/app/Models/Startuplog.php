<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Startuplog extends Model
{
    protected $fillable = [
        'instance_id',
        'hostname',
        'start_time',
        'start_time_local',
        'config',
        'bind_ip',
        'port',
        'service',
        'db_path',
        'log_path',
        'log_append',
        'pid',
        'version',
        'git_version',
        'target_min_os',
        'allocator',
        'javascript_engine',
        'version_array',
        'openssl_running',
        'modules',
        'storage_engines',
    ];

    protected $casts = [
        'service' => 'boolean',
        'log_append' => 'boolean',
        'version_array' => 'array',
        'modules' => 'array',
        'storage_engines' => 'array',
        'start_time' => 'datetime',
    ];
}
