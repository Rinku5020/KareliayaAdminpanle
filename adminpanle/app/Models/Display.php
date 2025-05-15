<?php

namespace App\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Model;

class Display extends Model
{
    protected $fillable = [
        'display_id',
        'name',
        'tags',
        'store_id',
        'time_zone',
        'display_mode',
        'country',
        'state',
        'city',
        'address',
    ];

    // Define the relationship here
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}

