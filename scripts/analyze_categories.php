<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = App\Models\Event::select('id', 'name', 'category', 'source_url', 'description')->get();

echo "Total Events: " . $events->count() . "\n\n";

$grouped = $events->groupBy('category');

foreach ($grouped as $category => $items) {
    echo "CATEGORY: " . strtoupper($category) . " (" . $items->count() . ")\n";
    echo str_repeat('-', 50) . "\n";
    foreach ($items->take(20) as $event) { // Show first 20 of each for sampling
        echo "[$event->id] $event->name\n";
    }
    echo "\n";
}

// specific check for ambiguous terms
echo "AMBIGUOUS / POTENTIAL MISMATCHES:\n";
echo str_repeat('=', 50) . "\n";
foreach ($events as $event) {
    $name = strtolower($event->name);
    $cat = $event->category;

    // Check for 'run' in hiking/cycling
    if ($cat != 'running' && (str_contains($name, 'run') || str_contains($name, 'marathon'))) {
        echo "POSSIBLE RUN MISMATCH ($cat): $event->name\n";
    }

    // Check for 'hike' in running/cycling
    if ($cat != 'hiking' && (str_contains($name, 'hike') || str_contains($name, 'trek') || str_contains($name, 'climb'))) {
        echo "POSSIBLE HIKE MISMATCH ($cat): $event->name\n";
    }

    // Check for 'ride' in running/hiking
    if ($cat != 'cycling' && (str_contains($name, 'ride') || str_contains($name, 'cycle') || str_contains($name, 'bike'))) {
        echo "POSSIBLE CYCLE MISMATCH ($cat): $event->name\n";
    }
}
