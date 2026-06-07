<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('creator', 'participants')
            ->orderBy('created_at', 'desc');

        if ($request->has('category') && $request->category) {
            $category = $request->category;
            // Backward compatibility map
            $map = ['run' => 'running', 'hike' => 'hiking', 'cycle' => 'cycling'];
            $category = $map[$category] ?? $category;

            $query->where('category', $category);
        }

        $events = $query->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
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
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $validated['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
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
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }
            $validated['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}