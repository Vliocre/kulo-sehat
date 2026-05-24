<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TopicGuide;

$guides = TopicGuide::all(['id','category_slug','topic_slug','title'])->toArray();
echo json_encode($guides, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
