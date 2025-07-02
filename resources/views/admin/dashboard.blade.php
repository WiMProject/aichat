@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-gray-900/50 backdrop-blur-lg border-b border-gray-700/50 p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">🛡️ Admin Dashboard</h1>
            <div class="flex items-center space-x-4">
                <span class="text-gray-300">{{ auth()->user()->name }}</span>
                <a href="{{ route('chat.index') }}" class="text-blue-400 hover:text-blue-300">Back to Chat</a>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button class="text-red-400 hover:text-red-300">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Total Users</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_users'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Total Chats</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_chats'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Sessions</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['total_sessions'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Active Today</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['active_users_today'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <div class="flex items-center">
                    <div class="p-3 bg-red-500/20 rounded-lg">
                        <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-400 text-sm">Chats Today</p>
                        <p class="text-2xl font-bold text-white">{{ $stats['chats_today'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex space-x-4 mb-8">
            <a href="{{ route('admin.users') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                👥 Manage Users
            </a>
            <a href="{{ route('admin.reports') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                📊 View Reports
            </a>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Recent Users</h3>
                <div class="space-y-3">
                    @foreach($recent_users as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div>
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Users -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">Most Active Users</h3>
                <div class="space-y-3">
                    @foreach($top_users as $user)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div>
                            <p class="text-white font-medium">{{ $user->name }}</p>
                            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
                        </div>
                        <span class="px-2 py-1 bg-blue-600/20 text-blue-300 text-xs rounded-full">{{ $user->chats_count }} chats</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection