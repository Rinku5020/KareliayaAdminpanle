<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class layout extends Model
{
   protected $fillable = [
        'layoutName',
        'store_id',
        'displayMode',
        'playlistName',
        'address',
        'logo',
        'Select_zone'
    ];


}
