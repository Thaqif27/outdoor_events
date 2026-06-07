<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Services\GeolocationService;

class CleanupForeignEvents extends Command
{
    protected $signature = 'events:cleanup-foreign';
    protected $description = 'Delete events that are not in Malaysia';

    public function handle(GeolocationService $geoService)
    {
        $this->info('Starting foreign event cleanup...');

        $events = Event::where('status', 'upcoming')->get();
        $bar = $this->output->createProgressBar(count($events));

        $deleted = 0;

        foreach ($events as $event) {
            // 1. Simple String Check first (Optimization)
            if (!stripos($event->location, 'Malaysia') && !stripos($event->location, 'Kuala Lumpur') && !stripos($event->location, 'Selangor')) {
                // If it doesn't even say Malaysia, it's suspicious. Let's strict check.
            }

            // 2. Strict Geolocation Check
            // The GeolocationService now returns NULL if not in Malaysia
            $coords = $geoService->getCoordinates($event->location);

            if (!$coords) {
                $this->warn("\nDeleting foreign/invalid event: {$event->name} ({$event->location})");
                $event->delete();
                $deleted++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nCleanup complete. Deleted $deleted foreign events.");
    }
}
