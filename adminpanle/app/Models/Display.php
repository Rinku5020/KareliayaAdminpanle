<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Display extends Model
{
    use HasFactory;

    protected $fillable = [
        'displayName',
        'displayMode',
        'timeZone',
        'tags',
        'status',
        'displayId',
        'store_id',
        'account_id',
    ];

    protected $casts = [
        'tags' => 'array', 
    ];
}
