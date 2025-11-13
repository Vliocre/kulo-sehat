<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Izinkan kolom 'name' dan 'slug' untuk diisi secara massal.
    protected $fillable = ['name', 'slug'];

    /**
     * Mendefinisikan relasi "One-to-Many": Satu Kategori memiliki banyak Artikel.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
