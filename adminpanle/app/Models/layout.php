<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class layout extends Model
{
    protected $fillable = [
        'unique_id',
        'layoutName',
        'store_id',
        'displayMode',
        'playlistName',
        'address',
        'logo',
        'zone1',
        'zone2',
        'zone3',
        'zone4',
        'status',
        'selectedDisplays'

    ];

}
