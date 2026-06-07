<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Daily scraping of all event sources
        $schedule->command('events:scrape')
            ->daily()
            ->at('03:00') // Run at 3 AM
            ->withoutOverlapping()
            ->onSuccess(function () {
                \Log::info('Daily scraping completed successfully');
            })
            ->onFailure(function () {
                \Log::error('Daily scraping failed');
            });

        // Cleanup old completed events (weekly)
        $schedule->command('events:cleanup')
            ->weekly()
            ->sundays()
            ->at('04:00');

        // Cleanup foreign/invalid events (bi-weekly)
        $schedule->command('events:cleanup-foreign')
            ->twiceDaily(2, 14);
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
