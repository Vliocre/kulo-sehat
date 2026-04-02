<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Keluhan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'gambar',
        'jawaban',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
