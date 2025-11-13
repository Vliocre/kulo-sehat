<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // Daftarkan semua kolom yang bisa diisi dari form.
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'user_id',      // Foreign key
        'category_id',  // Foreign key
    ];

    /**
     * Mendefinisikan relasi "belongsTo": Satu Artikel dimiliki oleh satu User.
     * Nama fungsi `author()` (tunggal) adalah konvensi untuk relasi belongsTo.
     * Kita menamakannya 'author' agar lebih deskriptif daripada 'user'.
     */
    public function author()
    {
        // `belongsTo` artinya "dimiliki oleh".
        // Argumen kedua ('user_id') adalah nama foreign key di tabel `articles`.
        // Walaupun Laravel seringkali bisa menebaknya, menuliskannya secara eksplisit
        // membuat kode lebih jelas.
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendefinisikan relasi "belongsTo": Satu Artikel termasuk dalam satu Kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
