<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ImageDownloaderService
{
    /**
     * Download an image (or reuse fallback) and return the local path relative to storage/public.
     *
     * @param string|null $url The external URL
     * @param string $category The event category (running, hiking, cycling) for fallback selection
     * @param string $slug A slug for the filename
     * @return string
     */
    public function downloadOrFallback($url, $category, $slug)
    {
        // 1. If no URL, return fallback immediately
        if (empty($url)) {
            return $this->getFallback($category);
        }

        // 2. Try to download
        try {
            $contents = Http::timeout(10)->get($url)->body();

            if (empty($contents)) {
                throw new \Exception("Empty content from URL");
            }

            // Determine extension (simple heuristic or default to jpg)
            $extension = 'jpg';
            if (str_contains($url, '.png'))
                $extension = 'png';
            if (str_contains($url, '.webp'))
                $extension = 'webp';

            // Create unique filename
            $filename = 'events/scraped/' . $slug . '-' . Str::random(6) . '.' . $extension;

            // Store it
            Storage::disk('public')->put($filename, $contents);

            return $filename;

        } catch (\Exception $e) {
            Log::warning("Image download failed for $slug: " . $e->getMessage());
            return $this->getFallback($category);
        }
    }

    public function getFallback($category)
    {
        // Ensure category is lowercase (just in case)
        $category = strtolower($category);

        // Return path to the pre-generated placeholders we just made
        // These live in public/storage/events/placeholders/
        // The return value is stored in DB, so 'events/placeholders/...'

        switch ($category) {
            case 'running':
                return 'events/placeholders/running.png';
            case 'hiking':
                return 'events/placeholders/hiking.png';
            case 'cycling':
                return 'events/placeholders/cycling.png';
            default:
                return 'events/placeholders/default.png';
        }
    }
}
