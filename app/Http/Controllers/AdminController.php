<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Chat;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{


    public function dashboard()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $stats = [
            'total_users' => User::count(),
            'total_chats' => Chat::count(),
            'total_sessions' => ChatSession::count(),
            'active_users_today' => User::whereDate('updated_at', today())->count(),
            'chats_today' => Chat::whereDate('created_at', today())->count(),
        ];

        $recent_users = User::latest()->take(5)->get();
        $top_users = User::withCount('chats')->orderBy('chats_count', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'top_users'));
    }

    public function users()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $users = User::withCount(['chats', 'chatSessions'])->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $user = User::findOrFail($id);
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin user');
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully');
    }

    public function reports()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
        $daily_stats = Chat::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $user_activity = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports', compact('daily_stats', 'user_activity'));
    }
}