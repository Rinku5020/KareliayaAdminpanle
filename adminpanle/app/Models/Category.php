<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    protected $fillable = [
        'account_id',
        'logo_id',
        'name',
        'status',
    ];


}
