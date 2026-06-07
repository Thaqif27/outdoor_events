<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = App\Models\Event::all();
$updatedCount = 0;

echo "Refining categories for " . $events->count() . " events...\n";
echo str_repeat('-', 50) . "\n";

foreach ($events as $event) {
    if (in_array($event->source, ['sgtrek', 'finishers'])) {
        // Skip sources that have hardcoded reliable categories
        continue;
    }

    $nameLower = strtolower($event->name);
    $currentCategory = $event->category;
    $newCategory = $currentCategory; // Default to keep existing if no match found (or reset to default 'running' if we want strictly enforce)

    // Apply Weighted Logic
    // 1. Cycling
    if (
        str_contains($nameLower, 'cycle') || str_contains($nameLower, 'cycling') ||
        str_contains($nameLower, 'bike') || str_contains($nameLower, 'mtb') ||
        str_contains($nameLower, 'ride') || str_contains($nameLower, 'tour')
    ) {
        $newCategory = 'cycling';
    }
    // 2. Running
    elseif (
        str_contains($nameLower, 'run') || str_contains($nameLower, 'marathon') ||
        str_contains($nameLower, 'jog') || str_contains($nameLower, 'sprint') ||
        str_contains($nameLower, 'ultra')
    ) {
        $newCategory = 'running';
    }
    // 3. Hiking
    elseif (
        str_contains($nameLower, 'hike') || str_contains($nameLower, 'hiking') ||
        str_contains($nameLower, 'climb') || str_contains($nameLower, 'trek') ||
        str_contains($nameLower, 'peak') || str_contains($nameLower, 'walk') ||
        str_contains($nameLower, 'trail')
    ) {
        $newCategory = 'hiking';
    }
    // 4. Special cases (Swimming, etc) - optional, for now map to closest or keep original
    elseif (str_contains($nameLower, 'swim')) {
        $newCategory = 'swimming'; // If we support this
    }

    if ($newCategory !== $currentCategory) {
        // Validation: Only update if we are moving to valid categories we support in UI
        if (in_array($newCategory, ['running', 'hiking', 'cycling'])) {
            echo "UPDATE [$event->id] $event->name: '$currentCategory' -> '$newCategory'\n";
            $event->category = $newCategory;
            $event->save();
            $updatedCount++;
        }
    }
}

echo str_repeat('-', 50) . "\n";
echo "Total events updated: $updatedCount\n";
