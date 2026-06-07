<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'joined' => auth()->check() ? auth()->user()->participatingEvents()->count() : 0,
            'favourites' => auth()->check() ? auth()->user()->favourites()->count() : 0,
            'upcoming' => Event::where('status', 'upcoming')->where('event_date', '>=', now()->startOfDay())->count(),
        ];

        return view('user.dashboard', compact('stats'));
    }
}
