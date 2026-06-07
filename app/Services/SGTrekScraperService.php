<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Symfony\Component\DomCrawler\Crawler;
use Spatie\Browsershot\Browsershot;
use App\Models\Event;
use App\Services\GeolocationService;
use Carbon\Carbon;

class SGTrekScraperService
{
    protected $baseUrl = 'https://sgtrek.com';
    protected $geolocationService;
    protected $imageDownloader;
    protected $filterService;

    public function __construct(GeolocationService $geolocationService, ImageDownloaderService $imageDownloader, EventFilterService $filterService)
    {
        $this->geolocationService = $geolocationService;
        $this->imageDownloader = $imageDownloader;
        $this->filterService = $filterService;
    }

    public function importEvents()
    {
        // Search for Malaysia events
        $targetUrl = 'https://sgtrek.com/page/1/?s=malaysia';
        // Note: Added /page/1/ to ensure consistent starting point, though ?s=malaysia defaults to page 1.

        Log::info("SGTrek: Scraping $targetUrl");

        $stats = ['created' => 0, 'updated' => 0];

        try {
            $html = Browsershot::url($targetUrl)
                ->setOption('args', ['--disable-web-security', '--no-sandbox'])
                ->windowSize(1920, 1080)
                ->waitUntilNetworkIdle()
                ->bodyHtml();

            // Still dump debug for reference if needed
            // File::put(storage_path('logs/sgtrek_debug.html'), $html); 

            $events = $this->scrapeEvents($html);

            foreach ($events as $data) {
                // Determine category using strict filter
                $category = $this->filterService->analyzeEvent($data['name'], $data['description'] ?? '');

                if (is_null($category)) {
                    continue; // Skip irrelevant
                }

                // Check if event already exists
                $event = Event::updateOrCreate(
                    [
                        'source_url' => $data['url'],
                    ],
                    [
                        'name' => $data['name'],
                        'description' => $data['description'] . "\n\nSource: SGTrek",
                        'event_date' => $data['start_date'],
                        'event_time' => '09:00:00', // Default time
                        'category' => $category,
                        'image' => $this->imageDownloader->downloadOrFallback($data['image'], $category, \Illuminate\Support\Str::slug($data['name'])),
                        'location' => $data['location'],
                        'source' => 'sgtrek',
                        'created_by' => 1,
                    ]
                );

                // Strict Location Check from Name (since SGTrek defaults location to Malaysia)
                // If the Title is "Trip to Japan", geocoding the title usually finds Japan.
                $geoCheck = $this->geolocationService->getCoordinates($data['name']);
                if ($geoCheck && $geoCheck['country'] !== 'Malaysia') {
                    Log::info("SGTrek: Removed '{$data['name']}' - Detected generic location outside Malaysia.");
                    $event->delete(); // Delete if we just created it and realized it's foreign
                    continue;
                }

                if ($event->wasRecentlyCreated) {
                    $stats['created']++;
                } else {
                    $stats['updated']++;
                }
            }

            Log::info("SGTrek: Created {$stats['created']}, Updated {$stats['updated']}");

            return $stats;

        } catch (\Exception $e) {
            Log::error("SGTrek Scraper Error: " . $e->getMessage());
            return $stats;
        }
    }

    private function scrapeEvents($html)
    {
        $crawler = new Crawler($html);
        $events = [];

        $crawler->filter('.post-list-style-1')->each(function ($node) use (&$events) {
            try {
                $titleNode = $node->filter('.title a');
                if ($titleNode->count() === 0)
                    return;

                $name = $titleNode->text();
                $url = $titleNode->attr('href');

                // Image
                $imgNode = $node->filter('.image img');
                $image = $imgNode->count() ? ($imgNode->attr('data-src') ?: $imgNode->attr('src')) : null;

                // Date Parsing from Excerpt
                $excerpt = $node->filter('.excerpt')->count() ? $node->filter('.excerpt')->text() : '';
                $date = null;

                if (preg_match('/EVENT DATE:\s*(.*?)(?:\.\.\.|$|<)/i', $excerpt, $matches)) {
                    $dateString = trim($matches[1]);
                    // Clean up date string e.g., "15 Apr – 20 April 2025"
                    // Take the first part before any dash or '–'
                    $dateParts = preg_split('/[-–]/', $dateString);
                    $startDateStr = trim($dateParts[0]);

                    // If start date doesn't have year, try to get it from the end string or current year
                    // But usually "15 Apr - 20 April 2025" implies 2025.
                    // If $startDateStr is "15 Apr", we might need to append the year from the second part.

                    // Check if year is in the start string
                    if (!preg_match('/\d{4}/', $startDateStr) && isset($dateParts[1])) {
                        if (preg_match('/(\d{4})/', $dateParts[1], $yearMatches)) {
                            $startDateStr .= ' ' . $yearMatches[1];
                        } else {
                            $startDateStr .= ' ' . date('Y'); // Fallback to current year
                        }
                    }

                    try {
                        $date = Carbon::parse($startDateStr);
                    } catch (\Exception $e) {
                        // ignore
                    }
                }

                if (!$date) {
                    $postDateText = $node->filter('.post-information .date')->count() ? $node->filter('.post-information .date')->text() : null;
                    if ($postDateText) {
                        try {
                            $date = Carbon::parse($postDateText);
                        } catch (\Exception $e) {
                        }
                    }
                }

                if ($name && $url) {
                    $events[] = [
                        'name' => $name,
                        'url' => $url,
                        'image' => $image,
                        'start_date' => $date ? $date->toDateTimeString() : null,
                        'location' => 'Malaysia', // Default location
                        'description' => $excerpt
                    ];
                }

            } catch (\Exception $e) {
                Log::warning("SGTrek scraping error for a node: " . $e->getMessage());
            }
        });

        return $events;
    }
}
