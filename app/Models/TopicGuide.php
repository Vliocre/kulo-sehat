<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_slug',
        'topic_slug',
        'title',
        'summary',
        'symptoms',
        'care',
        'prevention',
        'danger_signs',
        'palette',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'care' => 'array',
        'prevention' => 'array',
        'danger_signs' => 'array',
    ];
}
