<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventMapController extends Controller
{
    public function index()
    {
        // Fetch upcoming events with valid coordinates
        $events = Event::where('status', 'upcoming')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'name', 'latitude', 'longitude', 'category', 'event_date', 'location', 'image']);

        $apiKey = config('services.google_maps.api_key');

        return view('user.events.map', compact('events', 'apiKey'));
    }
}
