<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Services\GeolocationService;

class BackfillCoordinates extends Command
{
    protected $signature = 'events:backfill-coordinates';
    protected $description = 'Fetch and save precise coordinates for ALL upcoming events using Name+Location';

    public function handle(GeolocationService $geoService)
    {
        // process ALL upcoming events to ensure high precision, even if they already have coords
        $events = Event::where('status', 'upcoming')->get();

        $this->info("Refining locations for " . $events->count() . " events using precise Name+Location query...");
        $bar = $this->output->createProgressBar($events->count());
        $updated = 0;
        $failed = 0;

        foreach ($events as $event) {
            // Strategy: Combine Name + Location for higher precision
            // Example: "Fun Run" + "Cyberjaya" = "Fun Run Cyberjaya" -> Specific Start Point
            $query = $event->name . ' ' . $event->location;

            // Clean up to remove confusing chars
            $query = str_replace(['|', ' - '], ' ', $query);

            $coords = $geoService->getCoordinates($query);

            if ($coords) {
                $event->latitude = $coords['latitude'];
                $event->longitude = $coords['longitude'];
                $event->save();
                $updated++;
            } else {
                // Fallback: Try just location if Name+Location failed
                $coordsFallback = $geoService->getCoordinates($event->location);
                if ($coordsFallback) {
                    $event->latitude = $coordsFallback['latitude'];
                    $event->longitude = $coordsFallback['longitude'];
                    $event->save();
                    $updated++;
                } else {
                    $failed++;
                }
            }

            // Rate limit compliance
            usleep(250000); // 0.25s

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nPrecision Update Complete. Refined $updated events. Failed $failed.");
    }
}
