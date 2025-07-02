@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header -->
    <div class="bg-gray-900/50 backdrop-blur-lg border-b border-gray-700/50 p-4">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">👥 User Management</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-blue-400 hover:text-blue-300">← Back to Dashboard</a>
        </div>
    </div>

    <div class="p-6">
        @if(session('success'))
        <div class="bg-green-600/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-600/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
        @endif

        <!-- Users Table -->
        <div class="bg-white/10 backdrop-blur-lg rounded-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Activity</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700/50">
                        @foreach($users as $user)
                        <tr class="hover:bg-gray-800/30 transition duration-200">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-white">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $user->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $user->role === 'admin' ? 'bg-red-600/20 text-red-300' : 'bg-blue-600/20 text-blue-300' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300">
                                    <div>{{ $user->chats_count }} chats</div>
                                    <div>{{ $user->chat_sessions_count }} sessions</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role !== 'admin')
                                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:text-red-300 text-sm">Delete</button>
                                </form>
                                @else
                                <span class="text-gray-500 text-sm">Protected</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection