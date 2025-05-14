<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'logo',
        'status',
        'storeId',
        'name',
        'email',
        'phone',
        'country',
        'state',
        'city',
        'pincode',
        'address',
    ];
}
