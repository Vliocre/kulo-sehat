<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryLandingController extends Controller
{
    public function show(string $slug)
    {
        $categorySlug = strtolower($slug);

        $names = [
            'bayi' => 'Bayi',
            'remaja' => 'Remaja',
            'dewasa' => 'Dewasa',
            'lansia' => 'Lansia',
        ];

        $cardsMap = [
            'bayi' => [
                [
                    'slug' => 'flu',
                    'label' => 'Flu',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M4.5 10c0-2.2 2.3-4 7.5-4s7.5 1.8 7.5 4v1.25A4.75 4.75 0 0 1 14.75 16h-5.5A4.75 4.75 0 0 1 4.5 11.25V10Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M4.75 11.25 3 12.5M19.25 11.25 21 12.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 11h6" stroke-linecap="round"/><path d="M9 9h.01M15 9h.01" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                ],
                [
                    'slug' => 'demam',
                    'label' => 'Demam',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><circle cx="11" cy="12" r="6.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 14.5c.7.6 1.7 1 2.5 1s1.8-.4 2.5-1" stroke-linecap="round" stroke-linejoin="round"/><path d="M8.5 10.5h.01M12 10.5h.01" stroke-linecap="round" stroke-linejoin="round"/><path d="M16.8 4.2c.7-.7 1.9-.4 2.1.6.2.8-.3 1.9-1.4 2.9-.8-.4-1.5-1-1.8-1.7-.3-.6-.1-1.3.4-1.8Z" fill="currentColor" stroke="none"/><path d="M15.5 9.5c.8-.7 1.5-1.7 1.5-2.6" stroke-linecap="round"/></svg>',
                ],
                [
                    'slug' => 'diare',
                    'label' => 'Diare',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M14 5.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" stroke-linecap="round" stroke-linejoin="round" fill="currentColor"/><path d="M6.5 21 8.2 14l3-2 1.3-3.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M14.5 9.4 12.1 11m0 0 3.4 1.8 1.5 3.8" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 14h3.2" stroke-linecap="round"/><path d="M4.5 18.5h2.5M17 18.5h2" stroke-linecap="round"/></svg>',
                ],
                [
                    'slug' => 'ruam-popok',
                    'label' => 'Ruam Popok',
                    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" class="w-10 h-10"><path d="M4.5 7.5A3.5 3.5 0 0 1 8 4h8a3.5 3.5 0 0 1 3.5 3.5v3A6.5 6.5 0 0 1 13 17h-2A6.5 6.5 0 0 1 4.5 10.5v-3Z" stroke-linecap="round" stroke-linejoin="round"/><path d="M7 9.5h3l-1 2.2L7.5 13M17 9.5h-3l1 2.2 1.5 1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 16.5 8 20M14 16.5l2 3.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
                ],
            ],
            'remaja' => [
                ['slug' => 'akne', 'label' => 'Akne'],
                ['slug' => 'anemia', 'label' => 'Anemia'],
                ['slug' => 'migrain', 'label' => 'Migrain'],
                ['slug' => 'skoliosis', 'label' => 'Skoliosis'],
                ['slug' => 'depresi', 'label' => 'Depresi'],
                ['slug' => 'ansietas', 'label' => 'Ansietas'],
                ['slug' => 'insomnia', 'label' => 'Insomnia'],
                ['slug' => 'gastritis', 'label' => 'Gastritis'],
                ['slug' => 'hepatitis', 'label' => 'Hepatitis'],
                ['slug' => 'obesitas', 'label' => 'Obesitas'],
            ],
            'dewasa' => [
                ['slug' => 'hipertensi', 'label' => 'Hipertensi'],
                ['slug' => 'diabetes', 'label' => 'Diabetes'],
                ['slug' => 'stroke', 'label' => 'Stroke'],
                ['slug' => 'gastritis', 'label' => 'Gastritis'],
                ['slug' => 'hepatitis', 'label' => 'Hepatitis'],
                ['slug' => 'kanker', 'label' => 'Kanker'],
                ['slug' => 'asma', 'label' => 'Asma'],
                ['slug' => 'osteoartritis', 'label' => 'Osteoartritis'],
                ['slug' => 'hiperlipidemia', 'label' => 'Hiperlipidemia'],
                ['slug' => 'nefritis', 'label' => 'Nefritis'],
            ],
            'lansia' => [
                ['slug' => 'demensia', 'label' => 'Demensia'],
                ['slug' => 'alzheimer', 'label' => 'Alzheimer'],
                ['slug' => 'hipertensi', 'label' => 'Hipertensi'],
                ['slug' => 'diabetes', 'label' => 'Diabetes'],
                ['slug' => 'osteoporosis', 'label' => 'Osteoporosis'],
                ['slug' => 'osteoartritis', 'label' => 'Osteoartritis'],
                ['slug' => 'katarak', 'label' => 'Katarak'],
                ['slug' => 'stroke', 'label' => 'Stroke'],
                ['slug' => 'parkinson', 'label' => 'Parkinson'],
                ['slug' => 'insomnia', 'label' => 'Insomnia'],
            ],
        ];

        if (!isset($names[$categorySlug])) {
            abort(404);
        }

        return view('articles.category-landing', [
            'categorySlug' => $categorySlug,
            'categoryName' => $names[$categorySlug],
            'cards' => $cardsMap[$categorySlug] ?? [],
        ]);
    }
}
