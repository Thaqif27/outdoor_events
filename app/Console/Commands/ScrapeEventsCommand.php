<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\EventbriteScraperService;
use App\Services\Ticket2uScraperService;
use App\Services\JomRunScraperServices;

class ScrapeEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:scrape {source? : The source to scrape (eventbrite, ticket2u, jomrun)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape events from external sources';

    /**
     * Execute the console command.
     */
    public function handle(
        EventbriteScraperService $eventbrite,
        Ticket2uScraperService $ticket2u,
        JomRunScraperServices $jomrun
    ) {
        $source = $this->argument('source');

        $this->info("Starting scraper...");

        if ($source === 'eventbrite' || is_null($source)) {
            $this->info("Scraping Eventbrite...");
            $stats = $eventbrite->importEvents();
            $this->info("Eventbrite: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        if ($source === 'ticket2u' || is_null($source)) {
            $this->info("Scraping Ticket2U...");
            $stats = $ticket2u->importEvents();
            $this->info("Ticket2U: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        if ($source === 'jomrun' || is_null($source)) {
            $this->info("Scraping JomRun...");
            $stats = $jomrun->importEvents();
            $this->info("JomRun: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        if ($source === 'sgtrek' || is_null($source)) {
            $this->info('Scraping SGTrek...');
            $sgTrek = app(\App\Services\SGTrekScraperService::class);
            $stats = $sgTrek->importEvents();
            $this->info("SGTrek: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        if ($source === 'checkpointspot' || is_null($source)) {
            $this->info('Scraping CheckpointSpot...');
            $checkpoint = app(\App\Services\CheckpointSpotScraperService::class);
            $stats = $checkpoint->importEvents();
            // CheckpointSpot might return null or empty if blocked, so check if stats is array
            if (is_array($stats)) {
                $this->info("CheckpointSpot: Created {$stats['created']}, Updated {$stats['updated']}");
            }
        }

        if ($source === 'finishers' || is_null($source)) {
            $this->info('Scraping Finishers...');
            $finishers = app(\App\Services\FinishersScraperService::class);
            $stats = $finishers->importEvents();
            $this->info("Finishers: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        if ($source === 'meetup' || is_null($source)) {
            $this->info('Scraping Meetup...');
            $meetup = app(\App\Services\MeetupScraperService::class);
            $stats = $meetup->importEvents();
            $this->info("Meetup: Created {$stats['created']}, Updated {$stats['updated']}");
        }

        $this->info("Scraping completed.");
    }
}
