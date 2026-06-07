<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\GeolocationService;

class EventController extends Controller
{
    protected $geolocationService;

    public function __construct(GeolocationService $geolocationService)
    {
        $this->geolocationService = $geolocationService;
    }

    public function index(Request $request)
    {
        $query = Event::with('creator', 'participants')
            ->where('status', 'upcoming')
            ->where('event_date', '>=', now()->startOfDay());

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $category = $request->input('category');
            // Backward compatibility map
            $map = ['run' => 'running', 'hike' => 'hiking', 'cycle' => 'cycling'];
            $category = $map[$category] ?? $category;

            $query->where('category', $category);
        }

        // Distance Filter
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 50); // Default radius if location present

        if ($latitude && $longitude) {
            $haversine = "(6371 * acos(cos(radians($latitude)) 
                         * cos(radians(latitude)) 
                         * cos(radians(longitude) - radians($longitude)) 
                         + sin(radians($latitude)) 
                         * sin(radians(latitude))))";

            $query->select('*')
                ->selectRaw("{$haversine} as distance")
                ->whereRaw("{$haversine} <= ?", [$radius])
                ->orderBy('distance');
        } else {
            $query->orderBy('event_date', 'asc');
        }

        $events = $query->paginate(12)->appends($request->query());

        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');

        // Pass location data back to view
        return view('user.events.index', compact('events', 'latitude', 'longitude', 'radius', 'googleMapsApiKey'));
    }

    public function show(Event $event)
    {
        $event->load('creator', 'participants', 'reviews.user');
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        return view('user.events.show', compact('event', 'googleMapsApiKey'));
    }

    public function create()
    {
        return view('user.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:running,hiking,cycling',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required',
            'location' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['created_by'] = auth()->id();

        // Auto-generate coordinates from location string
        $coordinates = $this->geolocationService->getCoordinates($validated['location']);
        if ($coordinates) {
            $validated['latitude'] = $coordinates['latitude'];
            $validated['longitude'] = $coordinates['longitude'];
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('user.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        // Only creator can edit
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('user.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:running,hiking,cycling',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'location' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Update coordinates if location changed
        $coordinates = $this->geolocationService->getCoordinates($validated['location']);
        if ($coordinates) {
            $validated['latitude'] = $coordinates['latitude'];
            $validated['longitude'] = $coordinates['longitude'];
        }

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('user.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('user.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    public function join(Event $event)
    {
        $user = auth()->user();

        if ($event->isFull()) {
            return back()->with('error', 'Event is full!');
        }

        if ($user->participatingEvents()->where('event_id', $event->id)->exists()) {
            // Already joined - redirect to external source
            if ($event->source_url) {
                return redirect()->away($event->source_url);
            }
            return back()->with('info', 'You have already joined this event!');
        }

        // Register user for the event
        $user->participatingEvents()->attach($event->id, ['status' => 'registered']);

        // Redirect to external registration page
        if ($event->source_url) {
            return redirect()->away($event->source_url);
        }

        return back()->with('success', 'Successfully joined the event!');
    }

    public function leave(Event $event)
    {
        auth()->user()->participatingEvents()->detach($event->id);
        return back()->with('success', 'Left the event successfully!');
    }

    public function joinExternal(Event $event)
    {
        $user = auth()->user();

        // 1. Track locally if not already joined
        if (!$user->participatingEvents()->where('event_id', $event->id)->exists()) {
            $user->participatingEvents()->attach($event->id, ['status' => 'registered']);
        }

        // 2. Redirect to external source
        if ($event->source_url) {
            return \Illuminate\Support\Facades\Redirect::away($event->source_url);
        }

        return back()->with('error', 'External link not found.');
    }
}