<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->withCount('participatingEvents', 'createdEvents')
            ->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Cannot delete admin users!');
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }
}