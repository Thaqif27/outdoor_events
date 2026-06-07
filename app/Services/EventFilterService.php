<?php

namespace App\Services;

class EventFilterService
{
    /**
     * Determine if an event is relevant and return its category.
     * Returns null if irrelevant.
     *
     * @param string $name
     * @param string|null $description
     * @return string|null
     */
    public function analyzeEvent($name, $description = '')
    {
        $text = strtolower($name . ' ' . $description);
        $nameLower = strtolower($name);

        // 1. Negative Filters (Exclude completely unrelated stuff)
        // If these words appear in the TITLE, it's almost certainly spam/irrelevant for "Outdoor Events"
        $negativeTitleKeywords = [
            'webinar',
            'online class',
            'zoom link',
            'virtual conference',
            'career fair',
            'job fair',
            'networking night',
            'seminar',
            'workshop',
            'expo',
            'exhibition',
            'real estate',
            'property',
            'investment',
            'wealth',
            'factory',
            'museum',
            'aviation',
            'yacht',
            'luxury',
            'dining',
            'gala',
            'concert',
            'music festival',
            'party',
            'clubbing',
            'standup',
            'comedy',
            'buffet',
            'high tea',
            'valentine',
            'dinner',
            'party',
            'celebration',
            'anniversary',
            'wedding',
            'sale',
            'clearance',
            'stock',
            'warehouse',
            'fair',
            'bazaar',
            'market',
            'church',
            'prayer',
            'religious',
            'temple',
            'mosque',
            'prayer',
            'spiritual',
            'retreat',
            'yoga',
            'meditation',
            'wellness',
            'health',
            'medical',
            'hospital',
            'doctor',
            'clinic'
        ];

        foreach ($negativeTitleKeywords as $keyword) {
            if (str_contains($nameLower, $keyword)) {
                return null; // Irrelevant
            }
        }

        // 2. Determine Category (Weighted)

        // Priority 1: Cycling
        // Removed 'tour' and 'ride' as standalone to avoid "Factory Tour" or "Bus Ride"
        if ($this->matchesKeywords($nameLower, ['cycle', 'cycling', 'bike', 'mtb', 'bicycling', 'kayuhan', 'crit', 'criterion', 'fun ride', 'bike tour'])) {
            return 'cycling';
        }

        // Priority 2: Running
        if ($this->matchesKeywords($nameLower, ['run', 'running', 'marathon', 'jog', 'sprint', 'ultra', 'fun run', 'triathlon', 'biathlon', 'race', 'larian', 'dash', 'chase'])) {
            return 'running';
        }

        // Priority 3: Hiking
        if ($this->matchesKeywords($nameLower, ['hike', 'hiking', 'climb', 'trek', 'peak', 'walk', 'trail', 'expedition', 'mount', 'gunung', 'bukit', 'jungle', 'forest', 'nature', 'cave'])) {
            // Double check description for non-outdoor context if title is just "The Climb"
            if (str_contains($text, 'career') || str_contains($text, 'corporate') || str_contains($text, 'sales') || str_contains($text, 'chart')) {
                return null; // Likely metaphorical
            }
            return 'hiking';
        }

        // 4. Strict Fallback: If we haven't matched a category yet, check description?

        if ($this->matchesKeywords($text, ['fun run', '5km', '10km', 'marathon', 'half-marathon'])) {
            return 'running';
        }

        if ($this->matchesKeywords($text, ['hiking trip', 'jungle trekking', 'mountain climbing'])) {
            return 'hiking';
        }

        // If no category matched, it's not an event we want
        return null;
    }

    protected function matchesKeywords($text, array $keywords)
    {
        foreach ($keywords as $keyword) {
            if (str_contains($text, $keyword)) {
                return true;
            }
        }
        return false;
    }
}
