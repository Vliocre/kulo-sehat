<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <-- PENTING: Tambahkan 'role' di sini.
    ];
    // PENJELASAN $fillable: Ini adalah daftar "izin". Laravel hanya akan
    // mengizinkan kolom-kolom dalam daftar ini untuk diisi secara otomatis
    // dari sebuah form. Ini adalah fitur keamanan untuk mencegah pengisian
    // kolom yang tidak seharusnya (seperti kolom `is_admin` jika ada).

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
        ];
    }

    // --- MULAI PENAMBAHAN KODE KITA ---

    /**
     * Mendefinisikan relasi "One-to-Many": Satu User memiliki banyak Artikel.
     * Nama fungsi `articles()` (jamak) adalah konvensi untuk relasi one-to-many.
     */
    public function articles()
    {
        // `hasMany` berarti "memiliki banyak".
        return $this->hasMany(Article::class);
    }

    /**
     * Fungsi pembantu (helper) untuk membuat pengecekan role lebih bersih.
     * Daripada menulis `if (auth()->user()->role == 'dokter')`,
     * kita bisa menulis `if (auth()->user()->isDokter())`.
     */
    public function isDokter()
    {
        return $this->role === 'dokter';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
