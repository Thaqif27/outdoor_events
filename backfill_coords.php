<?php

use App\Models\Event;
use App\Services\GeolocationService;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$geo = app(GeolocationService::class);

Event::whereNull('latitude')->orWhereNull('longitude')->chunk(10, function ($events) use ($geo) {
    foreach ($events as $event) {
        echo "Updating event: {$event->name} ({$event->location})...";
        $coords = $geo->getCoordinates($event->location);
        if ($coords) {
            $event->update($coords);
            echo " DONE (Lat: {$coords['latitude']}, Lng: {$coords['longitude']})\n";
        } else {
            echo " FAILED (No coordinates found)\n";
        }
        sleep(1); // Avoid rate limiting
    }
});

echo "Backfill complete.\n";
