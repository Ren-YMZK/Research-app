<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function showMultiplayerGame()
    {
        $roomId = uniqid('room_');
        return view('factor-game-multi', ['roomId' => $roomId]);
    }

    public function joinRoom($roomId)
    {
        return view('factor-game-multi', ['roomId' => $roomId]);
    }

    public function broadcastGameState(Request $request)
    {
        broadcast(new GameStateUpdate($request->gameState, $request->roomId))->toOthers();
        return response()->json(['status' => 'success']);
    }
}
