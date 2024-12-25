<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function showMatchingScreen()
    {
        return view('game-matching');
    }

    public function showMultiplayerGame($roomId)
    {
        // 有効なルームIDかチェック
        if (!in_array($roomId, ['A', 'B', 'C'])) {
            return redirect()->route('match')->with('error', '無効なルームIDです');
        }

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
