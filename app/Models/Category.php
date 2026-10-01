<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const ARTICLE_CATEGORIES = [
        ['name' => 'Bayi', 'slug' => 'bayi'],
        ['name' => 'Remaja', 'slug' => 'remaja'],
        ['name' => 'Dewasa', 'slug' => 'dewasa'],
        ['name' => 'Lansia', 'slug' => 'lansia'],
    ];

    // Izinkan kolom 'name' dan 'slug' untuk diisi secara massal.
    protected $fillable = ['name', 'slug'];

    public function scopeForArticles($query)
    {
        return $query->whereIn('slug', $this->articleSlugs());
    }

    public function scopeForBmi($query)
    {
        return $query->forArticles();
    }

    public static function articleSlugs(): array
    {
        return array_column(self::ARTICLE_CATEGORIES, 'slug');
    }

    public static function bmiSlugs(): array
    {
        return self::articleSlugs();
    }

    /**
     * Mendefinisikan relasi "One-to-Many": Satu Kategori memiliki banyak Artikel.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
