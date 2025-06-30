<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Welcome page
Route::get('/', function () {
    return auth()->check() ? redirect()->route('chat.index') : view('welcome');
})->name('welcome');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/new-session', [ChatController::class, 'newSession'])->name('chat.new-session');
    Route::delete('/chat/session/{id}', [ChatController::class, 'deleteSession'])->name('chat.delete-session');
});
