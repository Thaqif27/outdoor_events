<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user participated in the event
        if (!auth()->user()->participatingEvents()->where('event_id', $event->id)->exists()) {
            return back()->with('error', 'You must join the event to leave a review!');
        }

        Review::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => auth()->id(),
            ],
            $validated
        );

        return back()->with('success', 'Review submitted successfully!');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $review->delete();
        return back()->with('success', 'Review deleted successfully!');
    }
}
