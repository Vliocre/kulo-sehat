<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <-- Pindahkan ke sini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <-- Gabungkan dalam satu use

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'age',
        'height',
        'weight',
        'password',
        'role',
        'phone',
        'workplace',
        'specialty',
        'about',
        'doctor_idi_number',
        'doctor_str_file',
        'doctor_sip_file',
        'doctor_verification_status',
        'doctor_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'doctor_verified_at' => 'datetime',
        ];
    }

    // --- RELATIONS ---

    /**
     * Relasi ke Article (One-to-Many)
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Relasi ke Keluhan (One-to-Many)
     */
    public function keluhans()
    {
        return $this->hasMany(Keluhan::class);
    }

    // --- ROLE HELPERS ---

    /**
     * Cek apakah user adalah dokter
     */
    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    /**
     * Cek apakah user butuh approval dokter
     */
    public function requiresDoctorApproval()
    {
        return $this->role === 'dokter';
    }

    /**
     * Cek apakah dokter sudah disetujui
     */
    public function isApprovedDoctor()
    {
        return $this->role === 'dokter' && $this->doctor_verification_status === 'approved';
    }

    /**
     * Cek apakah user bisa akses area dokter
     */
    public function canAccessDoctorArea()
    {
        return !$this->requiresDoctorApproval() || $this->isApprovedDoctor();
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}