<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Verifycode extends Model
{
    protected $fillable = [
        'unique_code',
        'ip_address',
        'ipv4_address',
        'device_token',
        'device_brand',
        'display',
    ];

    protected $casts = [
        'display' => 'array',
    ];
}
