<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feed extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'category_id',
        'logo_id',
        'account_id',
        'status',
        'title',
        'rss_feed_url',
        'rss_read_more_url'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
