<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Logic
echo "Normalizing categories...\n";

DB::update("UPDATE events SET category = 'hiking' WHERE (category LIKE '%hike%' OR category LIKE '%hiking%') AND category != 'hiking'");
DB::update("UPDATE events SET category = 'running' WHERE (category LIKE '%run%' OR category LIKE '%running%') AND category != 'running'");
DB::update("UPDATE events SET category = 'cycling' WHERE (category LIKE '%cycle%' OR category LIKE '%cycling%') AND category != 'cycling'");

echo "Done.\n\nVerification Counts:\n";
$counts = App\Models\Event::select('category', \DB::raw('count(*) as count'))->groupBy('category')->get()->toArray();

foreach ($counts as $c) {
    echo "{$c['category']}: {$c['count']}\n";
}
