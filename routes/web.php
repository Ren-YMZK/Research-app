<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;

Route::get('/', function () {
    return view('home');
});

/*
Route::get('/factor-game', function () {
    return view('factor-game');
});
*/

Route::get('/factor-game', function () {
    return view('factor-game');  // factor-game.blade.phpを表示
})->name('factor-game');

Route::get('/', function () {
    return view('home');  // home.blade.phpを表示
})->name('home');

Route::get('/factor-game-multi', [GameController::class, 'showMultiplayerGame'])->name('factor-game-multi');
Route::post('/broadcast-game-state', [GameController::class, 'broadcastGameState']);
Route::get('/factor-game-multi/join/{roomId}', [GameController::class, 'joinRoom'])->name('factor-game-multi.join');
