<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Illness extends Model
{
    protected $fillable = [
        'name',
        'description',
        'age_category'
    ];
}
