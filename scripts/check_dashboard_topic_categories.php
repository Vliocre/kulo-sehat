<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Article;
use App\Models\TopicGuide;

$latestArticles = Article::where('status', 'published')->latest()->take(3)->get();
$allSlugs = TopicGuide::distinct()->pluck('category_slug')->all();
$categoriesMap = [
    'bayi' => 'Bayi',
    'remaja' => 'Remaja',
    'dewasa' => 'Dewasa',
    'lansia' => 'Lansia',
];
$topicCategories = [];
foreach ($allSlugs as $s) {
    $topicCategories[$s] = $categoriesMap[$s] ?? ucfirst($s);
}

print_r($topicCategories);
