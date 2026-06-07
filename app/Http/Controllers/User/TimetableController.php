<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get events user is participating in, grouped by category
        $runEvents = $user->participatingEvents()
            ->whereIn('category', ['run', 'running', 'marathon'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        $hikeEvents = $user->participatingEvents()
            ->whereIn('category', ['hike', 'hiking', 'trail'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();



        $cyclingEvents = $user->participatingEvents()
            ->whereIn('category', ['cycle', 'cycling', 'ride'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        $otherEvents = $user->participatingEvents()
            ->whereNotIn('category', [
                'run',
                'running',
                'marathon',
                'hike',
                'hiking',
                'trail',
                'cycle',
                'cycling',
                'ride'
            ])
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        return view('user.timetable.index', compact('runEvents', 'hikeEvents', 'cyclingEvents', 'otherEvents'));
    }
}