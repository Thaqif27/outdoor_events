<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JomRunScraperServices;
use Illuminate\Http\Request;

class ScraperController extends Controller
{
    protected $scraperService;

    public function __construct(JomRunScraperServices $scraperService)
    {
        $this->scraperService = $scraperService;
    }

    public function index()
    {
        return view('admin.scraper.index');
    }

    public function scrape(Request $request)
    {
        set_time_limit(0); // Prevent timeout during scraping

        $request->validate([
            'target_url' => 'required|url',
            'force_category' => 'nullable|in:run,hike,cycling',
        ]);

        try {
            $stats = $this->scraperService->importEvents(
                $request->input('target_url'),
                $request->input('force_category')
            );

            $message = "Scraping completed! Created: {$stats['created']}, Updated: {$stats['updated']}.";

            return redirect()->route('admin.scraper.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.scraper.index')
                ->with('error', 'Failed to scrape events: ' . $e->getMessage());
        }
    }
}