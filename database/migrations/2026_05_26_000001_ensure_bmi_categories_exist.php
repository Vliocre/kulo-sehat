<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach ([
            ['name' => 'Bayi', 'slug' => 'bayi'],
            ['name' => 'Remaja', 'slug' => 'remaja'],
            ['name' => 'Dewasa', 'slug' => 'dewasa'],
            ['name' => 'Lansia', 'slug' => 'lansia'],
        ] as $category) {
            $exists = DB::table('categories')
                ->where('slug', $category['slug'])
                ->exists();

            if ($exists) {
                DB::table('categories')
                    ->where('slug', $category['slug'])
                    ->update([
                        'name' => $category['name'],
                        'updated_at' => $now,
                    ]);

                continue;
            }

            DB::table('categories')->insert([
                    'name' => $category['name'],
                    'slug' => $category['slug'],
                    'updated_at' => $now,
                    'created_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        //
    }
};
