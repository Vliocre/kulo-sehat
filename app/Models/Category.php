<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const BMI_CATEGORIES = [
        ['name' => 'Kurus', 'slug' => 'kurus'],
        ['name' => 'Ideal', 'slug' => 'ideal'],
        ['name' => 'Gemuk', 'slug' => 'gemuk'],
        ['name' => 'Obesitas', 'slug' => 'obesitas'],
    ];

    // Izinkan kolom 'name' dan 'slug' untuk diisi secara massal.
    protected $fillable = ['name', 'slug'];

    public function scopeForBmi($query)
    {
        return $query->whereIn('slug', $this->bmiSlugs());
    }

    public static function bmiSlugs(): array
    {
        return array_column(self::BMI_CATEGORIES, 'slug');
    }

    /**
     * Mendefinisikan relasi "One-to-Many": Satu Kategori memiliki banyak Artikel.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
