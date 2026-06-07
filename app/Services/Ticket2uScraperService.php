<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\DomCrawler\Crawler;
use Spatie\Browsershot\Browsershot;
use App\Models\Event;
use App\Services\GeolocationService;
use App\Services\ImageDownloaderService;
use App\Services\EventFilterService;
use Carbon\Carbon;

class Ticket2uScraperService
{
    protected $baseUrl = 'https://www.ticket2u.com.my';
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
        // Ticket2U events URL
        $targetUrl = $targetUrl ?? 'https://www.ticket2u.com.my/event/list';

        $stats = ['created' => 0, 'updated' => 0];
        $events = $this->scrapeEvents($targetUrl);

        foreach ($events as $eventData) {
            // Geocode & Validate Validation (Strict Malaysia Check)
            $coordinates = null;
            if (!empty($eventData['location'])) {
                $coordinates = $this->geolocationService->getCoordinates($eventData['location']);
            }

            if (!$coordinates) {
                \Log::info("Ticket2u: Skipped event '{$eventData['name']}' - Location validation failed (Not in Malaysia or invalid).");
                continue;
            }

            $eventData['latitude'] = $coordinates['latitude'];
            $eventData['longitude'] = $coordinates['longitude'];

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

        return $stats;
    }

    public function scrapeEvents($url)
    {
        $events = [];

        try {
            // Use Browsershot to fetch dynamic content
            // Assuming node is in path, otherwise setNodeBinary() might be needed
            $html = \Spatie\Browsershot\Browsershot::url($url)
                ->setOption('args', ['--disable-web-security', '--no-sandbox'])
                ->windowSize(1920, 10000)
                ->waitUntilNetworkIdle()
                ->bodyHtml();

            \Illuminate\Support\Facades\File::put(storage_path('logs/ticket2u_debug.html'), $html);

            if ($html) {
                $crawler = new Crawler($html);

                // Ticket2U List Structure handling
                // Card is figure.fig
                $crawler->filter('.event__listing .fig')->each(function (Crawler $node) use (&$events) {
                    try {
                        // Title
                        $titleNode = $node->filter('h3 > a');
                        if ($titleNode->count() === 0)
                            return;
                        $name = trim($titleNode->text());

                        // Detail URL
                        $sourceUrl = $titleNode->attr('href');
                        if ($sourceUrl && !str_starts_with($sourceUrl, 'http')) {
                            $sourceUrl = $this->baseUrl . $sourceUrl;
                        }

                        // Date - composed of parts .fig__month, .fig__date
                        $monthNode = $node->filter('.fig__month');
                        $dayNode = $node->filter('.fig__date');
                        $dateStr = null;
                        if ($monthNode->count() && $dayNode->count()) {
                            // "Feb 28"
                            $dateStr = $dayNode->text() . ' ' . $monthNode->text();
                        }

                        // Location
                        $locNode = $node->filter('address.fig__pre');
                        $location = $locNode->count() ? trim($locNode->text()) : 'Malaysia';

                        // Image - bg image in style attribute of .fig__image-bg
                        $imgNode = $node->filter('.fig__image-bg');
                        $imgUrl = null;
                        if ($imgNode->count()) {
                            $style = $imgNode->attr('style'); // background-image: url("...");
                            // Matches including unescaped quotes etc.
                            if (preg_match('/url\((.*?)\)/', $style, $matches)) {
                                $rawUrl = $matches[1];
                                // Remove surrounding quotes (single, double, or &quot;)
                                $imgUrl = trim($rawUrl, '"\'&quot; ');
                                // Fix potentially encoded &quot; inside
                                $imgUrl = str_replace('&quot;', '', $imgUrl);
                            }
                        }

                        // Filter & Categorize using strict service
                        $category = $this->filterService->analyzeEvent($name, $location); // Using location as desc proxy if desc unavailable here

                        // If category is null, it's irrelevant
                        $isRelevant = !is_null($category);

                        if ($name && $sourceUrl && $isRelevant) {
                            $events[] = [
                                'name' => $name,
                                'description' => "Event found on Ticket2U. " . ($location ? "Location: $location" : ""),
                                'category' => $category,
                                'event_date' => $this->parseDate($dateStr),
                                'event_time' => '08:00:00',
                                'location' => $location,
                                'max_participants' => 100,
                                'price' => 0,
                                'status' => 'upcoming',
                                'created_by' => 1,
                                'source_url' => $sourceUrl,
                                'image' => $this->imageDownloader->downloadOrFallback($imgUrl, $category, \Illuminate\Support\Str::slug($name)),
                            ];
                        }

                    } catch (\Exception $e) {
                        // ignore
                    }
                });
            }
        } catch (\Exception $e) {
            \Log::error("Ticket2U Scraper Error: " . $e->getMessage());
        }

        \Log::info("Ticket2U: Found " . count($events) . " events.");
        return $events;
    }

    protected function parseDate($dateStr)
    {
        if (!$dateStr)
            return now()->addDays(30)->format('Y-m-d');
        try {
            // Clean up date string if it has ranges or extra text
            // Ticket2U dates might differ, robust parsing needed or just return safely
            return Carbon::parse($dateStr)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->addDays(30)->format('Y-m-d');
        }
    }
}
