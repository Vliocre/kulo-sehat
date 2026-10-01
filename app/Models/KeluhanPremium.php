<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluhanPremium extends Model
{
    protected $table = 'keluhan_premiums';

    protected $fillable = [
        'keluhan_id',
        'sender_id',
        'pesan',
        'gambar',
        'dibaca_at',
    ];

    protected function casts(): array
    {
        return [
            'dibaca_at' => 'datetime',
        ];
    }

    public function keluhan()
    {
        return $this->belongsTo(Keluhan::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
