<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Services\ImageDownloaderService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FixBrokenImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:fix-images {--force : Attempt to re-download even if an external URL is present}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix broken image links by downloading external images or setting placeholders';

    protected $imageDownloader;

    public function __construct(ImageDownloaderService $imageDownloader)
    {
        parent::__construct();
        $this->imageDownloader = $imageDownloader;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::all();
        $this->info("Checking " . count($events) . " events for broken images...");
        
        $bar = $this->output->createProgressBar(count($events));
        $bar->start();

        $fixedCount = 0;

        foreach ($events as $event) {
            $needsFix = false;
            $isUrl = Str::startsWith($event->image, ['http://', 'https://']);

            // 1. If empty, it's broken
            if (empty($event->image)) {
                $needsFix = true;
            } 
            // 2. If it's an external URL (always try to bring local for stability)
            elseif ($isUrl) {
                $needsFix = true;
            }
            // 3. If it's a local path but file doesn't exist
            elseif (!Storage::disk('public')->exists($event->image)) {
                $needsFix = true;
            }
            // 4. If it's a 0-byte file (common in failed scrapes)
            elseif (Storage::disk('public')->size($event->image) === 0) {
                $needsFix = true;
            }

            if ($needsFix) {
                $oldImage = $event->image;
                
                // Try to download if it was a URL
                if ($isUrl) {
                    $newPath = $this->imageDownloader->downloadOrFallback(
                        $oldImage, 
                        $event->category ?? 'running', 
                        Str::slug($event->name)
                    );
                } else {
                    // Just set to category placeholder if it was locally broken
                    $newPath = $this->imageDownloader->getFallback($event->category ?? 'running');
                }

                $event->image = $newPath;
                $event->save();
                $fixedCount++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Successfully processed images. Fixed/Updated: $fixedCount.");
    }
}
