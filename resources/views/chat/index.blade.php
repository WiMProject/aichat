@extends('layouts.app')

@section('title', 'AI Chat')

@section('content')
<div class="h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex">
    <!-- Sidebar -->
    <div class="w-80 bg-gray-900/50 backdrop-blur-lg border-r border-gray-700/50 flex flex-col">
        <!-- Header -->
        <div class="p-4 border-b border-gray-700/50">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h1 class="text-lg font-semibold text-white">AI Chat</h1>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-gray-400 hover:text-red-400 transition duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- New Chat Button -->
            <form action="{{ route('chat.new-session') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition duration-200 shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-sm font-medium">Chat Baru</span>
                </button>
            </form>
        </div>

        <!-- Chat Sessions -->
        <div class="flex-1 overflow-y-auto p-4 space-y-2">
            <h3 class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-3">Riwayat Chat</h3>
            @forelse($sessions as $session)
                <div class="group relative">
                    <a href="{{ route('chat.index', ['session_id' => $session->id]) }}" 
                       class="block w-full text-left p-3 rounded-lg transition duration-200 {{ $currentSession && $currentSession->id == $session->id ? 'bg-blue-600/20 border border-blue-500/30' : 'hover:bg-gray-800/50' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">
                                    {{ $session->title }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $session->updated_at->diffForHumans() }}
                                </p>
                            </div>
                            <form action="{{ route('chat.delete-session', $session->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition duration-200">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-400 transition duration-200" onclick="return confirm('Hapus chat ini?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </a>
                </div>
            @empty
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada chat</p>
                </div>
            @endforelse
        </div>

        <!-- User Info -->
        <div class="p-4 border-t border-gray-700/50">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col">
        @if($currentSession)
            <!-- Chat Header -->
            <div class="bg-gray-900/30 backdrop-blur-lg border-b border-gray-700/50 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-white">{{ $currentSession->title }}</h2>
                        <p class="text-sm text-gray-400">{{ $currentSession->chats->count() }} pesan</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-sm text-gray-400">Online</span>
                    </div>
                </div>
            </div>

            <!-- Messages -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6">
                @forelse($chats->reverse() as $chat)
                    <div class="space-y-4">
                        <!-- User Message -->
                        <div class="flex justify-end">
                            <div class="max-w-xs lg:max-w-md">
                                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-md px-4 py-3 shadow-lg">
                                    <p class="text-sm">{{ $chat->message }}</p>
                                </div>
                                <p class="text-xs text-gray-400 mt-1 text-right">{{ $chat->created_at->format('H:i') }}</p>
                            </div>
                        </div>
                        
                        <!-- AI Response -->
                        <div class="flex justify-start">
                            <div class="flex space-x-3 max-w-xs lg:max-w-md">
                                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="bg-gray-800/50 backdrop-blur-sm text-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-lg border border-gray-700/30">
                                        <div class="text-sm whitespace-pre-line">{!! nl2br(e($chat->response)) !!}</div>
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">{{ $chat->created_at->format('H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-white mb-2">Mulai Percakapan</h3>
                        <p class="text-gray-400">Kirim pesan pertama untuk memulai chat dengan AI!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="bg-gray-900/30 backdrop-blur-lg border-t border-gray-700/50 p-4">
                <form id="chat-form" class="flex space-x-3">
                    @csrf
                    <input type="hidden" id="session-id" value="{{ $currentSession->id }}">
                    <div class="flex-1 relative">
                        <input type="text" 
                               id="message-input" 
                               name="message" 
                               placeholder="Ketik pesan Anda di sini..." 
                               class="w-full px-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               required>
                    </div>
                    <button type="submit" 
                            id="send-button"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 flex items-center space-x-2 shadow-lg">
                        <span>Kirim</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500/20 to-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-3">Selamat Datang di AI Chat</h2>
                    <p class="text-gray-400 mb-6">Mulai percakapan baru dengan AI assistant yang cerdas</p>
                    <form action="{{ route('chat.new-session') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl transition duration-200 shadow-lg flex items-center space-x-2 mx-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>Mulai Chat Baru</span>
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const chatMessages = document.getElementById('chat-messages');
    const sessionId = document.getElementById('session-id');

    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function addMessage(message, response, time) {
        if (!chatMessages) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = 'space-y-4';
        messageDiv.innerHTML = `
            <div class="flex justify-end">
                <div class="max-w-xs lg:max-w-md">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-md px-4 py-3 shadow-lg">
                        <p class="text-sm">${message}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">${time}</p>
                </div>
            </div>
            <div class="flex justify-start">
                <div class="flex space-x-3 max-w-xs lg:max-w-md">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="bg-gray-800/50 backdrop-blur-sm text-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-lg border border-gray-700/30">
                            <div class="text-sm whitespace-pre-line">${response.replace(/\n/g, '<br>')}</div>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">${time}</p>
                    </div>
                </div>
            </div>
        `;
        
        const emptyState = chatMessages.querySelector('.text-center.py-12');
        if (emptyState) {
            emptyState.remove();
        }
        
        chatMessages.appendChild(messageDiv);
        scrollToBottom();
    }

    if (chatForm) {
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (!message) return;

            sendButton.disabled = true;
            sendButton.innerHTML = '<span>Mengirim...</span>';
            messageInput.disabled = true;

            try {
                const response = await fetch('{{ route("chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ 
                        message: message,
                        session_id: sessionId ? sessionId.value : null
                    })
                });

                const data = await response.json();
                
                if (response.ok) {
                    addMessage(data.message, data.response, data.created_at);
                    messageInput.value = '';
                    
                    if (data.session_id && (!sessionId || !sessionId.value)) {
                        window.location.href = `{{ route('chat.index') }}?session_id=${data.session_id}`;
                    }
                } else {
                    alert('Terjadi kesalahan saat mengirim pesan');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            } finally {
                sendButton.disabled = false;
                sendButton.innerHTML = '<span>Kirim</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
                messageInput.disabled = false;
                messageInput.focus();
            }
        });
    }

    scrollToBottom();
});
</script>
@endsection