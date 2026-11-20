<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorWithdrawalRequest extends Model
{
    public const MONTHLY_LIMIT = 200000;

    public const STATUS_PENDING = 'menunggu';
    public const STATUS_APPROVED = 'disetujui';
    public const STATUS_REJECTED = 'ditolak';

    protected $fillable = [
        'doctor_id',
        'amount',
        'status',
        'admin_note',
        'processed_by',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'processed_at' => 'datetime',
        ];
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
