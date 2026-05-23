<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\TopicsExplorerController;

// Render topics-all
$topicsController = new TopicsExplorerController();
$viewTopics = $topicsController->index();
$htmlTopics = $viewTopics->render();
$hasPinggang = strpos($htmlTopics, 'sakit pinggang') !== false ? 'yes' : 'no';

// Render dashboard view by simulating route closure
// We'll require the dashboard view directly
$latestArticles = App\Models\Article::where('status','published')->latest()->take(3)->get();
$allSlugs = App\Models\TopicGuide::distinct()->pluck('category_slug')->all();
$categoriesMap = ['bayi'=>'Bayi','remaja'=>'Remaja','dewasa'=>'Dewasa','lansia'=>'Lansia'];
$topicCategories = [];
foreach ($allSlugs as $s) { $topicCategories[$s] = $categoriesMap[$s] ?? ucfirst($s); }

$dashboardView = view('dashboard', compact('latestArticles','topicCategories'));
$htmlDashboard = $dashboardView->render();
$hasLansiaOption = strpos($htmlDashboard, 'value="lansia"') !== false ? 'yes' : 'no';

echo "topics-all contains 'sakit pinggang'? ".$hasPinggang.PHP_EOL;
echo "dashboard has option lansia? ".$hasLansiaOption.PHP_EOL;
