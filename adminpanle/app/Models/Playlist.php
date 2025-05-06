<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'theme_id',
        'playlist_name',
        'display_mode',
        'display_size',
        'display_status',
        'static_address',
        'schedule_type',
        'status',
        'unique_code',
        'logo_id',
        'recurring',
        'stores',
        'selected_displays',
        'zone1',
        'zone2',
        'zone3',
        'zone4'
    ];

    protected $casts = [
        'display_status' => 'boolean',
        'status' => 'boolean',
        'recurring' => 'array',
        'stores' => 'array',
        'selected_displays' => 'array',
        'zone1' => 'array',
        'zone2' => 'array',
        'zone3' => 'array',
        'zone4' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


}
