<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Event;
use App\Services\GeolocationService;
use App\Services\ImageDownloaderService;
use App\Services\EventFilterService;
use Carbon\Carbon;

class EventbriteScraperService
{
    protected $baseUrl = 'https://www.eventbrite.com';
    protected $geolocationService;
    protected $imageDownloader;
    protected $filterService;

    public function __construct(GeolocationService $geolocationService, ImageDownloaderService $imageDownloader, EventFilterService $filterService)
    {
        $this->geolocationService = $geolocationService;
        $this->imageDownloader = $imageDownloader;
        $this->filterService = $filterService;
    }

    public function importEvents($targetUrl = null)
    {
        // Eventbrite search/discovery URL for Malaysia (or generic if not specified)
        // Using a general search for 'outdoor' or 'run' in Malaysia if no URL provided.
        $targetUrl = $targetUrl ?? 'https://www.eventbrite.com/d/malaysia/outdoor-events/';

        $stats = ['created' => 0, 'updated' => 0];
        $events = $this->scrapeEvents($targetUrl);

        \Log::info("Eventbrite: Found " . count($events) . " events.");

        foreach ($events as $eventData) {
            // Geocode & Validate Validation (Strict Malaysia Check)
            $coordinates = null;
            if (!empty($eventData['location'])) {
                $coordinates = $this->geolocationService->getCoordinates($eventData['location']);
            }

            if (!$coordinates) {
                // If coordinates are null, it means either Geocoding failed OR it wasn't in Malaysia
                \Log::info("Eventbrite: Skipped event '{$eventData['name']}' - Location validation failed (Not in Malaysia or invalid).");
                continue;
            }

            $eventData['latitude'] = $coordinates['latitude'];
            $eventData['longitude'] = $coordinates['longitude'];

            if (empty($eventData['source_url'])) {
                // Fallback to name check if no source_url (unlikely)
                $event = Event::where('name', $eventData['name'])->first();
                if ($event) {
                    $event->update($eventData);
                    $stats['updated']++;
                } else {
                    Event::create($eventData);
                    $stats['created']++;
                }
            } else {
                $event = Event::updateOrCreate(
                    ['source_url' => $eventData['source_url']],
                    $eventData
                );

                if ($event->wasRecentlyCreated) {
                    $stats['created']++;
                } else {
                    $stats['updated']++;
                }
            }
        }

        return $stats;
    }

    public function scrapeEvents($url)
    {
        $events = [];

        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->get($url);

            if ($response->successful()) {
                $html = $response->body();

                // Strategy 1: Look for JSON-LD structured data (Most precise)
                $scripts = $crawler->filter('script[type="application/ld+json"]');
                if ($scripts->count() > 0) {
                    $scripts->each(function ($node) use (&$events) {
                        try {
                            $json = json_decode($node->text(), true);
                            // Eventbrite might return a list or single object
                            $items = isset($json['@type']) ? [$json] : ($json ?? []);

                            foreach ($items as $item) {
                                if (isset($item['@type']) && $item['@type'] === 'Event') {
                                    $name = $item['name'] ?? null;
                                    $url = $item['url'] ?? null;

                                    if (!$name || !$url)
                                        continue;

                                    $location = null;
                                    $lat = null;
                                    $lng = null;

                                    if (isset($item['location'])) {
                                        $location = $item['location']['name'] ?? null;
                                        // Address fallback
                                        if (!$location && isset($item['location']['address'])) {
                                            $addr = $item['location']['address'];
                                            $location = is_string($addr) ? $addr : ($addr['streetAddress'] ?? $addr['addressLocality'] ?? 'Malaysia');
                                        }

                                        // NATIVE COORDINATES
                                        if (isset($item['location']['geo'])) {
                                            $lat = $item['location']['geo']['latitude'] ?? null;
                                            $lng = $item['location']['geo']['longitude'] ?? null;
                                        }
                                    }

                                    $category = $this->filterService->analyzeEvent($name, $item['description'] ?? '');
                                    if (!$category)
                                        continue;

                                    $events[] = [
                                        'name' => $name,
                                        'description' => ($item['description'] ?? "Event found on Eventbrite.") . ($location ? "\nLocation: $location" : ""),
                                        'category' => $category,
                                        'event_date' => $this->parseDate($item['startDate'] ?? null),
                                        'event_time' => isset($item['startDate']) ? Carbon::parse($item['startDate'])->format('H:i:s') : '09:00:00',
                                        'location' => $location ?: 'Malaysia',
                                        'latitude' => $lat,
                                        'longitude' => $lng, // NATIVE COORDS!
                                        'status' => 'upcoming',
                                        'created_by' => 1,
                                        'source_url' => $url,
                                        'image' => $this->imageDownloader->downloadOrFallback($item['image'] ?? null, $category, Str::slug($name)),
                                        'price' => 0,
                                        'max_participants' => 100
                                    ];
                                }
                            }
                        } catch (\Exception $e) {
                        }
                    });
                }

                // Strategy 2: HTML Fallback (if JSON-LD didn't yield results)
                if (empty($events)) {
                    $crawler->filter('.discover-search-desktop-card, .discover-search-mobile-card, article.eds-l-pad-all-4')->each(function (Crawler $node) use (&$events) {
                        try {
                            // Title
                            // Trying multiple common selectors
                            $titleNode = $node->filter('h3, .event-card__title, [data-spec="event-card__formatted-name"]');
                            if ($titleNode->count() > 0) {
                                $name = trim($titleNode->text());
                            } else {
                                return; // Skip if no title
                            }

                            // Date
                            $dateNode = $node->filter('.event-card__formatted-date, [data-spec="event-card__formatted-date"]');
                            $dateStr = $dateNode->count() ? $dateNode->text() : null;

                            // Location
                            $locNode = $node->filter('.event-card__formatted-venue, [data-spec="event-card__formatted-venue"]');
                            $location = $locNode->count() ? $locNode->text() : 'Malaysia';

                            // Link
                            $linkNode = $node->filter('a.event-card-link, a[href*="/e/"]');
                            $sourceUrl = $linkNode->count() ? $linkNode->attr('href') : null;

                            // Image
                            $imgNode = $node->filter('img.event-card-image, img.eds-event-card-content__image');
                            $imgUrl = $imgNode->count() ? $imgNode->attr('src') : null;

                            // Filter & Categorize using strict service
                            $category = $this->filterService->analyzeEvent($name, $location); // Using location as desc proxy if unavailable
                            $isRelevant = !is_null($category);

                            if ($name && $sourceUrl && $isRelevant) {
                                $events[] = [
                                    'name' => $name,
                                    'description' => "Event found on Eventbrite. " . ($location ? "Location: $location" : ""),
                                    'category' => $category,
                                    'event_date' => $this->parseDate($dateStr),
                                    'event_time' => '09:00:00', // Default
                                    'location' => $location,
                                    'max_participants' => 100,
                                    'price' => 0, // Hard to scrape price from list view reliable
                                    'status' => 'upcoming',
                                    'created_by' => 1,
                                    'source_url' => $sourceUrl,
                                    'image' => $this->imageDownloader->downloadOrFallback($imgUrl, $category, Str::slug($name)),
                                    'latitude' => null, // Will be geocoded later
                                    'longitude' => null,
                                ];
                            }

                        } catch (\Exception $e) {
                            // ignore broken card
                        }
                    });
                }
            }
        } catch (\Exception $e) {
            \Log::error("Eventbrite Scraper Error: " . $e->getMessage());
        }

        return $events;
    }

    protected function parseDate($dateStr)
    {
        if (!$dateStr)
            return now()->addDays(30)->format('Y-m-d');
        try {
            // Eventbrite dates like "Sat, Feb 24, 7:00 PM"
            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->addDays(30)->format('Y-m-d');
        }
    }
}
