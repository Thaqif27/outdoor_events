<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Carbon\Carbon;
use Illuminate\Support\Str;

class JomRunScraperServices
{
    /**
     * Import events from JomRun given a URL.
     * Checks if the URL is a list or single event.
     *
     * @param string $url
     * @param string|null $forceCategory
     * @return int Number of events imported
     */
    public function importEvents($url = "https://www.jomrun.com/event/list", $forceCategory = null)
    {
        $importedCount = 0;

        try {
            $response = Http::timeout(60)->get($url);

            if ($response->failed()) {
                throw new \Exception("Failed to reach URL: " . $url);
            }

            $crawler = new Crawler($response->body());

            // Strategy 1: Check if this is a single event page
            // A single event page typically has a "Register" button or specific metadata
            $isSingleEvent = $this->isSingleEventPage($crawler);

            echo "Debug: Checking URL: $url\n";
            echo "Debug: isSingleEventPage result: " . ($isSingleEvent ? 'YES' : 'NO') . "\n";

            if ($isSingleEvent) {
                echo "Debug: Processing as single event...\n";
                if ($this->processSingleEventPage($crawler, $url, $forceCategory)) {
                    $importedCount++;
                }
            } else {
                echo "Debug: Processing as list page...\n";

                // Get ALL links and filter manually to be safe
                $allLinks = $crawler->filter('a')->each(function (Crawler $node) {
                    return $node->attr('href');
                });

                echo "Debug: Total links found on page: " . count($allLinks) . "\n";
                if (count($allLinks) > 0) {
                    echo "Debug: First 5 links: " . implode(', ', array_slice($allLinks, 0, 5)) . "\n";
                }

                $eventLinks = array_filter($allLinks, function ($href) {
                    return str_contains((string) $href, '/event/');
                });

                echo "Debug: Found event links count: " . count($eventLinks) . "\n";

                $uniqueLinks = array_unique($eventLinks);

                // Filter out non-event links (like /event/list itself or login)
                $uniqueLinks = array_filter($uniqueLinks, function ($link) {
                    return !str_contains((string) $link, '/event/list') &&
                        !str_contains((string) $link, 'login') &&
                        !str_contains((string) $link, 'register');
                });

                foreach ($uniqueLinks as $link) {
                    // Normalize URL
                    if (!str_starts_with($link, 'http')) {
                        $link = 'https://www.jomrun.com' . (str_starts_with($link, '/') ? '' : '/') . $link;
                    }

                    // Scrape the individual event page
                    try {
                        $eventResponse = Http::timeout(30)->get($link);
                        if ($eventResponse->ok()) {
                            $eventCrawler = new Crawler($eventResponse->body());
                            if ($this->processSingleEventPage($eventCrawler, $link, $forceCategory)) {
                                $importedCount++;
                            }
                        }
                        // Polite delay
                        usleep(200000);
                    } catch (\Exception $e) {
                        continue;
                    }
                }
            }

        } catch (\Exception $e) {
            throw $e;
        }

        return $importedCount;
    }

    private function isSingleEventPage(Crawler $crawler)
    {
        // Check for specific single-event elements. 
        // Example: The "Join Now" or "Register" button usually exists on event pages
        // Or checking if h1 exists and looks like an event title
        return $crawler->filter('h1')->count() > 0 && $crawler->filter('.event-detail-container, .event-info')->count() > 0;
    }

    private function processSingleEventPage(Crawler $crawler, $url, $forceCategory = null)
    {
        try {
            // Updated Selectors based on generic bootstrap/web assumptions + user feedback implicit
            // Title: usually the main H1
            $name = $crawler->filter('h1')->count() ? $crawler->filter('h1')->text() : null;

            // If H1 is missing, try H2 or H3 inside a main content area
            if (!$name) {
                $name = $crawler->filter('.event-title, h2.title')->count() ? $crawler->filter('.event-title, h2.title')->text() : 'Unknown Event';
            }

            // Date: often in a time/date icon area
            // Try to find text that looks like a date if specific class fails
            $dateText = $crawler->filter('.event-date, .date')->count() ? $crawler->filter('.event-date, .date')->text() : null;

            // Fallback for date: search entire text for date pattern? (Risky, skip for now. Default to +1 month)

            // Location
            $location = $crawler->filter('.event-location, .location, .venue')->count() ? $crawler->filter('.event-location, .location, .venue')->text() : 'TBC';

            // Image (Hero image)
            $imageUrl = null;
            if ($crawler->filter('img.banner, .event-banner img')->count()) {
                $imageUrl = $crawler->filter('img.banner, .event-banner img')->attr('src');
            }

            // Determine Category
            $category = $forceCategory ?: $this->determineCategory($name);

            // Parse Date
            try {
                // Remove extra spaces strings like "Date:" "Time:"
                $dateAndText = trim(str_replace(['Date:', 'Time:'], '', $dateText));
                $eventDate = $dateAndText ? Carbon::parse($dateAndText) : Carbon::now()->addMonth();
            } catch (\Exception $e) {
                $eventDate = Carbon::now()->addMonth();
            }

            // Save to DB
            Event::updateOrCreate(
                [
                    'name' => trim($name),
                    'event_date' => $eventDate->format('Y-m-d'),
                ],
                [
                    'description' => "Imported from JomRun (" . $url . ")",
                    'category' => $category,
                    'event_time' => '07:00:00',
                    'location' => trim($location),
                    'max_participants' => 1000,
                    'price' => 50.00, // Placeholder price
                    'status' => 'upcoming',
                    'created_by' => 1,
                    // 'image' => $imageUrl // If we want to save image URL? For now we only support local upload in Controller.
                ]
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function determineCategory($title)
    {
        $title = strtolower($title);

        if (str_contains($title, 'hike') || str_contains($title, 'trail') || str_contains($title, 'bukit') || str_contains($title, 'mountain') || str_contains($title, 'climb')) {
            return 'hike';
        }

        if (str_contains($title, 'ride') || str_contains($title, 'cycle') || str_contains($title, 'cycling') || str_contains($title, 'bike') || str_contains($title, 'tour')) {
            return 'cycling';
        }

        // Default to run
        return 'run';
    }
}