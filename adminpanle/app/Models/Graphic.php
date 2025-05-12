<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Graphic extends Model
{

    // use HasFactory, SoftDeletes;

    // protected $fillable = [
    //     'type',
    //     'media_id',
    //     'account_id',
    //     'status',
    //     'name'
    // ];

    // protected $casts = [
    //     'status' => 'boolean',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];
    protected $fillable = ['name', 'type', 'media_id'];
}
