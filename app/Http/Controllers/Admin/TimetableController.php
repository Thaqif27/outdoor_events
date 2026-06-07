<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $category = $request->get('category', 'all');

        $query = Event::with([
            'participants' => function ($query) {
                $query->orderBy('registered_at', 'desc');
            }
        ])->where('event_date', '>=', now());

        if ($category !== 'all') {
            // Map simple category slug to possible database values
            $categoryMap = [
                'run' => ['run', 'running', 'marathon'],
                'hike' => ['hike', 'hiking', 'trail', 'trek'],
                'cycling' => ['cycle', 'cycling', 'ride']
            ];

            // If the category exists in our map, search for all variations using whereIn
            if (array_key_exists($category, $categoryMap)) {
                $query->whereIn('category', $categoryMap[$category]);
            } else {
                // Fallback for unmapped categories
                $query->where('category', $category);
            }
        }

        // Sort by number of participants (highest first), then by event date (sooner first)
        $events = $query->withCount('participants')
            ->orderBy('participants_count', 'desc')
            ->orderBy('event_date', 'asc')
            ->get();

        return view('admin.timetable.index', compact('events', 'category'));
    }
}