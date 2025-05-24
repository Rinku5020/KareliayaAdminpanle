<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;



    protected $fillable = [
    'device_token',
    'unique_code',
    'log_date',
    'action',
    'message',
    'updated_at'
    ];

  
}
