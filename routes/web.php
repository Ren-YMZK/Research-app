<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameScoreController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
});

Route::get('/factor-game', function () {
    return view('factor-game');
})->name('factor-game');

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
    Route::get('/match', [GameController::class, 'showMatchingScreen'])->name('match');
    Route::get('/game-multi/{roomId}', [GameController::class, 'showMultiplayerGame'])->name('game.multi');
});

Route::get('/game-matching', function () {
    return view('game-matching');
})->name('game-matching')->middleware('auth');

require __DIR__ . '/auth.php';
