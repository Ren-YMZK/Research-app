<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameScoreController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameMatchingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/factor-game', function () {
    return view('factor-game');
})->name('factor-game');

Route::get('/factor-game-cpu', function () {
    return view('factor-game-cpu');
})->name('factor-game-cpu');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/game/save-score', [GameScoreController::class, 'store'])
    ->name('game.save-score')
    ->middleware('auth');

Route::get('/game/rankings', [GameScoreController::class, 'getRankings'])
    ->name('game.rankings');

Route::middleware(['auth'])->group(function () {
    Route::get('/match', [GameMatchingController::class, 'index'])->name('match');
    Route::post('/room/{roomId}/join', [GameMatchingController::class, 'join'])->name('room.join');
    Route::post('/room/{roomId}/leave', [GameMatchingController::class, 'leave'])->name('room.leave');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/game-matching', [GameMatchingController::class, 'index'])->name('game.matching');
    Route::post('/room/{roomId}/join', [GameMatchingController::class, 'join'])->name('room.join');
    Route::post('/room/{roomId}/leave', [GameMatchingController::class, 'leave'])->name('room.leave');
});

require __DIR__ . '/auth.php';
