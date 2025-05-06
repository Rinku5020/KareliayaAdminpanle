<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auth extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'ip_address',
        'device_type',
        'location',
        'created_at',
        'expires_at',
        'is_active',
    ];
}
