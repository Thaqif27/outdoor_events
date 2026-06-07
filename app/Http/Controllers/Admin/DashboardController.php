<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Events now reflects Valid Future Events only, to match "Up to Date" expectation
        $totalEvents = Event::where('event_date', '>=', now()->startOfDay())->count();
        $totalUsers = User::where('role', 'user')->count();
        $upcomingEvents = $totalEvents; // They are essentially the same definition now

        // Maybe the user considers 'active' events as total? 
        // Let's stick to raw counts but ensure they are correct.

        return view('admin.dashboard', compact('totalEvents', 'totalUsers', 'upcomingEvents'));
    }
}
