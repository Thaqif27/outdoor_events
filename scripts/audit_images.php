<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$events = App\Models\Event::whereNotNull('image')->get();

echo "Auditing " . $events->count() . " event images...\n";
echo str_repeat('-', 60) . "\n";

$broken = 0;
$missing = 0;
$relative = 0;
$valid = 0;

foreach ($events as $event) {
    $url = $event->image;

    // Check if relative path (starts with / or plain filename)
    if (!str_starts_with($url, 'http')) {
        // If it's a local file (uploaded), check existence
        if (file_exists(public_path('storage/' . $url))) {
            // echo "[LOCAL OK] $event->name\n";
            $valid++;
        } else {
            echo "[LOCAL MISSING] [$event->id] $event->name : $url\n";
            $missing++;
        }
        continue;
    }

    // Check external URL
    $headers = @get_headers($url);
    if (!$headers || strpos($headers[0], '200') === false) {
        $status = $headers ? $headers[0] : 'Connection Failed';
        echo "[BROKEN URL] [$event->id] ($status) $event->name : $url\n";
        $broken++;
    } else {
        $valid++;
    }
}

echo str_repeat('-', 60) . "\n";
echo "Summary:\n";
echo "Valid: $valid\n";
echo "Broken External URLs: $broken\n";
echo "Missing Local Files: $missing\n";

// Also check for NULL images
$nullImages = App\Models\Event::whereNull('image')->orWhere('image', '')->count();
echo "Events with NO Image: $nullImages\n";
