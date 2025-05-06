<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_name',
        'image_path',
        'html_name',
        'html_path',
        'created_date'
    ];

    protected $casts = [
        'created_date' => 'datetime'
    ];
}
