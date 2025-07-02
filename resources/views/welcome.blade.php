@extends('layouts.app')

@section('title', 'AI Chat - Asisten Cerdas Anda')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <!-- Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6 shadow-2xl">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    AI Chat
                </h1>
                <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
                    Asisten AI cerdas yang siap membantu Anda dalam berbagai topik. Dari coding hingga resep masakan, dari bisnis hingga kesehatan.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-semibold rounded-xl transition duration-200 shadow-2xl transform hover:scale-105 animate-pulse hover:animate-none">
                    <span>Coba Sekarang</span>
                    <svg class="ml-2 w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white/5 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Fitur Unggulan</h2>
                <p class="text-gray-300 text-lg">Mengapa memilih AI Chat sebagai asisten Anda?</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition duration-300 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Super Cepat</h3>
                    <p class="text-gray-300">Powered by Groq API dengan response time yang sangat cepat untuk pengalaman chat yang smooth.</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition duration-300 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Riwayat Tersimpan</h3>
                    <p class="text-gray-300">Semua percakapan Anda tersimpan dengan aman dan dapat diakses kapan saja dengan sistem session management.</p>
                </div>
                
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 text-center transform hover:scale-105 transition duration-300 hover:shadow-2xl">
                    <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-spin" style="animation-duration: 3s;">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Multi Topik</h3>
                    <p class="text-gray-300">Dari programming, bisnis, kesehatan, hingga resep masakan. AI kami siap membantu berbagai kebutuhan Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- How It Works -->
    <div class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Cara Penggunaan</h2>
                <p class="text-gray-300 text-lg">Mudah digunakan dalam 3 langkah sederhana</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Daftar & Login</h3>
                    <p class="text-gray-300">Buat akun gratis atau login dengan akun yang sudah ada untuk mulai menggunakan AI Chat.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Mulai Chat Baru</h3>
                    <p class="text-gray-300">Klik tombol "Chat Baru" dan mulai percakapan dengan AI tentang topik apapun yang Anda inginkan.</p>
                </div>
                
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-r from-pink-500 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6 text-white text-2xl font-bold">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-4">Nikmati Percakapan</h3>
                    <p class="text-gray-300">Dapatkan jawaban cerdas, simpan riwayat chat, dan kelola session sesuai kebutuhan Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Supported Topics -->
    <div class="py-20 bg-white/5 backdrop-blur-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Topik yang Didukung</h2>
                <p class="text-gray-300 text-lg">AI kami dapat membantu Anda dalam berbagai bidang</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">💻</div>
                    <h4 class="text-white font-semibold">Programming</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">🍳</div>
                    <h4 class="text-white font-semibold">Resep Masakan</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">🏥</div>
                    <h4 class="text-white font-semibold">Kesehatan</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">💼</div>
                    <h4 class="text-white font-semibold">Bisnis</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">📚</div>
                    <h4 class="text-white font-semibold">Pendidikan</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">✈️</div>
                    <h4 class="text-white font-semibold">Travel</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">🎵</div>
                    <h4 class="text-white font-semibold">Musik</h4>
                </div>
                <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-center border border-white/20">
                    <div class="text-3xl mb-3">🏃‍♂️</div>
                    <h4 class="text-white font-semibold">Olahraga</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Siap Memulai Percakapan dengan AI?
            </h2>
            <p class="text-xl text-gray-300 mb-8">
                Bergabunglah dengan ribuan pengguna yang sudah merasakan kemudahan AI Chat
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg font-semibold rounded-xl transition duration-200 shadow-2xl transform hover:scale-105">
                <span>Mulai Chat Sekarang</span>
                <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-black/30 backdrop-blur-lg border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">AI Chat</h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Asisten AI cerdas yang membantu Anda dalam berbagai kebutuhan sehari-hari. Dari pertanyaan teknis hingga percakapan santai.
                    </p>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Chat Real-time</li>
                        <li>Riwayat Tersimpan</li>
                        <li>Multi Session</li>
                        <li>Response Cepat</li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="text-white font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Cara Penggunaan</li>
                        <li>FAQ</li>
                        <li>Kontak Support</li>
                        <li>Privacy Policy</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/10 mt-8 pt-8 text-center">
                <p class="text-gray-400">
                    © 2024 AI Chat. All rights reserved. Made with ❤️ for better conversations.
                </p>
            </div>
        </div>
    </footer>
</div>
@endsection