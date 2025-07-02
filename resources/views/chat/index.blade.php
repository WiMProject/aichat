@extends('layouts.app')

@section('title', 'AI Chat')

@section('content')
<div class="h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 flex">
    <!-- Sidebar -->
    <div id="sidebar" class="w-80 bg-gray-900/50 backdrop-blur-lg border-r border-gray-700/50 flex flex-col transition-transform duration-300 transform translate-x-0 md:translate-x-0">
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
                <div class="group relative transform hover:scale-105 transition duration-200">
                    <a href="{{ route('chat.index', ['session_id' => $session->id]) }}" 
                       class="block w-full text-left p-3 rounded-lg transition duration-200 {{ $currentSession && $currentSession->id == $session->id ? 'bg-blue-600/20 border border-blue-500/30 shadow-lg' : 'hover:bg-gray-800/50 hover:shadow-md' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div class="w-2 h-2 bg-green-400 rounded-full {{ $currentSession && $currentSession->id == $session->id ? 'animate-pulse' : '' }}"></div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-white truncate">
                                        {{ $session->title }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        {{ $session->updated_at->diffForHumans() }} • {{ $session->chats->count() }} pesan
                                    </p>
                                </div>
                            </div>
                            <form action="{{ route('chat.delete-session', $session->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition duration-200">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-400 transition duration-200 transform hover:scale-110" onclick="return confirm('Hapus chat ini?')">
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
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center">
                    <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
            @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="block w-full text-center py-2 bg-red-600/20 hover:bg-red-600/30 text-red-300 text-xs rounded-lg border border-red-500/30 transition duration-200">
                🛡️ Admin Panel
            </a>
            @endif
        </div>
    </div>

    <!-- Sidebar Toggle Button -->
    <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 md:hidden bg-gray-800/80 backdrop-blur-sm text-white p-2 rounded-lg border border-gray-700/50 transition duration-200 hover:bg-gray-700/80">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col transition-all duration-300" id="main-content">
        @if($currentSession)
            <!-- Chat Header -->
            <div class="bg-gray-900/30 backdrop-blur-lg border-b border-gray-700/50 p-4">
                <!-- Desktop Sidebar Toggle -->
                <button id="sidebar-toggle-desktop" class="hidden md:block mb-4 text-gray-400 hover:text-white transition duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
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
                <!-- Typing Indicator (hidden by default) -->
                <div id="typing-indicator" class="hidden">
                    <div class="flex justify-start">
                        <div class="flex space-x-3 max-w-xs lg:max-w-md">
                            <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="bg-gray-800/50 backdrop-blur-sm text-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-lg border border-gray-700/30">
                                    <div class="flex space-x-1">
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-400 mt-1">AI sedang mengetik...</p>
                            </div>
                        </div>
                    </div>
                </div>
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
                                    <div class="bg-gray-800/50 backdrop-blur-sm text-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-lg border border-gray-700/30 relative group">
                                        <div class="text-sm whitespace-pre-line formatted-response">{!! \App\Http\Controllers\ChatController::formatForDisplay($chat->response) !!}</div>
                                        <button class="copy-btn absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-200 text-gray-400 hover:text-white p-1 rounded" data-text="{{ $chat->response }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
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
                <!-- Quick Actions -->
                <div class="flex space-x-2 mb-3 overflow-x-auto pb-2">
                    <button class="quick-action flex-shrink-0 px-3 py-1 bg-blue-600/20 hover:bg-blue-600/30 text-blue-300 text-xs rounded-full border border-blue-500/30 transition duration-200" data-message="Halo! Bagaimana kabar Anda?">
                        👋 Sapa
                    </button>
                    <button class="quick-action flex-shrink-0 px-3 py-1 bg-purple-600/20 hover:bg-purple-600/30 text-purple-300 text-xs rounded-full border border-purple-500/30 transition duration-200" data-message="Bisakah Anda membantu saya dengan coding?">
                        💻 Coding
                    </button>
                    <button class="quick-action flex-shrink-0 px-3 py-1 bg-green-600/20 hover:bg-green-600/30 text-green-300 text-xs rounded-full border border-green-500/30 transition duration-200" data-message="Berikan saya resep masakan yang mudah">
                        🍳 Resep
                    </button>
                    <button class="quick-action flex-shrink-0 px-3 py-1 bg-yellow-600/20 hover:bg-yellow-600/30 text-yellow-300 text-xs rounded-full border border-yellow-500/30 transition duration-200" data-message="Bagaimana cara memulai bisnis online?">
                        💼 Bisnis
                    </button>
                    <button class="quick-action flex-shrink-0 px-3 py-1 bg-red-600/20 hover:bg-red-600/30 text-red-300 text-xs rounded-full border border-red-500/30 transition duration-200" data-message="Tips hidup sehat apa yang bisa Anda berikan?">
                        🏥 Kesehatan
                    </button>
                </div>
                
                <form id="chat-form" class="flex space-x-3">
                    @csrf
                    <input type="hidden" id="session-id" value="{{ $currentSession->id }}">
                    <div class="flex-1 relative">
                        <textarea id="message-input" 
                                  name="message" 
                                  placeholder="Ketik pesan Anda di sini... (Enter = kirim, Shift+Enter = baris baru)" 
                                  class="w-full px-4 py-3 bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-none min-h-[48px] max-h-32 overflow-y-auto"
                                  rows="1"
                                  required></textarea>
                        <div class="absolute right-3 top-3 flex space-x-2">
                            <button type="button" id="voice-button" class="text-gray-400 hover:text-blue-400 transition duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </button>
                            <button type="button" id="emoji-button" class="text-gray-400 hover:text-yellow-400 transition duration-200">
                                😊
                            </button>
                        </div>
                    </div>
                    <button type="submit" 
                            id="send-button"
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 flex items-center space-x-2 shadow-lg transform hover:scale-105">
                        <span>Kirim</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </form>
                
                <!-- Emoji Picker (hidden by default) -->
                <div id="emoji-picker" class="hidden absolute bottom-20 right-4 bg-gray-800/90 backdrop-blur-lg rounded-xl p-4 border border-gray-700/50 shadow-2xl">
                    <div class="grid grid-cols-8 gap-2 text-lg">
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">😊</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">😂</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🤔</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">👍</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">❤️</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🔥</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">⭐</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🎉</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">💻</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🍳</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">💼</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🏥</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">📚</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">✈️</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🎵</button>
                        <button class="emoji-btn hover:bg-gray-700/50 p-1 rounded transition duration-200">🏃</button>
                    </div>
                </div>
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
    const typingIndicator = document.getElementById('typing-indicator');
    const emojiButton = document.getElementById('emoji-button');
    const emojiPicker = document.getElementById('emoji-picker');
    const voiceButton = document.getElementById('voice-button');

    // Sound effects
    function playSound(type) {
        const audioContext = new (window.AudioContext || window.webkitAudioContext)();
        let frequency;
        
        switch(type) {
            case 'send': frequency = 800; break;
            case 'receive': frequency = 600; break;
            case 'click': frequency = 400; break;
            default: frequency = 500;
        }
        
        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();
        
        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);
        
        oscillator.frequency.value = frequency;
        oscillator.type = 'sine';
        
        gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
        
        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.1);
    }

    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function showTypingIndicator() {
        if (typingIndicator) {
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
        }
    }

    function hideTypingIndicator() {
        if (typingIndicator) {
            typingIndicator.classList.add('hidden');
        }
    }

    function addMessage(message, response, time) {
        if (!chatMessages) return;
        
        // Add user message first
        const userMessageDiv = document.createElement('div');
        userMessageDiv.className = 'flex justify-end animate-fade-in';
        userMessageDiv.innerHTML = `
            <div class="max-w-xs lg:max-w-md">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-2xl rounded-br-md px-4 py-3 shadow-lg transform hover:scale-105 transition duration-200">
                    <p class="text-sm">${message}</p>
                </div>
                <p class="text-xs text-gray-400 mt-1 text-right">${time}</p>
            </div>
        `;
        
        const emptyState = chatMessages.querySelector('.text-center.py-12');
        if (emptyState) {
            emptyState.remove();
        }
        
        chatMessages.appendChild(userMessageDiv);
        playSound('send');
        scrollToBottom();
        
        // Show typing indicator
        showTypingIndicator();
        
        // Add AI response after delay
        setTimeout(() => {
            hideTypingIndicator();
            
            const aiMessageDiv = document.createElement('div');
            aiMessageDiv.className = 'flex justify-start animate-fade-in';
            aiMessageDiv.innerHTML = `
                <div class="flex space-x-3 max-w-xs lg:max-w-md">
                    <div class="w-8 h-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center flex-shrink-0 animate-pulse">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="bg-gray-800/50 backdrop-blur-sm text-gray-100 rounded-2xl rounded-bl-md px-4 py-3 shadow-lg border border-gray-700/30 transform hover:scale-105 transition duration-200 relative group">
                            <div class="text-sm whitespace-pre-line formatted-response">${formatResponseForDisplay(response)}</div>
                            <button class="copy-btn absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-200 text-gray-400 hover:text-white p-1 rounded" data-text="${response}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">${time}</p>
                    </div>
                </div>
            `;
            
            chatMessages.appendChild(aiMessageDiv);
            playSound('receive');
            scrollToBottom();
        }, 1000 + Math.random() * 2000); // Random delay 1-3 seconds
    }

    // Quick actions
    document.querySelectorAll('.quick-action').forEach(button => {
        button.addEventListener('click', function() {
            const message = this.getAttribute('data-message');
            messageInput.value = message;
            playSound('click');
            messageInput.focus();
        });
    });

    // Emoji picker
    if (emojiButton && emojiPicker) {
        emojiButton.addEventListener('click', function() {
            emojiPicker.classList.toggle('hidden');
            playSound('click');
        });

        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                messageInput.value += this.textContent;
                emojiPicker.classList.add('hidden');
                messageInput.focus();
                playSound('click');
            });
        });
    }

    // Voice input (placeholder)
    if (voiceButton) {
        voiceButton.addEventListener('click', function() {
            playSound('click');
            // Placeholder for voice input
            alert('Fitur voice input akan segera hadir! 🎤');
        });
    }

    // Hide emoji picker when clicking outside
    document.addEventListener('click', function(e) {
        if (!emojiButton.contains(e.target) && !emojiPicker.contains(e.target)) {
            emojiPicker.classList.add('hidden');
        }
    });

    // Auto-resize textarea and typing effects
    messageInput.addEventListener('input', function() {
        // Auto-resize textarea
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 128) + 'px';
        
        // Add typing sound occasionally
        if (Math.random() < 0.1) {
            playSound('click');
        }
    });

    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarToggleDesktop = document.getElementById('sidebar-toggle-desktop');
    const mainContent = document.getElementById('main-content');
    let sidebarOpen = true;

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
        if (sidebarOpen) {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        } else {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarToggleDesktop) {
        sidebarToggleDesktop.addEventListener('click', toggleSidebar);
    }

    // Enter to send, Shift+Enter for new line
    if (messageInput) {
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                if (e.shiftKey) {
                    // Shift+Enter: new line (allow default behavior)
                    return;
                } else {
                    // Enter: send message
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const message = this.value.trim();
                    if (message && chatForm && !sendButton.disabled) {
                        // Trigger form submit manually
                        const submitEvent = new Event('submit', {
                            bubbles: true,
                            cancelable: true
                        });
                        chatForm.dispatchEvent(submitEvent);
                    }
                }
            }
        });
        
        // Also handle keypress as fallback
        messageInput.addEventListener('keypress', function(e) {
            if ((e.key === 'Enter' || e.keyCode === 13) && !e.shiftKey) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    }

    if (chatForm) {
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const message = messageInput.value.trim();
            if (!message || sendButton.disabled) return;

            sendButton.disabled = true;
            sendButton.innerHTML = '<span>Mengirim...</span>';
            sendButton.classList.add('animate-pulse');
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
                        setTimeout(() => {
                            window.location.href = `{{ route('chat.index') }}?session_id=${data.session_id}`;
                        }, 2000);
                    }
                } else {
                    alert('Terjadi kesalahan saat mengirim pesan');
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan');
            } finally {
                sendButton.disabled = false;
                sendButton.innerHTML = '<span>Kirim</span><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>';
                sendButton.classList.remove('animate-pulse');
                messageInput.disabled = false;
                messageInput.focus();
            }
        });
    }

    // Copy functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.copy-btn')) {
            const btn = e.target.closest('.copy-btn');
            const text = btn.getAttribute('data-text');
            
            // Copy to clipboard
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopySuccess(btn);
                }).catch(() => {
                    fallbackCopy(text, btn);
                });
            } else {
                fallbackCopy(text, btn);
            }
        }
    });
    
    function fallbackCopy(text, btn) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showCopySuccess(btn);
        } catch (err) {
            console.error('Copy failed:', err);
        }
        document.body.removeChild(textArea);
    }
    
    function showCopySuccess(btn) {
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        playSound('click');
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 2000);
    }
    
    function formatResponseForDisplay(response) {
        // Format **text** menjadi bold
        response = response.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        
        // Format bullet points menjadi numbered list
        const lines = response.split('\n');
        let formatted = [];
        let listCounter = 0;
        let inList = false;
        
        lines.forEach(line => {
            line = line.trim();
            
            if (!line) {
                if (inList) {
                    inList = false;
                    listCounter = 0;
                }
                formatted.push('');
                return;
            }
            
            // Deteksi bullet points
            const bulletMatch = line.match(/^[•\*\-]\s*(.+)/);
            if (bulletMatch) {
                if (!inList) {
                    inList = true;
                    listCounter = 0;
                }
                listCounter++;
                formatted.push(`<span class='list-item'><strong>${listCounter}.</strong> ${bulletMatch[1]}</span>`);
            } else {
                if (inList) {
                    inList = false;
                    listCounter = 0;
                }
                formatted.push(line);
            }
        });
        
        return formatted.join('<br>');
    }

    scrollToBottom();
});
</script>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.quick-action:hover {
    transform: translateY(-1px);
}

#emoji-picker {
    z-index: 50;
}

/* Sidebar responsive */
@media (max-width: 768px) {
    #sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        z-index: 40;
    }
    
    #sidebar.-translate-x-full {
        transform: translateX(-100%);
    }
    
    #main-content {
        width: 100%;
    }
}

@media (min-width: 769px) {
    #sidebar.-translate-x-full {
        transform: translateX(-320px);
        width: 0;
        overflow: hidden;
    }
    
    #main-content {
        margin-left: 0;
        transition: margin-left 0.3s ease;
    }
}

/* Formatted response styling */
.formatted-response .list-item {
    display: block;
    margin: 4px 0;
    padding-left: 8px;
}

.formatted-response strong {
    font-weight: 600;
    color: #e5e7eb;
}

.copy-btn {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
}

.copy-btn:hover {
    background: rgba(0, 0, 0, 0.5);
}

/* Formatted response styling */
.formatted-response .list-item {
    display: block;
    margin: 4px 0;
    padding-left: 8px;
}

.formatted-response strong {
    font-weight: 600;
    color: #e5e7eb;
}

.copy-btn {
    background: rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(4px);
}

.copy-btn:hover {
    background: rgba(0, 0, 0, 0.5);
}
</style>
@endsection