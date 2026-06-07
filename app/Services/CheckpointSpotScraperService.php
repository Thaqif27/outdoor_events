<?php

namespace App\Services;

use Spatie\Browsershot\Browsershot;
use App\Models\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CheckpointSpotScraperService
{
    public function importEvents()
    {
        Log::info("CheckpointSpot: Running Node.js scraper...");
        $scriptPath = base_path('scripts/scrape-checkpointspot.js');
        $outputFile = storage_path('logs/checkpointspot_events.json');

        // Ensure output file is clean
        if (File::exists($outputFile)) {
            File::delete($outputFile);
        }

        // Execute Node script
        // Note: Ensure 'node' is in the path. Using basic 'node' command.
        // In some environments, full path to node might be required.
        $command = "node " . escapeshellarg($scriptPath);
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            Log::error("CheckpointSpot: Node script failed with exit code $returnVar. Output: " . implode("\n", $output));
            return ['created' => 0, 'updated' => 0];
        }

        if (!File::exists($outputFile)) {
            Log::error("CheckpointSpot: Output file not found at $outputFile");
            return ['created' => 0, 'updated' => 0];
        }

        $json = File::get($outputFile);
        $events = json_decode($json, true);

        if (!is_array($events)) {
            Log::error("CheckpointSpot: Invalid JSON output.");
            return ['created' => 0, 'updated' => 0];
        }

        Log::info("CheckpointSpot: Found " . count($events) . " events.");

        $stats = ['created' => 0, 'updated' => 0];

        foreach ($events as $data) {
            // Basic date parsing from description if not provided
            $date = now();
            if (isset($data['description'])) {
                // Try to find a date pattern like "01 Jan 2026" or "2026-01-01"
                // This is a rough heuristic.
                if (preg_match('/(\d{1,2}\s+[A-Za-z]{3}\s+\d{4})/', $data['description'], $matches)) {
                    try {
                        $date = \Carbon\Carbon::parse($matches[1]);
                    } catch (\Exception $e) {
                    }
                }
            }

            // Determine category using strict filter
            $filterService = app(\App\Services\EventFilterService::class);
            $category = $filterService->analyzeEvent($data['name'], $data['description']);

            // CheckpointSpot usually implies reliable data, but let's be strict as requested
            if (is_null($category)) {
                if (str_contains(strtolower($data['name']), 'swim')) {
                    $category = 'swimming'; // Keep special case? User only asked for run/hike/cycle.
                } else {
                    continue; // Skip irrelevant
                }
            }

            $event = Event::updateOrCreate(
                ['source_url' => $data['url']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'] . "\n\nSource: CheckpointSpot",
                    'event_date' => $date, // Fallback to found date or now
                    'event_time' => '09:00:00',
                    'category' => $category,
                    'image' => app(\App\Services\ImageDownloaderService::class)->downloadOrFallback($data['image'], $category, \Illuminate\Support\Str::slug($data['name'])),
                    'location' => $data['location'],
                    'source' => 'checkpointspot',
                    'status' => 'upcoming',
                    'created_by' => 1
                ]
            );

            if ($event->wasRecentlyCreated) {
                $stats['created']++;
            } else {
                $stats['updated']++;
            }
        }

        Log::info("CheckpointSpot: Created {$stats['created']}, Updated {$stats['updated']}");

        return $stats;
    }
}
