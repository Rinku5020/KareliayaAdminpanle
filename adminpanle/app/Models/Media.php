<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
   
    protected $fillable = [
    'account_id',
    'originalname',
    'encoding',
    'mimetype',
    'size',
    'path',
    ];

   
}
