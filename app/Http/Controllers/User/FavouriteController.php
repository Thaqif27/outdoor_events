<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index()
    {
        $favourites = auth()->user()->favourites()
            ->with('creator', 'participants')
            ->paginate(12);
        
        return view('user.favourites.index', compact('favourites'));
    }

    public function toggle(Event $event)
    {
        $user = auth()->user();
        
        if ($user->favourites()->where('event_id', $event->id)->exists()) {
            $user->favourites()->detach($event->id);
            return back()->with('success', 'Removed from favourites!');
        } else {
            $user->favourites()->attach($event->id);
            return back()->with('success', 'Added to favourites!');
        }
    }
}