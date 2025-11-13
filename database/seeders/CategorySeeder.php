<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category; // Import model Category

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Bayi', 'slug' => 'bayi']);
        Category::create(['name' => 'Remaja', 'slug' => 'remaja']);
        Category::create(['name' => 'Dewasa', 'slug' => 'dewasa']);
        Category::create(['name' => 'Lansia', 'slug' => 'lansia']);
    }
}
