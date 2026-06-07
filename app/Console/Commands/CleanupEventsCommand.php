<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Str;

class CleanupEventsCommand extends Command
{
    protected $signature = 'events:cleanup';
    protected $description = 'Remove events that do not match the required keywords';

    public function handle()
    {
        $keywords = ['run', 'runner', 'running', 'marathon', 'jog', 'hike', 'hiking', 'climb', 'trek', 'trail', 'walk', 'cycle', 'cycling', 'bike', 'biking', 'bicycle', 'ride', 'tour'];

        $events = Event::where(function ($q) {
            $q->where('description', 'like', '%Event found on Ticket2U%')
                ->orWhere('description', 'like', '%Event found on Eventbrite%');
        })->get();

        $deletedCount = 0;

        foreach ($events as $event) {
            $name = strtolower($event->name);
            $isRelevant = false;
            foreach ($keywords as $keyword) {
                if (str_contains($name, $keyword)) {
                    $isRelevant = true;
                    break;
                }
            }

            if (!$isRelevant) {
                $this->info("Deleting irrelevant event: " . $event->name);
                $event->delete();
                $deletedCount++;
            }
        }

        $this->info("Total deleted events: $deletedCount");
    }
}
