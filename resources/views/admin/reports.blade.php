@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-gray-900/50 backdrop-blur-lg border-b border-gray-700/50 p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">📊 Reports & Analytics</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-400 hover:text-blue-300">← Back to Dashboard</a>
        </div>
    </div>

    <div class="p-6">
        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Chat Activity Chart -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">📈 Daily Chat Activity (Last 30 Days)</h3>
                <div class="h-64 flex items-end justify-between space-x-1">
                    @php
                        $maxChats = $daily_stats->max('count') ?: 1;
                    @endphp
                    @foreach($daily_stats as $stat)
                    <div class="flex flex-col items-center">
                        <div class="bg-gradient-to-t from-blue-600 to-purple-600 rounded-t" 
                             style="height: {{ ($stat->count / $maxChats) * 200 }}px; width: 20px;"
                             title="{{ $stat->count }} chats on {{ $stat->date }}">
                        </div>
                        <span class="text-xs text-gray-400 mt-1 transform rotate-45 origin-left">
                            {{ \Carbon\Carbon::parse($stat->date)->format('m/d') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-400">Total: {{ $daily_stats->sum('count') }} chats</span>
                </div>
            </div>

            <!-- User Registration Chart -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h3 class="text-lg font-semibold text-white mb-4">👤 User Registrations (Last 30 Days)</h3>
                <div class="h-64 flex items-end justify-between space-x-1">
                    @php
                        $maxUsers = $user_activity->max('count') ?: 1;
                    @endphp
                    @foreach($user_activity as $activity)
                    <div class="flex flex-col items-center">
                        <div class="bg-gradient-to-t from-green-600 to-blue-600 rounded-t" 
                             style="height: {{ ($activity->count / $maxUsers) * 200 }}px; width: 20px;"
                             title="{{ $activity->count }} users on {{ $activity->date }}">
                        </div>
                        <span class="text-xs text-gray-400 mt-1 transform rotate-45 origin-left">
                            {{ \Carbon\Carbon::parse($activity->date)->format('m/d') }}
                        </span>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <span class="text-sm text-gray-400">Total: {{ $user_activity->sum('count') }} new users</span>
                </div>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h4 class="text-white font-semibold mb-2">📊 Average Daily Chats</h4>
                <p class="text-2xl font-bold text-blue-400">
                    {{ $daily_stats->count() > 0 ? round($daily_stats->sum('count') / $daily_stats->count(), 1) : 0 }}
                </p>
                <p class="text-sm text-gray-400">per day</p>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h4 class="text-white font-semibold mb-2">🚀 Peak Day</h4>
                @php
                    $peakDay = $daily_stats->sortByDesc('count')->first();
                @endphp
                <p class="text-2xl font-bold text-green-400">
                    {{ $peakDay ? $peakDay->count : 0 }}
                </p>
                <p class="text-sm text-gray-400">
                    {{ $peakDay ? \Carbon\Carbon::parse($peakDay->date)->format('M d') : 'N/A' }}
                </p>
            </div>

            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
                <h4 class="text-white font-semibold mb-2">📈 Growth Rate</h4>
                @php
                    $firstWeek = $user_activity->take(7)->sum('count');
                    $lastWeek = $user_activity->reverse()->take(7)->sum('count');
                    $growth = $firstWeek > 0 ? (($lastWeek - $firstWeek) / $firstWeek) * 100 : 0;
                @endphp
                <p class="text-2xl font-bold {{ $growth >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $growth >= 0 ? '+' : '' }}{{ round($growth, 1) }}%
                </p>
                <p class="text-sm text-gray-400">vs last week</p>
            </div>
        </div>

        <!-- Export Options -->
        <div class="mt-8 bg-white/10 backdrop-blur-lg rounded-xl p-6 border border-white/20">
            <h3 class="text-lg font-semibold text-white mb-4">📥 Export Data</h3>
            <div class="flex space-x-4">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                    Export Users CSV
                </button>
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                    Export Chat Data
                </button>
                <button class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition duration-200">
                    Generate PDF Report
                </button>
            </div>
        </div>
    </div>
</div>
@endsection