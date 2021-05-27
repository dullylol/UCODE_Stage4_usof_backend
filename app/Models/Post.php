<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'title' => 'string',
        'content' => 'string',
        'status' => 'string',
        'user_id' => 'integer',
        'category_id' => 'integer',
    ];
}
