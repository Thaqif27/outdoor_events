<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;
use App\Models\Event;
use App\Services\GeolocationService;
use App\Services\ImageDownloaderService;
use App\Services\EventFilterService;

class MeetupScraperService
{
    protected $baseUrl = 'https://www.meetup.com';
    protected $geolocationService;
    protected $filterService;

    public function __construct(GeolocationService $geolocationService, EventFilterService $filterService)
    {
        $this->geolocationService = $geolocationService;
        $this->filterService = $filterService;
    }

    public function importEvents()
    {
        // Searching for 'running' in Malaysia (Kuala Lumpur as center)
        $targetUrl = 'https://www.meetup.com/find/?keywords=running&location=my--Kuala%20Lumpur&source=EVENTS';

        Log::info("Meetup: Scraping $targetUrl");

        try {
            $html = Browsershot::url($targetUrl)
                ->setOption('args', ['--disable-web-security', '--no-sandbox'])
                ->windowSize(1920, 1080)
                ->blockUrls(['*google-analytics*', '*doubleclick*', '*googletagmanager*', '*facebook*', '*criteo*'])
                ->setOption('waitUntil', 'domcontentloaded')
                ->timeout(60)
                ->bodyHtml();
            $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
            $events = [];

            // Parse JSON-LD
            $scriptTags = $crawler->filter('script[type="application/ld+json"]');

            Log::info("Meetup: Found " . $scriptTags->count() . " script tags with JSON-LD.");

            if ($scriptTags->count() > 0) {
                $scriptTags->each(function ($node) use (&$events) {
                    try {
                        $jsonContent = $node->text();
                        $jsonData = json_decode($jsonContent, true);

                        $items = [];
                        if (is_array($jsonData)) {
                            // Check if the root is an array or object
                            if (isset($jsonData['@type'])) {
                                $items[] = $jsonData;
                            } else {
                                $items = $jsonData;
                            }

                            foreach ($items as $item) {
                                if (isset($item['@type']) && $item['@type'] === 'Event') {
                                    $events[] = [
                                        'name' => $item['name'] ?? 'Unknown Event',
                                        'url' => $item['url'] ?? '',
                                        'start_date' => isset($item['startDate']) ? \Carbon\Carbon::parse($item['startDate']) : null,
                                        'image' => $item['image'] ?? null,
                                        'location' => $item['location']['address']['addressLocality'] ?? 'Kuala Lumpur',
                                        'description' => $item['description'] ?? '',
                                    ];
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // Ignore
                    }
                });
            }

            Log::info("Meetup: Parsed " . count($events) . " events from JSON-LD.");

            $stats = ['created' => 0, 'updated' => 0];

            foreach ($events as $data) {
                if (empty($data['url']))
                    continue;

                // Determine category using strict filter
                $category = $this->filterService->analyzeEvent($data['name'] ?? '', $data['description'] ?? '');

                if (is_null($category)) {
                    continue; // Skip irrelevant
                }

                $event = Event::updateOrCreate(
                    [
                        'source_url' => $data['url'],
                    ],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'] . "\n\nSource: Meetup",
                        'event_date' => $data['start_date'],
                        'event_time' => $data['start_date'] ? \Carbon\Carbon::parse($data['start_date'])->format('H:i:s') : '09:00:00',
                        'category' => $category,
                        'image' => app(ImageDownloaderService::class)->downloadOrFallback($data['image'], $category, \Illuminate\Support\Str::slug($data['name'])),
                        'location' => $data['location'] ?: 'Kuala Lumpur',
                        'source' => 'meetup',
                        'status' => 'upcoming',
                        'created_by' => 1,
                    ]
                );

                // Strict Location Validation
                $coordinates = $this->geolocationService->getCoordinates($data['location']);
                if (!$coordinates) {
                    Log::info("Meetup: Skipped event '{$data['name']}' - Location validation failed.");
                    continue;
                }

                $event = Event::updateOrCreate(
                    [
                        'source_url' => $data['url'],
                    ],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'] . "\n\nSource: Meetup",
                        'event_date' => $data['start_date'],
                        'event_time' => $data['start_date'] ? \Carbon\Carbon::parse($data['start_date'])->format('H:i:s') : '09:00:00',
                        'category' => $category,
                        'image' => app(ImageDownloaderService::class)->downloadOrFallback($data['image'], $category, \Illuminate\Support\Str::slug($data['name'])),
                        'location' => $data['location'] ?: 'Kuala Lumpur',
                        'latitude' => $coordinates['latitude'],
                        'longitude' => $coordinates['longitude'],
                        'source' => 'meetup',
                        'status' => 'upcoming',
                        'created_by' => 1,
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
            Log::error("Meetup Scraper Error: " . $e->getMessage());
            return ['created' => 0, 'updated' => 0];
        }
    }
}
