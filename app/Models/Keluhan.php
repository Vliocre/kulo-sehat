<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Keluhan extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'jenis',
        'judul',
        'isi',
        'gambar',
        'jawaban',
        'status',
        'premium_status',
        'premium_started_at',
        'premium_ended_at',
    ];

    protected function casts(): array
    {
        return [
            'premium_started_at' => 'datetime',
            'premium_ended_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function pesanPremium()
    {
        return $this->hasMany(KeluhanPremium::class)->oldest();
    }

    public function isPremium(): bool
    {
        return $this->jenis === 'premium';
    }
}
