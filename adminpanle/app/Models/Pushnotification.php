<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pushnotification extends Model
{
    use HasFactory;

    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'content',
        'display_ids',
        'read_by'
    ];

    protected $attributes = [
        'display_ids' => '[]', // Default as JSON string
        'read_by' => '[]'      // Default as JSON string
    ];

    protected $casts = [
        'display_ids' => 'array',
        'read_by' => 'array'
    ];

}
