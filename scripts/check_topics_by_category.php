<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\TopicsExplorerController;

$controller = new TopicsExplorerController();
$view = $controller->index();
$data = $view->getData();

echo "Categories keys: \n";
print_r(array_keys($data['categories']));

echo "TopicCategories keys passed to dashboard originally are not here; showing topicsByCategory keys...\n";
print_r(array_keys($data['topicsByCategory']));

if (isset($data['topicsByCategory']['lansia'])) {
    echo "Lansia items:\n";
    print_r(array_keys($data['topicsByCategory']['lansia']));
    print_r($data['topicsByCategory']['lansia']);
} else {
    echo "No lansia key present in topicsByCategory\n";
}
