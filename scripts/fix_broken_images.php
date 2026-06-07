<?php

use Illuminate\Support\Facades\Storage;

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$downloader = app(\App\Services\ImageDownloaderService::class);

// Find potential broken events (simple heuristic + what we found in audit)
$events = App\Models\Event::all();
$fixedCount = 0;

echo "Repairing broken images...\n";
echo str_repeat('-', 50) . "\n";

foreach ($events as $event) {
    $needsFix = false;
    $url = $event->image;

    // 1. Is it empty?
    if (empty($url)) {
        $needsFix = true;
    }
    // 2. Is it a broken local file?
    elseif (!str_starts_with($url, 'http') && !file_exists(public_path('storage/' . $url))) {
        echo "[MISSING LOCAL] $event->name ($url)\n";
        $needsFix = true;
    }
    // 3. Is it a known broken external URL? (Checking headers again is slow, relies on earlier audit)
    // For this pass, we focus on the "Known Missing" fallback paths we saw in audit
    elseif (str_contains($url, 'fallbacks/redesign')) {
        echo "[BAD FALLBACK] $event->name ($url)\n";
        $needsFix = true;
    }

    if ($needsFix) {
        $slug = \Illuminate\Support\Str::slug($event->name);
        // Try to re-download if we have source URL, or just set fallback
        // Since we don't easy have the original image URL unless we re-scrape, 
        // we will default to the CATEGORY fallback to ensure it works.
        // If we want to be smart, we could try to look at $event->source_url if it helps, but scraping is complex here.

        $newPath = $downloader->getFallback($event->category);

        $event->image = $newPath;
        $event->save();
        echo " -> FIXED: Set to $newPath\n";
        $fixedCount++;
    }
}

echo str_repeat('-', 50) . "\n";
echo "Total events repaired: $fixedCount\n";
