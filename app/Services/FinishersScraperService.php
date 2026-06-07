<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use App\Models\Event;
use App\Services\GeolocationService;

class FinishersScraperService
{
    protected $baseUrl = 'https://www.finishers.com';
    protected $geolocationService;
    protected $imageDownloader;

    public function __construct(GeolocationService $geolocationService, ImageDownloaderService $imageDownloader)
    {
        $this->geolocationService = $geolocationService;
        $this->imageDownloader = $imageDownloader;
    }

    public function importEvents()
    {
        $targetUrl = 'https://www.finishers.com/en/events?query=Malaysia';

        Log::info("Finishers: Scraping $targetUrl");

        try {
            $html = Browsershot::url($targetUrl)
                ->setOption('args', ['--disable-web-security', '--no-sandbox'])
                ->windowSize(1920, 1080)
                ->waitUntilNetworkIdle()
                ->bodyHtml();

            $crawler = new Crawler($html);
            $events = [];

            // Selectors based on finishers_debug.html
            // Card: a.style_EventPreview__kWm7L
            $crawler->filter('a.style_EventPreview__kWm7L')->each(function ($node) use (&$events) {
                try {
                    $name = $node->filter('.typo-h4')->count() ? $node->filter('.typo-h4')->text() : null;
                    $url = $node->attr('href');
                    if ($url && !str_starts_with($url, 'http')) {
                        $url = 'https://www.finishers.com' . $url; // Handle relative URLs
                    }

                    $dateText = $node->filter('.style_Date__UbJPv')->count() ? $node->filter('.style_Date__UbJPv')->text() : null;
                    // Date format example: "Sun, February 1, 2026" or "30 Fri ➜ January 31, 2026"
                    // We need to parse this. 
                    $date = $this->parseDate($dateText);

                    $location = $node->filter('.style_City__mrIOD span')->count() ? $node->filter('.style_City__mrIOD span')->text() : 'Malaysia';

                    $imgNode = $node->filter('img.style_Picture__fb5T3');
                    $image = $imgNode->count() ? $imgNode->attr('src') : null;

                    $distances = $node->filter('.styles_Distances__GLSe1 span')->each(function ($n) {
                        return $n->text();
                    });
                    $description = "Distances: " . implode(', ', $distances);

                    if ($name && $url) {
                        $events[] = [
                            'name' => $name,
                            'url' => $url,
                            'start_date' => $date ? $date->toDateTimeString() : null,
                            'image' => $image,
                            'location' => $location,
                            'description' => $description
                        ];
                    }
                } catch (\Exception $e) {
                    Log::warning("Finishers parsing error: " . $e->getMessage());
                }
            });

            Log::info("Finishers: Parsed " . count($events) . " events.");

            Log::info("Finishers: Parsed " . count($events) . " events.");

            $stats = ['created' => 0, 'updated' => 0];

            foreach ($events as $data) {
                // Strict Location Validation
                $coordinates = $this->geolocationService->getCoordinates($data['location']);

                if (!$coordinates) {
                    Log::info("Finishers: Skipped event '{$data['name']}' - Location validation failed (Not in Malaysia or invalid).");
                    continue;
                }

                $event = Event::updateOrCreate(
                    ['source_url' => $data['url']],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'],
                        'event_date' => $data['start_date'] ?? now(),
                        'event_time' => '09:00:00',
                        'category' => 'running',
                        'image' => $this->imageDownloader->downloadOrFallback(
                            $data['image'], 
                            'running', 
                            Str::slug($data['name'])
                        ),
                        'location' => $data['location'], // Or use $coordinates['formatted_address']
                        'latitude' => $coordinates['latitude'],
                        'longitude' => $coordinates['longitude'],
                        'source' => 'finishers',
                        'status' => 'upcoming', // Default status
                        'created_by' => 1 // Default admin
                    ]
                );

                if ($event->wasRecentlyCreated) {
                    $stats['created']++;
                } else {
                    $stats['updated']++;
                }
            }
            return $stats;

        } catch (\Exception $e) {
            Log::error("Finishers Scraper Error: " . $e->getMessage());
            return ['created' => 0, 'updated' => 0];
        }
    }

    private function parseDate($dateText)
    {
        if (!$dateText)
            return null;

        try {
            $cleanDate = preg_replace('/(Sun|Mon|Tue|Wed|Thu|Fri|Sat),?\s*/i', '', $dateText);

            if (str_contains($dateText, '➜')) {
                $parts = explode('➜', $dateText);
                $endPart = trim($parts[1]);
                $startPart = trim($parts[0]);

                $endDate = Carbon::parse($endPart);

                if (preg_match('/(\d+)/', $startPart, $m)) {
                    $day = $m[1];
                    $startDate = $endDate->copy()->day($day);
                    return $startDate;
                }
                return $endDate;
            }

            return Carbon::parse($cleanDate);
        } catch (\Exception $e) {
            return null;
        }
    }
}
